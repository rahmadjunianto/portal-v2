@extends('admin.layouts.adminlte')

@section('title', 'Chat Conversations')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Chat Conversations</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Chat Conversations</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Chat Conversations</h3>
            <div class="card-tools">
                <form action="{{ route('admin.chat-conversations.index') }}" method="GET" class="input-group input-group-sm" style="width: 300px;">
                    <input type="text" name="search" class="form-control float-right" 
                           placeholder="Cari..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-body">
            @if($conversations->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversations as $index => $conversation)
                            <tr>
                                <td>{{ $conversations->firstItem() + $index }}</td>
                                <td>{{ $conversation->user_name }}</td>
                                <td>{{ $conversation->user_email }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ ucfirst($conversation->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($conversation->is_success)
                                        <span class="badge badge-success">Success</span>
                                    @else
                                        <span class="badge badge-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ $conversation->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.chat-conversations.destroy', $conversation->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus conversation ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    Belum ada chat conversations.
                </div>
            @endif
        </div>
        
        @if($conversations->hasPages())
            <div class="card-footer">
                {{ $conversations->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
