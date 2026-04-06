<x-app-layout>
    <x-slot name="title">Manage Classrooms</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <i data-lucide="door-open" class="w-5 h-5 text-indigo-600"></i>
                    Manage Classrooms
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Add, edit, and remove classroom records.</p>
            </div>
            <a href="{{ route('manageClassrooms.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Classroom
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
            <h3 class="text-sm font-semibold text-slate-700">All Classrooms</h3>
            <span class="text-xs text-slate-400 bg-slate-50 border border-slate-100 rounded-full px-2.5 py-1 font-medium">{{ $classrooms->count() }} rooms</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full rss-table">
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classrooms as $classroom)
                        <tr>
                            <td>
                                <div class="font-semibold text-slate-900">{{ $classroom->roomName }}</div>
                            </td>
                            <td>
                                <span class="badge badge-indigo">{{ $classroom->roomType ?? 'Standard' }}</span>
                            </td>
                            <td>
                                @if($classroom->status === 'available')
                                    <span class="badge badge-green">Available</span>
                                @elseif($classroom->status === 'occupied')
                                    <span class="badge badge-amber">Occupied</span>
                                @else
                                    <span class="badge badge-slate">{{ $classroom->status ?? 'Unknown' }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('manageClassrooms.edit', $classroom->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg border border-transparent hover:border-indigo-100 transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('manageClassrooms.destroy', $classroom->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                data-confirm="Delete '{{ $classroom->roomName }}'? This cannot be undone."
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg border border-transparent hover:border-red-100 transition">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <i data-lucide="inbox" class="w-10 h-10 text-slate-200"></i>
                                    <p class="text-sm font-medium text-slate-500">No classrooms found.</p>
                                    <p class="text-xs">Add your first classroom to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>