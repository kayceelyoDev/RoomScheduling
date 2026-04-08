<x-guest-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
        <p class="text-sm text-slate-500 mt-1 font-medium italic">Please enter your credentials to access your account.</p>
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2 group">
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <x-text-input id="email" class="pl-10 w-full bg-slate-50/50 border-slate-200 focus:bg-white transition-all shadow-none" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <!-- Password -->
        <div class="space-y-2 group">
            <div class="flex items-center justify-between px-1">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-slate-700 uppercase tracking-wider" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <x-text-input id="password" class="pl-10 w-full bg-slate-50/50 border-slate-200 focus:bg-white transition-all shadow-none"
                                type="password"
                                name="password"
                                required 
                                placeholder="••••••••"
                                autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0 transition-all cursor-pointer" name="remember">
                </div>
                <span class="ms-2 text-xs font-semibold text-slate-500 group-hover:text-slate-700 transition-colors">{{ __('Stay signed in for 30 days') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-base shadow-indigo-100 shadow-lg">
                <span class="font-bold tracking-wide">{{ __('Sign In') }}</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </x-primary-button>
        </div>

        <div class="text-center pt-2">
            <p class="text-sm text-slate-500 font-medium">
                Don't have an account yet? 
                <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline underline-offset-4 decoration-2 decoration-indigo-200">Create Account</a>
            </p>
        </div>
    </form>
</x-guest-layout>
