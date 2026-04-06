<x-app-layout>
    <x-slot name="title">Room Requests</x-slot>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <i data-lucide="inbox" class="w-6 h-6 text-indigo-600"></i>
                {{ __('Room requests') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        selectedRoom: '{{ old('room_id', '') }}',
        requestedDate: '{{ old('requested_date', $date ?? \Carbon\Carbon::today()->format('Y-m-d')) }}',
        startTime: '{{ old('start_time', '') }}',
        endTime: '{{ old('end_time', '') }}',

        fillSlot(roomId, hour) {
            this.selectedRoom = roomId;
            this.startTime = hour.toString().padStart(2, '0') + ':00';
            this.endTime = (hour + 1).toString().padStart(2, '0') + ':00';
            
            // Scroll to form for better UX
            document.getElementById('new-request-form').scrollIntoView({ behavior: 'smooth' });
        },

        timeToPx(timeStr) {
            if (!timeStr) return 0;
            const [hrs, mins] = timeStr.split(':').map(Number);
            return (hrs + (mins / 60) - 7) * 150;
        }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Calendar / Availability View -->
            <div class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <i data-lucide="calendar" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Room Availability</h3>
                            <p class="text-xs text-gray-500">Click an empty slot to pre-fill the request form.</p>
                        </div>
                    </div>
                    
                    <form method="GET" action="{{ route('userRequest.index') }}" class="flex items-center gap-2">
                        <input type="date" name="date" x-model="requestedDate"
                            @change="$el.form.submit()"
                            class="border-gray-200 rounded-lg text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="hidden sm:flex gap-1">
                            @foreach (range(0, 4) as $i)
                                @php
                                    $d = \Carbon\Carbon::today()->addDays($i);
                                    $isSelected = isset($date) && $date == $d->format('Y-m-d');
                                @endphp
                                <a href="{{ route('userRequest.index', ['date' => $d->format('Y-m-d')]) }}"
                                    class="{{ $isSelected ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-gray-100' }} px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all">
                                    {{ $d->isToday() ? 'Today' : $d->format('D d') }}
                                </a>
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full" style="width: 2600px;">
                            <div class="flex border-b border-gray-100 bg-gray-50/50 uppercase text-[10px] font-bold tracking-widest text-gray-400">
                                <div class="sticky left-0 z-20 w-[200px] bg-gray-50 border-r border-gray-100 p-4 shrink-0">
                                    Rooms / Time
                                </div>
                                @foreach (range(7, 22) as $hour)
                                    <div class="w-[150px] p-4 border-r border-gray-50 text-center shrink-0">
                                        {{ $hour > 12 ? $hour - 12 . ' PM' : ($hour == 12 ? '12 PM' : $hour . ' AM') }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="divide-y divide-gray-50">
                                @php
                                    if (!function_exists('timeToPx')) {
                                        function timeToPx($timeStr)
                                        {
                                            $parts = explode(':', $timeStr);
                                            $hrs = (int) $parts[0];
                                            $mins = (int) $parts[1];
                                            return ($hrs + $mins / 60 - 7) * 150;
                                        }
                                    }
                                @endphp

                                @foreach ($rooms as $room)
                                    <div class="flex h-20 group/row">
                                        <div class="sticky left-0 z-10 w-[200px] bg-white border-r border-gray-100 p-4 shrink-0 flex flex-col justify-center shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)] transition-colors group-hover/row:bg-indigo-50/30">
                                            <span class="font-bold text-gray-900 truncate">{{ $room->roomName }}</span>
                                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $room->roomType ?? 'Standard Room' }}</span>
                                        </div>

                                        <div class="relative flex flex-1 bg-white">
                                            @foreach (range(7, 22) as $hour)
                                                <div @click="fillSlot({{ $room->id }}, {{ $hour }})"
                                                     class="w-[150px] border-r border-gray-50/50 shrink-0 h-full cursor-pointer hover:bg-indigo-50/20 transition-all"></div>
                                            @endforeach

                                            @if (isset($schedulesByRoom[$room->id]))
                                                @foreach ($schedulesByRoom[$room->id] as $schedule)
                                                    @php
                                                        $left = timeToPx($schedule->start_time);
                                                        $width = timeToPx($schedule->end_time) - $left;
                                                    @endphp
                                                    <div class="absolute top-2 bottom-2 z-0 block pointer-events-none opacity-60"
                                                        style="left: {{ $left }}px; width: {{ $width }}px;">
                                                        <div class="h-full bg-gray-100 border border-gray-200 rounded-lg p-2 flex flex-col justify-center overflow-hidden">
                                                            <span class="text-[8px] font-bold text-gray-500">
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <!-- Selection Preview -->
                                            <template x-if="selectedRoom == {{ $room->id }} && startTime && endTime">
                                                <div class="absolute top-2 bottom-2 z-10 pointer-events-none transition-all duration-300"
                                                     :style="`left: ${timeToPx(startTime)}px; width: ${timeToPx(endTime) - timeToPx(startTime)}px;` ">
                                                    <div class="h-full bg-amber-400/20 border-2 border-dashed border-amber-500 rounded-lg flex items-center justify-center animate-pulse">
                                                        <div class="flex flex-col items-center">
                                                            <i data-lucide="plus" class="w-3 h-3 text-amber-600"></i>
                                                            <span class="text-[8px] font-black uppercase text-amber-700">Request</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1.2"><div class="w-2 h-2 rounded-full bg-gray-300"></div> Occupied</span>
                            <span class="flex items-center gap-1.2"><div class="w-2 h-2 border-2 border-dashed border-amber-500 bg-amber-100"></div> Your Selection</span>
                        </div>
                        <span class="flex items-center gap-1"><i data-lucide="mouse-pointer-2" class="w-3 h-3"></i> Click to select · Shift + Scroll to pan time</span>
                    </div>
                </div>
            </div>

            <div id="new-request-form" class="bg-white border border-gray-200 shadow-sm sm:rounded-xl overflow-hidden transition-all duration-500"
                 :class="selectedRoom ? 'ring-2 ring-indigo-500 ring-offset-2' : ''">
                <div class="p-6 bg-indigo-50 border-b border-indigo-100">
                    <h3 class="font-bold text-indigo-900 flex items-center gap-2 text-sm uppercase tracking-widest">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        {{ __('New request') }}
                    </h3>
                    <p class="text-xs text-indigo-700 mt-1">Request a room for a specific date and time. An administrator will approve or decline.</p>
                </div>
                <form method="POST" action="{{ route('userRequest.store') }}" class="p-6 space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Section</label>
                            <select name="section_id" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('Select your section…') }}</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected(old('section_id') == $section->id)>
                                        {{ $section->sectionName }} (Yr: {{ $section->year_level ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Room</label>
                            <select name="room_id" x-model="selectedRoom" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('Select a room…') }}</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->roomName }} @if($room->roomType) — {{ $room->roomType }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date</label>
                            <input type="date" name="requested_date" x-model="requestedDate" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('requested_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Start</label>
                                <input type="time" name="start_time" x-model="startTime" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">End</label>
                                <input type="time" name="end_time" x-model="endTime" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Reason (optional)</label>
                            <textarea name="reason" rows="3" placeholder="e.g. Extra lab session, review class…"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="selectedRoom = ''; startTime = ''; endTime = ''"
                            class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition"
                            x-show="selectedRoom">
                            Clear Selection
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            {{ __('Submit request') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white border border-gray-200 shadow-sm sm:rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="list" class="w-5 h-5 text-gray-500"></i>
                        {{ __('Your requests') }}
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if ($requests->isEmpty())
                        <p class="p-8 text-center text-sm text-gray-500">No requests yet.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Room</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">When</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($requests as $req)
                                    <tr class="hover:bg-gray-50/80">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $req->room->roomName ?? '—' }}</td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $req->requested_date->format('M j, Y') }}
                                            <span class="text-gray-400">·</span>
                                            {{ \Carbon\Carbon::parse($req->start_time)->format('h:i A') }}
                                            –
                                            {{ \Carbon\Carbon::parse($req->end_time)->format('h:i A') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($req->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                                            @elseif ($req->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                            @endif
                                            @if ($req->admin_remark)
                                                <p class="text-xs text-gray-500 mt-1 max-w-[200px]">{{ $req->admin_remark }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if ($req->isPending())
                                                <form method="POST" action="{{ route('userRequest.destroy', $req) }}" class="inline"
                                                    onsubmit="return confirm('Withdraw this request?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800">Withdraw</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-4 py-3 border-t border-gray-100">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
