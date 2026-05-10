{{-- resources/views/components/card-agenda.blade.php --}}
{{-- Reusable Agenda Card Component --}}

@props([
    'agenda'
])

<article class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-shadow border border-gray-100 group">
    {{-- Date Badge --}}
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-emerald-600 text-white rounded-lg p-3 text-center min-w-[60px]">
            <div class="text-2xl font-bold">{{ $agenda->start_date ? $agenda->start_date->format('d') : '-' }}</div>
            <div class="text-xs uppercase">{{ $agenda->start_date ? $agenda->start_date->format('M') : '' }}</div>
        </div>
        @if($agenda->end_date && $agenda->end_date != $agenda->start_date)
        <span class="text-gray-400">-</span>
        <div class="bg-gray-400 text-white rounded-lg p-3 text-center min-w-[60px]">
            <div class="text-2xl font-bold">{{ $agenda->end_date->format('d') }}</div>
            <div class="text-xs uppercase">{{ $agenda->end_date->format('M') }}</div>
        </div>
        @endif
    </div>

    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
        <a href="{{ route('agendas.show', $agenda->slug) }}">{{ $agenda->title }}</a>
    </h3>

    @if($agenda->location)
    <p class="text-gray-600 text-sm flex items-start gap-2">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        {{ $agenda->location }}
    </p>
    @endif

    @if($agenda->event_time_text)
    <p class="text-gray-500 text-sm mt-2 flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ $agenda->event_time_text }}
    </p>
    @endif
</article>
