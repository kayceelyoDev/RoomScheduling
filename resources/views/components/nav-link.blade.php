@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 transition-all duration-150'
    : 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 border border-transparent transition-all duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5"></i>
    @endif
    <span>{{ $slot }}</span>
</a>