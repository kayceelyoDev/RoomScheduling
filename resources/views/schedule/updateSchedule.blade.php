<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <i data-lucide="edit" class="w-5 h-5 text-indigo-600"></i>
                {{ __('Update Schedule') }}
            </h2>
            <a href="{{ route('manageSchedule.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 shadow-sm transition ease-in-out duration-150">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{
        search: '',
        selectedDate: '{{ $manageSchedule->date }}',
        selectedRoom: '{{ $manageSchedule->room_id }}',
        startTime: '{{ \Carbon\Carbon::parse($manageSchedule->start_time)->format('H:i') }}',
        endTime: '{{ \Carbon\Carbon::parse($manageSchedule->end_time)->format('H:i') }}',
        isRecurring: {{ $manageSchedule->is_recurring ? 'true' : 'false' }},
        repeatType: '{{ $manageSchedule->repeat_type ?? 'custom' }}',
        repeatDays: {{ json_encode($manageSchedule->repeat_days ?? []) }},
        repeatUntil: '{{ $manageSchedule->repeat_until }}',
    
        setRepeatPreset(type) {
            this.repeatType = type;
            if (type === 'daily') this.repeatDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            else if (type === 'mwf') this.repeatDays = ['Monday', 'Wednesday', 'Friday'];
            else if (type === 'tth') this.repeatDays = ['Tuesday', 'Thursday'];
            else if (type === 'custom') this.repeatDays = [];
        },
    
        shouldShowPreview() {
            if (!this.selectedRoom || !this.startTime || !this.endTime) return false;
            if (!this.isRecurring) return true;
            const dateObj = new Date(this.selectedDate);
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return this.repeatDays.includes(days[dateObj.getDay()]);
        },
    
        timeToPx(timeStr) {
            if (!timeStr) return 0;
            const [hrs, mins] = timeStr.split(':').map(Number);
            return (hrs + (mins / 60) - 7) * 150;
        },
    
        fillForm(roomId, hour) {
            this.selectedRoom = roomId;
            this.startTime = hour.toString().padStart(2, '0') + ':00';
            this.endTime = (hour + 1).toString().padStart(2, '0') + ':00';
        }
    }">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start max-w-7xl mx-auto">

            <div class="lg:col-span-4 order-2 lg:order-1">
                <div class="sticky top-6 z-[60]">
                    <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">

                        <div class="p-6 bg-indigo-50 border-b border-indigo-100 flex justify-between items-center">
                            <h3
                                class="font-bold text-indigo-900 flex items-center gap-2 uppercase text-xs tracking-widest">
                                <i data-lucide="edit-3" class="w-4 h-4 text-indigo-600"></i>
                                Edit Schedule
                            </h3>
                            <span
                                class="text-xs font-bold bg-white text-indigo-600 px-2 py-1 rounded shadow-sm border border-indigo-100">ID:
                                {{ $manageSchedule->id }}</span>
                        </div>

                        <form method="POST" action="{{ route('manageSchedule.update', $manageSchedule->id) }}"
                            class="p-6 space-y-6">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())
                                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                                    <p class="text-sm text-red-700 font-bold">Please fix the errors below.</p>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Assign
                                        Teacher</label>
                                    <select name="user_id" required
                                        class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                                        <option value="">Select Teacher...</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                {{ $manageSchedule->user_id == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Target
                                            Room</label>
                                        <select name="room_id" x-model="selectedRoom" required
                                            class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                                            <option value="">Select Room...</option>
                                            @foreach ($rooms as $room)
                                                <option value="{{ $room->id }}">
                                                    {{ $room->room_number ?? ($room->name ?? 'Room ' . $room->id) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Section</label>
                                        <select name="section_id" required
                                            class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                                            <option value="">Select Section...</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                    {{ $manageSchedule->section_id == $section->id ? 'selected' : '' }}>
                                                    {{ $section->sectionName ?? 'Section ' . $section->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Date</label>
                                    <input type="date" name="date" x-model="selectedDate" required
                                        class="block w-full border-gray-300 rounded-md text-sm bg-white">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="time" name="start_time" x-model="startTime" required
                                        class="block w-full border-gray-300 rounded-md text-sm bg-white">
                                    <input type="time" name="end_time" x-model="endTime" required
                                        class="block w-full border-gray-300 rounded-md text-sm bg-white">
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 space-y-4">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-xs font-bold text-gray-700">Repeat Schedule?</span>
                                    <input type="checkbox" name="is_recurring" x-model="isRecurring"
                                        class="rounded text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                                </label>

                                <div x-show="isRecurring" x-transition
                                    class="space-y-4 p-4 bg-indigo-50/30 border border-indigo-100 rounded-lg">
                                    <div>
                                        <label
                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block mb-2">Pattern</label>
                                        <div class="grid grid-cols-4 gap-1">
                                            @foreach (['daily' => 'Daily', 'mwf' => 'MWF', 'tth' => 'TTh', 'custom' => 'Custom'] as $key => $label)
                                                <button type="button" @click="setRepeatPreset('{{ $key }}')"
                                                    :class="repeatType === '{{ $key }}' ?
                                                        'bg-indigo-600 text-white shadow-sm' :
                                                        'bg-white text-gray-400 border border-gray-200'"
                                                    class="py-1.5 text-[9px] font-bold rounded transition-all">
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div x-show="repeatType !== 'daily'" class="flex justify-between gap-1">
                                        @foreach (['Monday' => 'M', 'Tuesday' => 'T', 'Wednesday' => 'W', 'Thursday' => 'Th', 'Friday' => 'F', 'Saturday' => 'S'] as $full => $short)
                                            <label class="cursor-pointer flex-1">
                                                <input type="checkbox" name="repeat_days[]" value="{{ $full }}"
                                                    x-model="repeatDays" class="sr-only peer">
                                                <div
                                                    class="h-8 flex items-center justify-center rounded border border-gray-200 bg-white text-[10px] font-bold text-gray-300 peer-checked:bg-indigo-600 peer-checked:text-white transition-all">
                                                    {{ $short }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <input type="date" name="repeat_until" x-model="repeatUntil"
                                        :required="isRecurring" class="block w-full border-gray-300 rounded-md text-sm"
                                        placeholder="Until When?">
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-md font-semibold text-xs uppercase tracking-widest transition shadow-md active:scale-95">
                                <i data-lucide="save" class="w-4 h-4 inline-block mr-1"></i> Save Changes
                            </button>
                        </form>
                        <form method="POST" action="{{ route('manageSchedule.destroy', $manageSchedule->id) }}"
                            class="px-6 pb-6"
                            onsubmit="return confirm('Are you sure you want to permanently delete this schedule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-white hover:bg-red-50 text-red-600 border border-red-200 py-3 rounded-md font-semibold text-xs uppercase tracking-widest transition shadow-sm active:scale-95">
                                <i data-lucide="trash-2" class="w-4 h-4 inline-block mr-1"></i> Delete Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 order-1 lg:order-2 space-y-6 opacity-60 hover:opacity-100 transition-opacity duration-300"
                title="This timeline is just a preview!">
                <div
                    class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest"><i data-lucide="eye"
                            class="w-4 h-4 inline mr-1"></i> Conflict Preview</span>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
                        <input type="text" x-model="search" placeholder="Search Room..."
                            class="pl-9 border-gray-300 rounded-md text-sm w-48 focus:w-64 transition-all">
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full" style="width: 2600px;">

                            <div
                                class="flex border-b border-gray-200 bg-gray-50 uppercase text-[10px] font-bold tracking-widest text-gray-500">
                                <div
                                    class="sticky left-0 z-30 w-[200px] bg-gray-50 border-r border-gray-200 p-4 shrink-0 shadow-md">
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
                                        if (!$timeStr) {
                                            return 0;
                                        }
                                        $parts = explode(':', $timeStr);
                                        $hrs = (int) $parts[0];
                                        $mins = (int) $parts[1];
                                        return ($hrs + $mins / 60 - 7) * 150;
                                    }
                                @endphp

                                @foreach ($rooms as $room)
                                    <div class="flex h-24 group/row"
                                        x-show="'{{ $room->room_number ?? ($room->name ?? 'Room ' . $room->id) }}'.toLowerCase().includes(search.toLowerCase())">
                                        <div
                                            class="sticky left-0 z-20 w-[200px] bg-white border-r border-gray-200 p-4 shrink-0 flex flex-col justify-center shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)] transition-colors group-hover/row:bg-indigo-50">
                                            <span
                                                class="font-bold text-gray-900 tracking-tight">{{ $room->room_number ?? ($room->name ?? 'Room ' . $room->id) }}</span>
                                        </div>

                                        <div class="relative flex flex-1 bg-white">
                                            @foreach (range(7, 22) as $hour)
                                                <div @click="fillForm({{ $room->id }}, {{ $hour }})"
                                                    class="w-[150px] border-r border-gray-50 shrink-0 h-full cursor-pointer hover:bg-indigo-50/20 transition-all">
                                                </div>
                                            @endforeach

                                            @if (isset($schedulesByRoom[$room->id]))
                                                @foreach ($schedulesByRoom[$room->id] as $schedule)
                                                    @php
                                                        $left = timeToPx($schedule->start_time);
                                                        $width = timeToPx($schedule->end_time) - $left;
                                                        // Make the current schedule we are editing look different so we know which one is ours!
                                                        $isCurrentEdit = $schedule->id == $manageSchedule->id;
                                                    @endphp
                                                    <div class="absolute top-2 bottom-2 pointer-events-none {{ $isCurrentEdit ? 'z-0 opacity-20' : 'z-0 opacity-60' }}"
                                                        style="left: {{ $left }}px; width: {{ $width }}px;">
                                                        <div
                                                            class="h-full bg-slate-100 border border-slate-300 rounded-md p-1 flex flex-col items-center justify-center overflow-hidden">
                                                            <span
                                                                class="text-[9px] font-bold text-slate-500 text-center truncate w-full">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:iA') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:iA') }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <template
                                                x-if="selectedRoom == {{ $room->id }} && shouldShowPreview()">
                                                <div class="absolute top-2 bottom-2 z-10 pointer-events-none transition-all duration-300"
                                                    :style="`left: ${timeToPx(startTime)}px; width: ${timeToPx(endTime) - timeToPx(startTime)}px;`">
                                                    <div
                                                        class="h-full bg-indigo-500/20 border-2 border-dashed border-indigo-600 rounded-lg flex items-center justify-center animate-pulse shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                                                        <div class="flex flex-col items-center">
                                                            <i data-lucide="edit-2"
                                                                class="w-4 h-4 text-indigo-700 mb-1"></i>
                                                            <span
                                                                class="text-[8px] font-black uppercase text-indigo-800">Your
                                                                Edit</span>
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
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
