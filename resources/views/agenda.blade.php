@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Daftar Agenda</h1>
        <p class="text-gray-600 text-sm">Agenda kegiatan Kementerian Agama Kabupaten Nganjuk</p>
    </div>

    <!-- Month Filter -->
    <div class="mb-6">
        <form action="{{ route('agendas.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <input type="month" name="month" value="{{ request('month') }}"
                   class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <button type="submit" class="px-3 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                Filter
            </button>
            @if(request('month'))
            <a href="{{ route('agendas.index') }}" class="px-3 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Agendas List by Year -->
    @if(isset($agendas) && $agendas->count() > 0)
    @php
        $groupedAgendas = $agendas->groupBy(function($item) {
            return $item->start_date ? $item->start_date->format('Y') : 'Unknown';
        })->sortKeysDesc();
    @endphp
    
    @foreach($groupedAgendas as $year => $yearAgendas)
        <!-- Year Header -->
        <div class="mt-8 first:mt-0 mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-sm">{{ $year }}</span>
            </h2>
        </div>
        
        <div class="space-y-3">
        @foreach($yearAgendas as $agenda)
        <article class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow border-l-4 border-emerald-600">
            <div class="flex items-start gap-3">
                <!-- Date Badge -->
                @if($agenda->start_date)
                <div class="flex-shrink-0 text-center bg-emerald-100 rounded-lg p-2 min-w-[60px]">
                    <span class="block text-xl font-bold text-emerald-700 leading-tight">{{ $agenda->start_date->format('d') }}</span>
                    <span class="text-xs text-emerald-600">{{ $agenda->start_date->format('M') }}</span>
                </div>
                @endif

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <h2 class="font-semibold text-gray-800 text-sm md:text-base mb-1 line-clamp-2 hover:text-emerald-600">
                        <a href="{{ route('agendas.show', $agenda->slug) }}">{{ $agenda->title }}</a>
                    </h2>

                    <!-- Meta Info - Desktop -->
                    <div class="hidden md:flex flex-wrap items-center gap-3 text-xs text-gray-500">
                        @if($agenda->location)
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="truncate max-w-[150px]">{{ $agenda->location }}</span>
                        </div>
                        @endif

                        @if($agenda->event_time_text)
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $agenda->event_time_text }}</span>
                        </div>
                        @endif

                        @if($agenda->sender_name)
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>{{ $agenda->sender_name }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Meta Info - Mobile (compact) -->
                    <div class="flex md:hidden items-center gap-2 text-xs text-gray-500">
                        @if($agenda->location)
                        <span class="truncate">{{ $agenda->location }}</span>
                        @endif
                        @if($agenda->event_time_text)
                        <span class="text-gray-300">|</span>
                        <span>{{ $agenda->event_time_text }}</span>
                        @endif
                    </div>
                </div>

                <!-- Action -->
                <div class="flex-shrink-0">
                    <a href="{{ route('agendas.show', $agenda->slug) }}" class="inline-flex items-center justify-center w-8 h-8 bg-emerald-600 text-white rounded-full hover:bg-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
        </div>
    @endforeach

    <!-- Pagination -->
    @if(method_exists($agendas, 'links'))
    <div class="mt-6">
        {{ $agendas->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-12 bg-white rounded-xl">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-1">Belum Ada Agenda</h3>
        <p class="text-gray-500 text-sm">Tidak ada agenda yang tersedia.</p>
    </div>
    @endif
</div>
@endsection
