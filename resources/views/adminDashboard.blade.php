<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 text-indigo-600"></i>
                    Admin Dashboard
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
                {{ \Carbon\Carbon::today()->format('l, M j Y') }}
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium toast-enter">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

        <div class="space-y-8">
            
            <!-- KPI Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Rooms -->
                <div class="relative bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300">
                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Rooms</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $totalRooms }}</h3>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm border border-indigo-100 group-hover:border-indigo-600">
                                <i data-lucide="door-open" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-full opacity-50 z-0 group-hover:scale-150 transition-all duration-500"></div>
                </div>

                <!-- Total Sections -->
                <div class="relative bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300">
                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Sections</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $totalSections }}</h3>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm border border-emerald-100 group-hover:border-emerald-600">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full opacity-50 z-0 group-hover:scale-150 transition-all duration-500"></div>
                </div>

                <!-- Total Teachers -->
                <div class="relative bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300">
                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Active Teachers</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $totalTeachers }}</h3>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-xl text-amber-600 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-sm border border-amber-100 group-hover:border-amber-600">
                                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full opacity-50 z-0 group-hover:scale-150 transition-all duration-500"></div>
                </div>

                <!-- Active Schedules Today -->
                <div class="relative bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300">
                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Schedules Today</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $totalActiveSchedules }}</h3>
                            </div>
                            <div class="p-3 bg-rose-50 rounded-xl text-rose-600 group-hover:scale-110 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 shadow-sm border border-rose-100 group-hover:border-rose-600">
                                <i data-lucide="calendar-check" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-gradient-to-br from-rose-50 to-rose-100 rounded-full opacity-50 z-0 group-hover:scale-150 transition-all duration-500"></div>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider flex items-center gap-2"><i data-lucide="zap" class="w-4 h-4 text-amber-500"></i> Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('manageSchedule.create') }}" class="flex items-center gap-3 p-4 bg-white border border-transparent rounded-xl hover:border-indigo-500 hover:shadow-md transition-all shadow-sm group">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><i data-lucide="plus" class="w-5 h-5"></i></div>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors">Add Schedule</span>
                    </a>
                    <a href="{{ route('manageSchedule.index') }}" class="flex items-center gap-3 p-4 bg-white border border-transparent rounded-xl hover:border-indigo-500 hover:shadow-md transition-all shadow-sm group">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><i data-lucide="calendar" class="w-5 h-5"></i></div>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors">View Grid</span>
                    </a>
                    <a href="{{ route('manageClassrooms.index') }}" class="flex items-center gap-3 p-4 bg-white border border-transparent rounded-xl hover:border-indigo-500 hover:shadow-md transition-all shadow-sm group">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><i data-lucide="building" class="w-5 h-5"></i></div>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors">Manage Rooms</span>
                    </a>
                    <a href="{{ route('manageSection.index') }}" class="flex items-center gap-3 p-4 bg-white border border-transparent rounded-xl hover:border-indigo-500 hover:shadow-md transition-all shadow-sm group">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><i data-lucide="users-2" class="w-5 h-5"></i></div>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors">Manage Sections</span>
                    </a>
                </div>
            </div>

            <!-- Daily Schedule Grid -->
            <div>
                <div class="flex items-center justify-between mb-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2"><i data-lucide="server" class="w-4 h-4 text-indigo-500"></i> Local Schedule Activity</h3>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <input type="date" name="date" value="{{ $date ?? \Carbon\Carbon::today()->format('Y-m-d') }}" onchange="this.form.submit()" class="border-gray-300 rounded-md text-xs py-1.5 shadow-sm focus:ring-indigo-500">
                        <div class="hidden sm:flex gap-1">
                            @foreach(range(0, 4) as $i)
                                @php 
                                    $d = \Carbon\Carbon::today()->addDays($i); 
                                    $isSelected = isset($date) && $date == $d->format('Y-m-d');
                                @endphp
                                <a href="{{ route('admin.dashboard', ['date' => $d->format('Y-m-d')]) }}"
                                        class="{{ $isSelected ? 'bg-indigo-600 text-white shadow-sm ring-1 ring-offset-1 ring-indigo-500' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-transparent' }} px-3 py-1.5 rounded text-[10px] font-bold uppercase transition-all inline-block">
                                    {{ $d->format('D d') }}
                                </a>
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full" style="width: 2600px;">
                            
                            <div class="flex border-b border-gray-200 bg-gray-50 uppercase text-[10px] font-bold tracking-widest text-gray-500">
                                <div class="sticky left-0 z-20 w-[200px] bg-gray-50 border-r border-gray-200 p-4 shrink-0">
                                    Rooms / Time
                                </div>
                                @foreach(range(7, 22) as $hour)
                                    <div class="w-[150px] p-4 border-r border-gray-100 text-center shrink-0">
                                        {{ $hour > 12 ? ($hour - 12) . ' PM' : ($hour == 12 ? '12 PM' : $hour . ' AM') }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="divide-y divide-gray-100">
                                @php 
                                    function dashboardTimeToPx($timeStr) {
                                        $parts = explode(':', $timeStr);
                                        $hrs = (int)$parts[0];
                                        $mins = (int)$parts[1];
                                        return ($hrs + ($mins / 60) - 7) * 150;
                                    }
                                @endphp

                                @foreach($rooms as $room)
                                <div class="flex h-24 group hover:bg-gray-50/50 transition-colors">
                                    <div class="sticky left-0 z-10 w-[200px] bg-white border-r border-gray-200 p-4 shrink-0 flex flex-col justify-center shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)]">
                                        <span class="font-bold text-gray-900">{{ $room->room_number ?? $room->name ?? 'Room ' . $room->id }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase">{{ $room->type ?? 'Room' }}</span>
                                    </div>

                                    <div class="relative flex flex-1 bg-transparent border-b border-gray-50">
                                        @foreach(range(7, 22) as $hour)
                                            <div class="w-[150px] border-r border-gray-50 shrink-0 h-full"></div>
                                        @endforeach

                                        @if(isset($schedulesByRoom[$room->id]))
                                            @foreach($schedulesByRoom[$room->id] as $schedule)
                                                @php
                                                    $left = dashboardTimeToPx($schedule->start_time);
                                                    $width = dashboardTimeToPx($schedule->end_time) - $left;
                                                @endphp
                                                <div class="absolute top-2 bottom-2 z-0" style="left: {{ $left }}px; width: {{ $width }}px;">
                                                    <div class="h-full bg-indigo-50 border border-indigo-200 rounded-[10px] p-3 flex flex-col justify-center overflow-hidden hover:shadow-md transition-all cursor-pointer border-l-4 border-l-indigo-500">
                                                        <span class="text-[10px] font-bold text-indigo-700">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                                        <span class="text-xs font-bold text-gray-900 truncate">{{ $schedule->section->sectionName ?? 'Section ' . $schedule->section_id }} (Yr: {{ $schedule->section->year_level ?? '?' }})</span>
                                                        <span class="text-[10px] text-gray-500 truncate italic">{{ $schedule->user->name ?? 'Prof.' }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
</x-app-layout>