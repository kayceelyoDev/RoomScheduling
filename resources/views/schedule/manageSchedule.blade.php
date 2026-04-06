<x-app-layout>
    <x-slot name="title">Room Schedule</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-indigo-600"></i>
                    Daily Room Schedule
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">View and manage room occupancy for any day.</p>
            </div>
            <a href={{ route('manageSchedule.create') }}
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create Schedule
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium toast-enter">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

            <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('manageSchedule.index') }}" class="flex items-center gap-2">
                        <input type="date" name="date"
                            value="{{ $date ?? \Carbon\Carbon::today()->format('Y-m-d') }}"
                            onchange="this.form.submit()"
                            class="border-slate-200 rounded-lg text-sm shadow-sm focus:ring-indigo-500">
                        <div class="hidden sm:flex gap-1">
                            @foreach (range(0, 4) as $i)
                                @php
                                    $d = \Carbon\Carbon::today()->addDays($i);
                                    $isSelected = isset($date) && $date == $d->format('Y-m-d');
                                @endphp
                                <a href="{{ route('manageSchedule.index', ['date' => $d->format('Y-m-d')]) }}"
                                    class="{{ $isSelected ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-100' }} px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all">
                                    {{ $d->isToday() ? 'Today' : $d->format('D d') }}
                                </a>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full" style="width: 2600px;">

                        <div
                            class="flex border-b border-gray-200 bg-gray-50 uppercase text-[10px] font-bold tracking-widest text-gray-500">
                            <div class="sticky left-0 z-20 w-[200px] bg-gray-50 border-r border-gray-200 p-4 shrink-0">
                                Rooms / Time
                            </div>
                            @foreach (range(7, 22) as $hour)
                                <div class="w-[150px] p-4 border-r border-gray-100 text-center shrink-0">
                                    {{ $hour > 12 ? $hour - 12 . ' PM' : ($hour == 12 ? '12 PM' : $hour . ' AM') }}
                                </div>
                            @endforeach
                        </div>

                        <div class="divide-y divide-gray-100">

                            @php
                                function timeToPx($timeStr)
                                {
                                    $parts = explode(':', $timeStr);
                                    $hrs = (int) $parts[0];
                                    $mins = (int) $parts[1];
                                    return ($hrs + $mins / 60 - 7) * 150;
                                }
                            @endphp

                            @foreach ($rooms as $room)
                                <div class="flex h-24 group">
                                    <div
                                        class="sticky left-0 z-10 w-[200px] bg-white border-r border-gray-200 p-4 shrink-0 flex flex-col justify-center shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)]">
                                        <span
                                            class="font-bold text-gray-900">{{ $room->room_number ?? ($room->name ?? 'Room ' . $room->id) }}</span>
                                        <span
                                            class="text-[10px] text-gray-400 uppercase">{{ $room->type ?? 'Room' }}</span>
                                    </div>

                                    <div class="relative flex flex-1 bg-white">
                                        @foreach (range(7, 22) as $hour)
                                            <div class="w-[150px] border-r border-gray-50 shrink-0 h-full"></div>
                                        @endforeach

                                        @if (isset($schedulesByRoom[$room->id]))
                                            @foreach ($schedulesByRoom[$room->id] as $schedule)
                                                @php
                                                    $left = timeToPx($schedule->start_time);
                                                    $width = timeToPx($schedule->end_time) - $left;
                                                @endphp

                                                <a href="{{ route('manageSchedule.edit', $schedule->id) }}"
                                                    class="absolute top-2 bottom-2 z-0 block group/card"
                                                    style="left: {{ $left }}px; width: {{ $width }}px;">
                                                    <div
                                                        class="h-full bg-indigo-50 border border-indigo-200 rounded-md p-3 flex flex-col justify-center overflow-hidden transition-all duration-200 group-hover/card:bg-indigo-100 group-hover/card:shadow-lg group-hover/card:scale-[1.02] group-hover/card:z-50 cursor-pointer border-l-4 border-l-indigo-500">
                                                        <span
                                                            class="text-[10px] font-bold text-indigo-700">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                                        <span
                                                            class="text-xs font-bold text-gray-900 truncate">{{ $schedule->section->sectionName ?? 'Section ' . $schedule->section_id }}
                                                            (Yr:
                                                            {{ $schedule->section->year_level ?? 'N/A' }})
                                                        </span>
                                                        <span
                                                            class="text-[10px] text-gray-500 truncate italic">{{ $schedule->user->name ?? 'Prof.' }}</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-6 text-[11px] text-slate-500 font-medium bg-white p-4 rounded-xl border border-slate-200">
                <span class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-indigo-500"></div> Scheduled Class
                </span>
                <span class="ml-auto flex items-center gap-1"><i data-lucide="mouse-pointer-2" class="w-3 h-3"></i>
                    Shift + Scroll to pan horizontally</span>
            </div>

        </div>
    </div>
</x-app-layout>
