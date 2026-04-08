<x-guest-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-4 shadow-sm border border-amber-100">
                <i data-lucide="key-round" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Forgot Password?</h1>
            <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed italic">
                {{ __('No worries! Enter your email and we\'ll send you a recovery link.') }}
            </p>
        </div>
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-base shadow-indigo-100 shadow-lg">
                <span class="font-bold tracking-wide">{{ __('Send Reset Link') }}</span>
                <i data-lucide="send" class="w-4 h-4"></i>
            </x-primary-button>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 font-bold hover:text-indigo-600 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
