<nav x-data="{ open: false }" class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                        <i data-lucide="calendar-range" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-bold text-slate-900 text-sm tracking-tight">RoomSched</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-1">
                    @if (auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" icon="layout-dashboard">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('manageSection.index')" :active="request()->routeIs('manageSection.*')" icon="layers">
                            {{ __('Sections') }}
                        </x-nav-link>
                        <x-nav-link :href="route('manageClassrooms.index')" :active="request()->routeIs('manageClassrooms.*')" icon="door-open">
                            {{ __('Rooms') }}
                        </x-nav-link>
                        <x-nav-link :href="route('manageSchedule.index')" :active="request()->routeIs('manageSchedule.*')" icon="calendar-days">
                            {{ __('Schedules') }}
                        </x-nav-link>
                        <x-nav-link :href="route('manageUsers.index')" :active="request()->routeIs('manageUsers.*')" icon="users">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('userRequest.admin.index')" :active="request()->routeIs('userRequest.admin.*')" icon="clipboard-check">
                            {{ __('Requests') }}
                        </x-nav-link>
                    @endif

                    @if (auth()->user()->role === 'student' || auth()->user()->role === 'teacher')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="layout-dashboard">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    @if (auth()->user()->role === 'teacher')
                        <x-nav-link :href="route('userRequest.index')" :active="request()->routeIs('userRequest.index')" icon="inbox">
                            {{ __('Room Requests') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Side: User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <!-- Role Badge -->
                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full
                    {{ auth()->user()->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : (auth()->user()->role === 'teacher' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                    {{ auth()->user()->role }}
                </span>

                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 transition px-2 py-1.5 rounded-lg hover:bg-slate-50">
                            <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold text-xs uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="font-medium max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-xs font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <i data-lucide="user-cog" class="w-4 h-4 mr-2 text-slate-400 inline-block"></i>
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i data-lucide="log-out" class="w-4 h-4 mr-2 text-slate-400 inline-block"></i>
                                {{ __('Sign Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-slate-100 bg-white">
        <div class="pt-2 pb-3 space-y-0.5 px-3">
            @if (auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manageSection.index')" :active="request()->routeIs('manageSection.*')">{{ __('Sections') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manageClassrooms.index')" :active="request()->routeIs('manageClassrooms.*')">{{ __('Rooms') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manageSchedule.index')" :active="request()->routeIs('manageSchedule.*')">{{ __('Schedules') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('manageUsers.index')" :active="request()->routeIs('manageUsers.*')">{{ __('Users') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('userRequest.admin.index')" :active="request()->routeIs('userRequest.admin.*')">{{ __('Requests') }}</x-responsive-nav-link>
            @endif
            @if (auth()->user()->role === 'student' || auth()->user()->role === 'teacher')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            @endif
            @if (auth()->user()->role === 'teacher')
                <x-responsive-nav-link :href="route('userRequest.index')" :active="request()->routeIs('userRequest.index')">{{ __('Room Requests') }}</x-responsive-nav-link>
            @endif
        </div>
        <div class="pt-3 pb-2 border-t border-slate-100 px-4">
            <div class="font-semibold text-sm text-slate-800">{{ Auth::user()->name }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-0.5">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile Settings') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Sign Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
