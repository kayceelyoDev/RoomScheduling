<x-app-layout>
    <x-slot name="title">Manage Users</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i>
                    Manage Users
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Create and manage user accounts and roles.</p>
            </div>
            <a href="{{ route('manageUsers.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Add User
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium toast-enter">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700">All Users</h3>
            <span class="text-xs text-slate-400 bg-slate-50 border border-slate-100 rounded-full px-2.5 py-1 font-medium">{{ $users->total() ?? $users->count() }} users</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full rss-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-700 text-sm uppercase shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 text-sm">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                            <i data-lucide="mail" class="w-3 h-3"></i>
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge" style="background:#f5f3ff;color:#6d28d9;">Admin</span>
                                @elseif($user->role === 'teacher')
                                    <span class="badge badge-green">Teacher</span>
                                @else
                                    <span class="badge badge-slate">Student</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('manageUsers.edit', $user->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg border border-transparent hover:border-indigo-100 transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        Edit
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('manageUsers.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    data-confirm="Remove user '{{ $user->name }}'? This cannot be undone."
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-100 transition">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                Remove
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic px-2">You</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="users" class="w-10 h-10 text-slate-200"></i>
                                    <p class="text-sm font-medium text-slate-500">No users found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($users, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>