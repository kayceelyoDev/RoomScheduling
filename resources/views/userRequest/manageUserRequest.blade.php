<x-app-layout>
    <x-slot name="title">Manage Room Requests</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <i data-lucide="clipboard-check" class="w-5 h-5 text-indigo-600"></i>
                    Room Requests
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Review and approve or reject teacher room requests.</p>
            </div>
            <span class="badge badge-amber">
                {{ $requests->where('status', 'pending')->count() }} pending
            </span>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium toast-enter">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium toast-enter">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-600"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if ($requests->isEmpty())
                <div class="py-16 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i data-lucide="inbox" class="w-10 h-10 text-slate-200"></i>
                        <p class="text-sm font-medium text-slate-500">No room requests yet.</p>
                        <p class="text-xs text-slate-400">Requests submitted by teachers will appear here.</p>
                    </div>
                </div>
            @else
                <table class="w-full rss-table">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Room · Section</th>
                            <th>Date & Time</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                            <tr class="align-top">
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-700 text-xs uppercase shrink-0">
                                            {{ substr($req->teacher->name ?? 'T', 0, 1) }}
                                        </div>
                                        <div class="font-semibold text-slate-900 text-sm">{{ $req->teacher->name ?? '—' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-800">{{ $req->room->roomName ?? '—' }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $req->section->sectionName ?? 'No section' }}</div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <div class="font-medium text-slate-800">{{ $req->requested_date->format('M j, Y') }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($req->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($req->end_time)->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="max-w-[200px]">
                                    <p class="text-xs text-slate-500 truncate" title="{{ $req->reason }}">{{ $req->reason ?: '—' }}</p>
                                </td>
                                <td>
                                    @if ($req->status === 'pending')
                                        <span class="badge badge-amber">Pending</span>
                                    @elseif ($req->status === 'approved')
                                        <span class="badge badge-green">Approved</span>
                                    @else
                                        <span class="badge badge-red">Rejected</span>
                                    @endif
                                    @if ($req->reviewer)
                                        <div class="text-[10px] text-slate-400 mt-1">by {{ $req->reviewer->name }}</div>
                                    @endif
                                    @if ($req->admin_remark)
                                        <div class="text-[10px] text-slate-500 italic mt-0.5 max-w-[140px] truncate">{{ $req->admin_remark }}</div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($req->status === 'pending')
                                        <div class="flex flex-col items-end gap-2 min-w-[180px]">
                                            <form method="POST" action="{{ route('userRequest.approve', $req) }}" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="admin_remark" placeholder="Optional note…"
                                                    class="block w-full mb-1.5 text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50">
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">
                                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                    Approve & Create Schedule
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('userRequest.reject', $req) }}" class="w-full">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="admin_remark" placeholder="Reason for rejection…"
                                                    class="block w-full mb-1.5 text-xs rounded-lg border-slate-200 focus:border-red-400 focus:ring-red-400 bg-slate-50">
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 bg-white border border-red-200 text-red-700 hover:bg-red-50 text-xs font-semibold rounded-lg transition">
                                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
