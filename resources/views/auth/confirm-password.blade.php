<x-guest-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-600 mb-4 shadow-sm border border-slate-100">
                <i data-lucide="shield-alert" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Confirm Access</h1>
            <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed italic">
                {{ __('This is a secure area. Please confirm your password to continue.') }}
            </p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

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
                                autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-base shadow-indigo-100 shadow-lg">
                <span class="font-bold tracking-wide">{{ __('Confirm Password') }}</span>
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
