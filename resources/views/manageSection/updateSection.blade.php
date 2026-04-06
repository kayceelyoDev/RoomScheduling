<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Section') }}: <span class="text-indigo-600">{{ $manageSection->sectionName }}</span>
            </h2>
            
            <a href="{{ route('manageSection.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                            Update Section Details
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Modify the information below to update the class section.</p>
                    </div>

                    <form method="POST" action="{{ route('manageSection.update', $manageSection->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="sectionName" class="block font-medium text-sm text-gray-700">Section Name</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="type" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input type="text" name="sectionName" id="sectionName" required
                                       value="{{ old('sectionName', $manageSection->sectionName) }}"
                                       class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                       placeholder="e.g. Section A">
                            </div>
                            @error('sectionName') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            @php
                                $standardYears = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                                $isCustomYear = !in_array($manageSection->year_level, $standardYears);
                            @endphp
                            <div x-data="{ customYear: {{ $isCustomYear ? 'true' : 'false' }} }">
                                <label for="year_level" class="block font-medium text-sm text-gray-700">Year Level</label>
                                
                                <div class="relative mt-1" x-show="!customYear">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="graduation-cap" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <select x-ref="yearSelect" :name="!customYear ? 'year_level' : ''" :required="!customYear" @change="customYear = $event.target.value === 'other'"
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach($standardYears as $year)
                                            <option value="{{ $year }}" {{ old('year_level', $manageSection->year_level) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                        <option value="other" class="font-bold text-indigo-600" {{ $isCustomYear ? 'selected' : '' }}>Other (Please specify)</option> 
                                    </select>
                                </div>

                                <div class="relative mt-1" x-show="customYear" style="display: none;" x-cloak>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="pencil" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <input type="text" :name="customYear ? 'year_level' : ''" :required="customYear" 
                                           value="{{ $isCustomYear ? old('year_level', $manageSection->year_level) : '' }}"
                                           class="block w-full pl-10 pr-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                           placeholder="Enter custom year level">
                                    
                                    <button type="button" @click="customYear = false; $refs.yearSelect.value = '1st Year'" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                @error('year_level') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            @php
                                $standardDepts = ['Information Technology', 'Computer Science', 'Information Systems'];
                                $isCustomDept = !in_array($manageSection->department, $standardDepts);
                            @endphp
                            <div x-data="{ customDept: {{ $isCustomDept ? 'true' : 'false' }} }">
                                <label for="department" class="block font-medium text-sm text-gray-700">Department</label>
                                
                                <div class="relative mt-1" x-show="!customDept">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="building" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <select x-ref="deptSelect" :name="!customDept ? 'department' : ''" :required="!customDept" @change="customDept = $event.target.value === 'other'"
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach($standardDepts as $dept)
                                            <option value="{{ $dept }}" {{ old('department', $manageSection->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                        @endforeach
                                        <option value="other" class="font-bold text-indigo-600" {{ $isCustomDept ? 'selected' : '' }}>Other (Please specify)</option>
                                    </select>
                                </div>

                                <div class="relative mt-1" x-show="customDept" style="display: none;" x-cloak>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="pencil" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <input type="text" :name="customDept ? 'department' : ''" :required="customDept" 
                                           value="{{ $isCustomDept ? old('department', $manageSection->department) : '' }}"
                                           class="block w-full pl-10 pr-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                           placeholder="Enter custom department">
                                           
                                    <button type="button" @click="customDept = false; $refs.deptSelect.value = 'Information Technology'" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                @error('department') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('manageSection.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i data-lucide="save" class="w-4 h-4 mr-1"></i>
                                Update Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>