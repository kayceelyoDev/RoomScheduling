<x-guest-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-4 shadow-sm border border-indigo-100">
                <i data-lucide="mail-search" class="w-8 h-8 font-bold"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Verify Email</h1>
            <p class="text-sm text-slate-500 mt-2 font-medium leading-relaxed italic">
                {{ __('Thanks for signing up! Please verify your email by clicking the link we just sent you.') }}
            </p>
        </div>
    </x-slot>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            {{ __('A new verification link has been sent to your email.') }}
        </div>
    @endif

    <div class="space-y-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3 text-base shadow-indigo-100 shadow-lg">
                <span class="font-bold tracking-wide">{{ __('Resend Verification Email') }}</span>
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </x-primary-button>
        </form>

        <div class="flex flex-col items-center gap-4">
            <p class="text-xs text-slate-400 font-medium text-center">
                Didn't receive anything? Check your spam folder or try again.
            </p>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-rose-600 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
