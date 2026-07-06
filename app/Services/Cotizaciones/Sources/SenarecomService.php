<?php

namespace App\Services\Cotizaciones\Sources;

use App\DTOs\PriceQuote;
use App\Services\Cotizaciones\Contracts\PriceSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SenarecomService implements PriceSourceInterface
{
    private const URL = 'https://www.senarecom.gob.bo/estadisticas.php';

    private const MAPPING = [
        'ZINC' => ['metal' => 'Zn', 'name' => 'Zinc', 'unit' => 'USD/LF'],
        'PLATA' => ['metal' => 'Ag', 'name' => 'Plata', 'unit' => 'USD/OT'],
        'PLOMO' => ['metal' => 'Pb', 'name' => 'Plomo', 'unit' => 'USD/LF'],
    ];

    public function sourceName(): string
    {
        return 'SENARECOM';
    }

    public function fetch(): array
    {
        try {
            $response = Http::withoutVerifying()->timeout(15)->retry(2, 1000)->get(self::URL);

            if (! $response->successful()) {
                Log::warning('SENARECOM responded with {status}', [
                    'status' => $response->status(),
                ]);

                return [];
            }

            return $this->parseHtml($response->body());
        } catch (\Throwable $e) {
            Log::warning('SENARECOM request failed: {error}', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    private function parseHtml(string $html): array
    {
        preg_match_all('/<a\s+href="#mineral_\d+"[^>]*>(.*?)<\/a>/s', $html, $matches);

        $prices = [];
        foreach ($matches[1] as $item) {
            preg_match('/([\wÁÉÍÓÚÑ\s]+?)(?:\s*<span>(.*?)<\/span>)?\s*<div>(.*?)<\/div>/s', trim($item), $parts);

            if (empty($parts)) {
                continue;
            }

            $name = mb_strtoupper(trim($parts[1]));
            $config = self::MAPPING[$name] ?? null;

            if ($config === null) {
                continue;
            }

            $rawPrice = str_replace(',', '.', trim($parts[3]));

            if (! is_numeric($rawPrice)) {
                continue;
            }

            $prices[] = new PriceQuote(
                metal: $config['metal'],
                price: (float) $rawPrice,
                unit: $config['unit'],
                source: $this->sourceName(),
                updatedAt: Carbon::now(),
                metalName: $config['name'],
            );
        }

        return $prices;
    }
}
