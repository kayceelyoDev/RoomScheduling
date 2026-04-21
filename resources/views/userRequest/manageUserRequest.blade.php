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

    <div x-data="{
        openModal: false,
        action: '',
        requestId: null,
        submitUrl: '',
        
        open(action, id, url) {
            this.action = action;
            this.requestId = id;
            this.submitUrl = url;
            this.openModal = true;
        }
    }">
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
                                        <div class="flex flex-col sm:flex-row items-end justify-end gap-2 min-w-[180px]">
                                            <button @click="open('approve', {{ $req->id }}, '{{ route('userRequest.approve', $req) }}')" type="button"
                                                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 py-1.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                Approve
                                            </button>
                                            <button @click="open('reject', {{ $req->id }}, '{{ route('userRequest.reject', $req) }}')" type="button"
                                                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 py-1.5 px-3 bg-white border border-red-200 text-red-700 hover:bg-red-50 text-xs font-semibold rounded-lg transition shadow-sm">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                Reject
                                            </button>
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

        <!-- Action Modal -->
        <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;" x-cloak>
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="openModal = false"></div>

            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200 p-6 m-4 transform transition-all"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                 
                 <div class="flex items-start gap-4 mb-5">
                     <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                          :class="action === 'approve' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
                          <div x-show="action === 'approve'">
                              <i data-lucide="check" class="w-5 h-5"></i>
                          </div>
                          <div x-show="action === 'reject'">
                              <i data-lucide="x" class="w-5 h-5"></i>
                          </div>
                     </div>
                     <div class="pt-1">
                        <h3 class="text-lg font-bold text-slate-900" x-text="action === 'approve' ? 'Approve Request' : 'Reject Request'"></h3>
                        <p class="text-sm text-slate-500 mt-1" x-text="action === 'approve' ? 'Are you sure you want to approve this room request and automate schedule creation?' : 'Are you sure you want to reject this request?'"></p>
                     </div>
                 </div>

                 <form :action="submitUrl" method="POST">
                     @csrf
                     @method('PATCH')
                     
                     <div class="mb-5">
                         <label class="block text-sm font-medium text-slate-700 mb-1.5" x-text="action === 'approve' ? 'Optional Note' : 'Reason for Rejection'"></label>
                         <textarea name="admin_remark" rows="3" 
                             class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm placeholder-slate-400"
                             :placeholder="action === 'approve' ? 'Add an optional note (e.g. Please ensure room is clean after use)...' : 'Briefly explain why this request cannot be approved...'"></textarea>
                     </div>

                     <div class="flex justify-end gap-3 mt-6">
                         <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                             Cancel
                         </button>
                         <button type="submit" 
                             class="px-4 py-2 text-sm font-semibold text-white rounded-xl transition inline-flex items-center gap-2"
                             :class="action === 'approve' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'">
                             <span x-text="action === 'approve' ? 'Approve & Create Schedule' : 'Reject Request'"></span>
                         </button>
                     </div>
                 </form>
            </div>
        </div>
    </div>
</x-app-layout>
