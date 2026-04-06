<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit User') }}: <span class="text-indigo-600">{{ $user->name }}</span>
            </h2>
            
            <a href="{{ route('manageUsers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <header class="mb-6 border-b border-gray-100 pb-5">
                        <h2 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i>
                            Profile Information
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update this user's account profile information, email address, and system privileges.
                        </p>
                    </header>

                    <form method="POST" action="{{ route('manageUsers.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_type" value="profile">

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Full Name</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" required 
                                       value="{{ old('name', $user->name) }}"
                                       class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                                    <input type="email" name="email" id="email" required 
                                           value="{{ old('email', $user->email) }}"
                                           class="block w-full pl-10 py-2.5 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                                        <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Standard User</option>
                                    </select>
                                </div>
                                @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i data-lucide="save" class="w-4 h-4 mr-1.5"></i>
                                Save Profile
                            </button>
                            
                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                                    <i data-lucide="check" class="w-4 h-4"></i> Saved.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <header class="mb-6 border-b border-gray-100 pb-5">
                        <h2 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <i data-lucide="lock" class="w-5 h-5 text-indigo-600"></i>
                            Update Password
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Ensure this account is using a long, random password to stay secure. Leave blank if you do not wish to change it.
                        </p>
                    </header>

                    <form method="POST" action="{{ route('manageUsers.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="update_type" value="password">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">New Password</label>
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

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                <i data-lucide="shield-check" class="w-4 h-4 mr-1.5"></i>
                                Update Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                                    <i data-lucide="check" class="w-4 h-4"></i> Password Updated.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>