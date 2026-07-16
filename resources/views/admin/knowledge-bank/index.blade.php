@extends('admin.layouts.adminlte')

@section('title', 'Bank Data - Chatbot AI')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-database text-primary"></i> Bank Data Chatbot</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp-conversations.index') }}">WhatsApp</a></li>
                <li class="breadcrumb-item active">Bank Data</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengetahuan</h3>
        <div class="card-tools">
            <a href="{{ route('admin.knowledge-bank.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
    </div>
    
    <div class="card-body">
        {{-- Filter --}}
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $key => $name)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="active" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.knowledge-bank.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if($entries->count() > 0)
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                    <th>Kategori</th>
                    <th>Tags</th>
                    <th width="70">Prioritas</th>
                    <th width="60">Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                <tr>
                    <td>{{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}</td>
                    <td>{{ Str::limit($entry->question, 80) }}</td>
                    <td>{{ Str::limit($entry->answer, 100) }}</td>
                    <td>
                        <span class="badge badge-info">
                            {{ $categories[$entry->category] ?? $entry->category ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @if($entry->tags)
                            @foreach(explode(',', $entry->tags) as $tag)
                                <span class="badge badge-secondary">{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $entry->priority }}</td>
                    <td>
                        @if($entry->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.knowledge-bank.edit', $entry->id) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.knowledge-bank.destroy', $entry->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-5">
            <i class="fas fa-database fa-3x text-muted mb-3"></i>
            <p>Belum ada data. <a href="{{ route('admin.knowledge-bank.create') }}">Tambah data pertama</a></p>
        </div>
        @endif
    </div>
    
    @if($entries->hasPages())
    <div class="card-footer">{{ $entries->links() }}</div>
    @endif
</div>
@endsection
