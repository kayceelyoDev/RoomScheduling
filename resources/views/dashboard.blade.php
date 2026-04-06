<x-app-layout>
    <x-slot name="title">My Schedule</x-slot>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 text-indigo-600"></i>
                    My Schedule
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Your weekly class timetable.</p>
            </div>
            <div class="bg-white px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 flex items-center gap-2">
                @if(($userRole ?? auth()->user()->role) === 'teacher')
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-indigo-500"></i>
                    <span>View: <span class="text-indigo-700 font-semibold">{{ $sectionName ?? 'All Classes' }}</span></span>
                @else
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-indigo-500"></i>
                    <span>Section: <span class="text-indigo-700 font-semibold">{{ $sectionName ?? 'N/A' }}</span></span>
                    @if(!empty($departmentName))
                        <span class="text-slate-300 mx-1">·</span>
                        <span class="text-slate-500">{{ $departmentName }}</span>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
            
            <!-- Welcome Banner -->
        <div class="bg-indigo-600 rounded-xl shadow-md overflow-hidden relative mb-6">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
            <div class="p-6 relative z-10 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white mb-0.5">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-indigo-200 text-sm">
                        @if(($userRole ?? auth()->user()->role) === 'teacher')
                            Here are your classes to teach this week.
                        @else
                            Here is your class schedule for this week.
                        @endif
                    </p>
                </div>
                <div class="hidden sm:block bg-white/20 p-3 rounded-lg backdrop-blur-sm border border-white/30">
                    <i data-lucide="calendar-days" class="w-7 h-7 text-white"></i>
                </div>
            </div>
        </div>

        <!-- Weekly Grid -->
        <div class="bg-white border border-slate-200 overflow-hidden shadow-sm rounded-xl">
            <div class="p-5">
                    
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                        
                        @foreach($days as $day)
                            <div class="flex flex-col">
                                <div class="bg-gray-50 border border-gray-200 rounded-t-lg px-4 py-3 text-center mb-3">
                                    <h4 class="font-bold text-gray-700 uppercase tracking-wider text-xs">{{ $day }}</h4>
                                </div>

                                <div class="flex-1 flex flex-col gap-3">
                                    @if(isset($weeklySchedule) && $weeklySchedule->has($day))
                                        
                                        @foreach($weeklySchedule[$day] as $class)
                                            <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-3 shadow-sm hover:shadow-md transition-shadow relative group">
                                                @if(($userRole ?? auth()->user()->role) === 'teacher')
                                                    <h5 class="font-bold text-indigo-900 text-sm mb-1 leading-tight flex items-center gap-1.5">
                                                        <i data-lucide="users" class="w-4 h-4 text-indigo-600"></i>
                                                        {{ $class->section?->sectionName ?? 'Section TBA' }}
                                                    </h5>
                                                    <div class="flex items-start gap-1.5 text-xs text-gray-600 mb-1.5">
                                                        <i data-lucide="building-2" class="w-3.5 h-3.5 mt-0.5 shrink-0 text-indigo-500"></i>
                                                        <span>{{ $class->section?->department ?? '—' }}</span>
                                                    </div>
                                                    <div class="flex items-start gap-1.5 text-xs text-indigo-700 font-medium mb-1">
                                                        <i data-lucide="clock" class="w-3.5 h-3.5 mt-0.5"></i>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                                                            –
                                                            {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                                        <span>Room {{ $class->room->roomName ?? 'TBA' }}</span>
                                                    </div>
                                                @else
                                                    <h5 class="font-bold text-indigo-900 text-sm mb-1 leading-tight flex items-center gap-1.5">
                                                        <i data-lucide="users" class="w-4 h-4 text-indigo-600"></i>
                                                        {{ $class->section?->sectionName ?? 'Section TBA' }}
                                                    </h5>
                                                    <div class="flex items-start gap-1.5 text-xs text-gray-600 mb-1.5">
                                                        <i data-lucide="building-2" class="w-3.5 h-3.5 mt-0.5 shrink-0 text-indigo-500"></i>
                                                        <span>{{ $class->section?->department ?? '—' }}</span>
                                                    </div>
                                                    <div class="flex items-start gap-1.5 text-xs text-gray-800 font-medium mb-1">
                                                        <i data-lucide="user" class="w-3.5 h-3.5 mt-0.5 text-indigo-600"></i>
                                                        {{ $class->user->name ?? 'Unknown Teacher' }}
                                                    </div>
                                                    <div class="flex items-start gap-1.5 text-xs text-indigo-700 font-medium mb-1">
                                                        <i data-lucide="clock" class="w-3.5 h-3.5 mt-0.5"></i>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                                                            –
                                                            {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                                        <span>{{ $class->room->roomName ?? 'TBA' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach

                                    @else
                                        <div class="flex-1 border-2 border-dashed border-gray-100 rounded-lg flex flex-col items-center justify-center p-6 text-gray-400 min-h-[120px]">
                                            <i data-lucide="coffee" class="w-6 h-6 mb-2 opacity-50"></i>
                                            <span class="text-xs font-medium">Free Day</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>

            </div>
        </div>
    </div>
</x-app-layout>