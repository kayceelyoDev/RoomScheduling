<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Classroom') }}: <span class="text-indigo-600">{{ $manageClassroom->roomName }}</span>
            </h2>
            
            <a href="{{ route('manageClassrooms.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <div class="mb-6 border-b border-gray-100 pb-5">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <i data-lucide="edit-3" class="w-5 h-5 text-indigo-600"></i>
                            Update Classroom Details
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Modify the information below to update the classroom's properties and status.</p>
                    </div>

                    <form method="POST" action="{{ route('manageClassrooms.update', $manageClassroom->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="roomName" class="block font-medium text-sm text-gray-700">Room Name</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input type="text" name="roomName" id="roomName" required
                                       value="{{ old('roomName', $manageClassroom->roomName) }}"
                                       class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                       placeholder="e.g. Room 101, Lab A">
                            </div>
                            @error('roomName') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            @php
                                $standardTypes = ['Lecture Room', 'Computer Lab', 'Science Lab'];
                                $isCustomType = !in_array($manageClassroom->roomType, $standardTypes);
                            @endphp
                            <div x-data="{ customType: {{ $isCustomType ? 'true' : 'false' }} }">
                                <label for="roomType" class="block font-medium text-sm text-gray-700">Room Type</label>
                                
                                <div class="relative mt-1" x-show="!customType">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="monitor" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <select x-ref="typeSelect" :name="!customType ? 'roomType' : ''" :required="!customType" @change="customType = $event.target.value === 'other'"
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach($standardTypes as $type)
                                            <option value="{{ $type }}" {{ old('roomType', $manageClassroom->roomType) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                        <option value="other" class="font-bold text-indigo-600" {{ $isCustomType ? 'selected' : '' }}>Other (Please specify)</option> 
                                    </select>
                                </div>

                                <div class="relative mt-1" x-show="customType" style="display: none;" x-cloak>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="pencil" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <input type="text" :name="customType ? 'roomType' : ''" :required="customType" 
                                           value="{{ $isCustomType ? old('roomType', $manageClassroom->roomType) : '' }}"
                                           class="block w-full pl-10 pr-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                           placeholder="Enter custom room type">
                                    
                                    <button type="button" @click="customType = false; $refs.typeSelect.value = 'Lecture Room'" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                @error('roomType') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="activity" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <select name="status" id="status" required
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="Available" {{ old('status', $manageClassroom->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Unavailable" {{ old('status', $manageClassroom->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        
                                    </select>
                                </div>
                                @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('manageClassrooms.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i data-lucide="save" class="w-4 h-4 mr-1"></i>
                                Update Classroom
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>