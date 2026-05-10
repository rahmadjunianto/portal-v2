@extends('layouts.app')

@section('title', 'Riwayat Chat AI - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Chat AI Assistant</h1>
        <p class="text-gray-600">Lihat semua percakapan user dengan AI chatbot</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-500 text-sm">Total Chat</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-500 text-sm">Hari Ini</div>
            <div class="text-2xl font-bold text-emerald-600">{{ $stats['today'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-500 text-sm">Berhasil</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['success'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-500 text-sm">Gagal</div>
            <div class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-gray-500 text-sm">Total Tokens</div>
            <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_tokens'] ?? 0) }}</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('chatbot.history') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">IP Address</label>
                <input type="text" name="ip" value="{{ request('ip') }}" placeholder="Cari IP..."
                       class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Berhasil</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded text-sm hover:bg-emerald-700">
                    Filter
                </button>
                <a href="{{ route('chatbot.history') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Chat History Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pesan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balasan AI</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tokens</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($conversations as $conv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration + ($conversations->currentPage() - 1) * $conversations->perPage() }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $conv->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800">
                            {{ $conv->user_name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $conv->user_email ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $conv->user_phone ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-800 max-w-xs truncate" title="{{ $conv->message }}">
                            {{ Str::limit($conv->message, 40) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate" title="{{ $conv->response }}">
                            {{ Str::limit($conv->response, 50) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-purple-600 font-medium">
                            {{ $conv->tokens_used ? number_format($conv->tokens_used) : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($conv->is_success)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Berhasil
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Gagal
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada data chat
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $conversations->firstItem() ?? 0 }} - {{ $conversations->lastItem() ?? 0 }}
                dari {{ $conversations->total() }} data
            </div>
            <div class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($conversations->onFirstPage())
                    <span class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed">‹</span>
                @else
                    <a href="{{ $conversations->previousPageUrl() }}"
                       class="px-3 py-1 text-sm text-emerald-600 hover:bg-emerald-50 rounded">‹</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($conversations->getUrlRange(1, $conversations->lastPage()) as $page => $url)
                    @if ($page == $conversations->currentPage())
                        <span class="px-3 py-1 text-sm bg-emerald-600 text-white rounded">{{ $page }}</span>
                    @elseif ($page == 1 || $page == $conversations->lastPage() ||
                             abs($page - $conversations->currentPage()) <= 2)
                        <a href="{{ $url }}"
                           class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded">{{ $page }}</a>
                    @elseif ($page == $conversations->currentPage() - 3 || $page == $conversations->currentPage() + 3)
                        <span class="px-2 py-1 text-gray-400">...</span>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($conversations->hasMorePages())
                    <a href="{{ $conversations->nextPageUrl() }}"
                       class="px-3 py-1 text-sm text-emerald-600 hover:bg-emerald-50 rounded">›</a>
                @else
                    <span class="px-3 py-1 text-sm text-gray-400 cursor-not-allowed">›</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
