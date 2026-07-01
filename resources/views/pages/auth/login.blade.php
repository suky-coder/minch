<x-guest-layout>
    <div class="w-full max-w-md rounded-2xl bg-dark-800 p-8 shadow-xl ring-1 ring-dark-600">
        {{-- Logo --}}
        <div class="mb-8 flex flex-col items-center gap-3">
            <img src="{{ asset('image/logo.png') }}" alt="{{ config('app.name') }}" class="h-14 w-auto">
            <h1 class="text-xl font-semibold text-dark-200">{{ config('app.name') }}</h1>
            <p class="text-sm text-dark-400">{{ __('Enter your credentials to log in') }}</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4 text-center text-sm text-green-400" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <x-input label="Email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@example.com" />

            <x-password label="Password" name="password" required autocomplete="current-password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />

            <div class="flex items-center justify-between">
                <x-checkbox label="Remember me" id="remember_me" name="remember" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-primary-500 hover:text-primary-400 transition-colors">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-button type="submit" class="w-full">
                {{ __('Log in') }}
            </x-button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-dark-400">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-primary-500 hover:text-primary-400 transition-colors font-medium">
                        {{ __('Sign up') }}
                    </a>
                </p>
            @endif
        </form>
    </div>
</x-guest-layout>
