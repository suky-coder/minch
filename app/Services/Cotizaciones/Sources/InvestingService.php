<?php

namespace App\Services\Cotizaciones\Sources;

use App\DTOs\PriceQuote;
use App\Services\Cotizaciones\Contracts\PriceSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InvestingService implements PriceSourceInterface
{
    private const PAGES = [
        'https://www.investing.com/commodities/gold' => ['metal' => 'Au', 'name' => 'Oro', 'unit' => 'USD/OT'],
        'https://www.investing.com/commodities/silver' => ['metal' => 'Ag', 'name' => 'Plata', 'unit' => 'USD/OT'],
        'https://www.investing.com/commodities/copper' => ['metal' => 'Cu', 'name' => 'Cobre', 'unit' => 'USD/LB'],
        'https://www.investing.com/commodities/lead' => ['metal' => 'Pb', 'name' => 'Plomo', 'unit' => 'USD/TM'],
        'https://www.investing.com/commodities/zinc' => ['metal' => 'Zn', 'name' => 'Zinc', 'unit' => 'USD/TM'],
    ];

    public function sourceName(): string
    {
        return 'Investing.com';
    }

    public function fetch(): array
    {
        $quotes = [];

        foreach (self::PAGES as $url => $config) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->retry(2, 1000)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'es-BO,es-419;q=0.9,es;q=0.8,en;q=0.7',
                        'Referer' => 'https://www.investing.com/commodities/',
                    ])
                    ->get($url);

                if (! $response->successful()) {
                    Log::warning('Investing.com {metal} responded with {status}', [
                        'metal' => $config['metal'],
                        'status' => $response->status(),
                    ]);

                    continue;
                }

                $price = $this->extractPrice($response->body());

                if ($price === null) {
                    Log::warning('Investing.com {metal}: no se pudo extraer precio', [
                        'metal' => $config['metal'],
                    ]);

                    continue;
                }

                $quotes[] = new PriceQuote(
                    metal: $config['metal'],
                    price: $price,
                    unit: $config['unit'],
                    source: $this->sourceName(),
                    updatedAt: Carbon::now(),
                    metalName: $config['name'],
                );
            } catch (\Throwable $e) {
                Log::warning('Investing.com {metal} failed: {error}', [
                    'metal' => $config['metal'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $quotes;
    }

    private function extractPrice(string $body): ?float
    {
        $strategies = [
            // FAQ: "The current price of Gold futures is 4,078.00"
            '/The current price of \w+(?:\s+futures)?\s+is\s*([\d,]+\.\d+)/i',

            // Sidebar widget: "Gold4,078.75-4.35" or "Copper6.1790+0.0235"
            '/(?:Gold|Silver|Copper|Lead|Zinc)([\d,]+\.\d+)(?:[A-Z]+)?[+-]/i',

            // Main quote: "4,078.00-4.40(-0.11%)"
            '/([\d,]+\.\d+)[+-][\d,]+\.\d+\([+-]?[\d,]+\.\d+%\)/',

            // Futures table: "Jul 26 3740.00s 0.00"
            '/\w{3}\s+\d{2}\s+([\d,]+\.\d+)s\s+[\d,]+\.\d+/',
        ];

        foreach ($strategies as $pattern) {
            if (preg_match($pattern, $body, $m)) {
                $cleaned = str_replace(',', '', $m[1]);

                if (is_numeric($cleaned)) {
                    return (float) $cleaned;
                }
            }
        }

        return null;
    }
}
