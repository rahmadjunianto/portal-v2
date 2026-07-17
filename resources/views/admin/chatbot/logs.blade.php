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
        <h3 class="card-title">Daftar Percakapan</h3>
    </div>
    
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>User</th>
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
                            @if($conversation->user)
                                {{ $conversation->user->name }}
                            @else
                                <span class="text-muted">Anonim</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($conversation->first_message, 80) }}</td>
                        <td>{{ $conversation->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.chatbot.show', $conversation->id) }}" 
                               class="btn btn-info btn-xs">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada chat logs</td>
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
