<x-guest-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Account</h1>
        <p class="text-sm text-slate-500 mt-1 font-medium italic">Join our Room Scheduling System today.</p>
    </x-slot>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-2 group">
            <x-input-label for="name" :value="__('Full Name')" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <x-text-input id="name" class="pl-10 w-full bg-slate-50/50 border-slate-200 focus:bg-white transition-all shadow-none" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2 group">
            <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <x-text-input id="email" class="pl-10 w-full bg-slate-50/50 border-slate-200 focus:bg-white transition-all shadow-none" type="email" name="email" :value="old('email')" required placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <!-- Password -->
        <div class="space-y-2 group">
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <x-text-input id="password" class="pl-10 w-full bg-slate-50/50 border-slate-200 focus:bg-white transition-all shadow-none"
                                type="password"
                                name="password"
                                required 
                                placeholder="••••••••"
                                autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2 group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <x-text-input id="password_confirmation" class="pl-10 w-full bg-slate-50/50 border-slate-200 focus:bg-white transition-all shadow-none"
                                type="password"
                                name="password_confirmation" required placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-3 text-base shadow-indigo-100 shadow-lg">
                <span class="font-bold tracking-wide">{{ __('Create Account') }}</span>
                <i data-lucide="user-plus" class="w-4 h-4"></i>
            </x-primary-button>
        </div>

        <div class="text-center pt-2">
            <p class="text-sm text-slate-500 font-medium">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline underline-offset-4 decoration-2 decoration-indigo-200">Sign In instead</a>
            </p>
        </div>
    </form>
</x-guest-layout>
