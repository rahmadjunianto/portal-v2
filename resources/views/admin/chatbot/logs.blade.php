@extends('admin.layouts.adminlte')

@section('title', 'Chat Logs')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Chat Logs</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">AI Chatbot</li>
            <li class="breadcrumb-item active">Chat Logs</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Daftar Percakapan</h3>
            </div>
            <div class="col-md-6">
                <form method="GET" class="float-right">
                    <input type="hidden" name="type" value="{{ $type ?? 'all' }}">
                    <div class="input-group input-group-sm" style="width: 300px;">
                        <input type="text" name="search" class="form-control float-right" 
                               placeholder="Cari nama, email, pertanyaan..." 
                               value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ ($type ?? 'all') === 'all' ? 'active' : '' }}" 
                   href="{{ route('admin.chatbot.logs', ['type' => 'all']) }}">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($type ?? 'all') === 'chatbot' ? 'active' : '' }}" 
                   href="{{ route('admin.chatbot.logs', ['type' => 'chatbot']) }}">Chatbot</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($type ?? 'all') === 'whatsapp' ? 'active' : '' }}" 
                   href="{{ route('admin.chatbot.logs', ['type' => 'whatsapp']) }}">WhatsApp</a>
            </li>
        </ul>
    </div>
    
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th width="80">Source</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Pertanyaan</th>
                    <th>Waktu</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $index => $conversation)
                    <tr>
                        <td>{{ $conversations->firstItem() + $index }}</td>
                        <td>
                            @if($conversation->source === 'whatsapp')
                                <span class="badge badge-success">WhatsApp</span>
                            @else
                                <span class="badge badge-primary">Chatbot</span>
                            @endif
                        </td>
                        <td>{{ $conversation->name ?? '-' }}</td>
                        <td>
                            @if($conversation->phone)
                                <small>{{ $conversation->phone }}</small><br>
                            @endif
                            @if($conversation->email)
                                <small class="text-muted">{{ $conversation->email }}</small>
                            @endif
                        </td>
                        <td>{{ Str::limit($conversation->message, 80) }}</td>
                        <td>{{ $conversation->created_at ? $conversation->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.chatbot.show', $conversation->id) }}" 
                               class="btn btn-info btn-xs">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada chat logs</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        {{ $conversations->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
