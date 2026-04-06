<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New User') }}
            </h2>
            
            <a href="{{ route('manageUsers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                            <i data-lucide="user-plus" class="w-5 h-5 text-indigo-600"></i>
                            User Account Details
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Fill in the information below to provision a new account for the system.</p>
                    </div>

                    <form method="POST" action="{{ route('manageUsers.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Full Name</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                       class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                       placeholder="e.g. John Doe">
                            </div>
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email Address</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                           class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                           placeholder="e.g. john@example.com">
                                </div>
                                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">System Role</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="shield" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <select name="role" id="role" required
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="" disabled selected>Assign a role...</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Standard User</option>
                                    </select>
                                </div>
                                @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="pt-4 mt-6 border-t border-gray-100">
                            <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-1.5 uppercase tracking-widest">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i> Security
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                
                                <div>
                                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-lucide="key" class="w-4 h-4 text-gray-400"></i>
                                        </div>
                                        <input type="password" name="password" id="password" required
                                               class="block w-full pl-10 py-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                               placeholder="••••••••">
                                    </div>
                                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-gray-400"></i>
                                        </div>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                               class="block w-full pl-10 py-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                               placeholder="••••••••">
                                    </div>
                                </div>

                            </div>
                            <p class="mt-2 text-[11px] text-gray-500 italic">Ensure the password is at least 8 characters long.</p>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('manageUsers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                                Create User
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>