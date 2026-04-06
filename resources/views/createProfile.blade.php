<x-guest-layout>
    <form action="{{ route('user-profile.store') }}" method="POST" class="max-w-sm mx-auto mt-8">
        @csrf

        <div>
            <x-input-label for="id_number" :value="__('ID Number')" />
            <x-text-input id="id_number" class="block mt-1 w-full" type="text" name="id_number" :value="old('id_number')"
                required autofocus placeholder="Enter your ID number" />
            <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
        </div>
        @if (auth()->user()->role === 'student')
        <div class="mt-4">
            <x-input-label for="sections" :value="__('Section')" />

            <select id="sections" name="section_id" required
                class="bg-white text-gray-900 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                <option value="" disabled selected>Choose a section</option>

                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" {{ old('sections') == $section->id ? 'selected' : '' }}>
                        {{ $section->sectionName }} - {{ $section->year_level }} - {{ $section->department }}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('sections')" class="mt-2" />
        </div>
        @endif
        

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Save Profile') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
