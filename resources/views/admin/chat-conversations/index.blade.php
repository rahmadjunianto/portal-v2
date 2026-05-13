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
                <span class="mr-3">
                    <i class="fas fa-comments"></i> {{ $totalConversations ?? 0 }} conversations
                    | <i class="fas fa-coins"></i> {{ number_format($totalTokens ?? 0) }} tokens
                </span>
                <form action="{{ route('admin.chat-conversations.index') }}" method="GET" class="form-inline">
                    <select name="role" class="form-control form-control-sm mr-2">
                        <option value="">-- Semua Role --</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <select name="status" class="form-control form-control-sm mr-2">
                        <option value="">-- Semua Status --</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm mr-2" 
                           placeholder="Cari..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.chat-conversations.index') }}" class="btn btn-default btn-sm ml-1" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-body">
            @if($conversations->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Pesan</th>
                            <th style="width: 80px;">Balasan</th>
                            <th style="width: 80px;">Tokens</th>
                            <th style="width: 80px;">Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversations as $index => $conversation)
                            <tr>
                                <td>{{ $conversations->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $conversation->user_name }}</strong><br>
                                    <small class="text-muted">{{ $conversation->user_email }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst($conversation->role) }}</span>
                                </td>
                                <td>
                                    <small title="{{ $conversation->message }}">{{ Str::limit($conversation->message, 40) }}</small>
                                </td>
                                <td class="text-center">
                                    @if($conversation->response)
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-minus text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{ $conversation->tokens_used ?? 0 }}</td>
                                <td class="text-center">
                                    @if($conversation->is_success)
                                        <span class="badge badge-success">OK</span>
                                    @else
                                        <span class="badge badge-danger">Err</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modal-{{ $conversation->id }}" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.chat-conversations.destroy', $conversation->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger" title="Hapus" onclick="return confirm('Hapus conversation ini?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">Belum ada chat conversations.</div>
            @endif
        </div>
        
        @if($conversations->hasPages())
            <div class="card-footer">{{ $conversations->withQueryString()->links() }}</div>
        @endif
    </div>
    
    <!-- Modals -->
    @foreach($conversations as $conversation)
    <div class="modal fade" id="modal-{{ $conversation->id }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"><i class="fas fa-comments"></i> Detail</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama:</strong> {{ $conversation->user_name }}<br>
                            <strong>Email:</strong> {{ $conversation->user_email }}<br>
                            <strong>Telepon:</strong> {{ $conversation->user_phone ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Tokens:</strong> {{ $conversation->tokens_used ?? 0 }}<br>
                            <strong>Status:</strong> 
                            @if($conversation->is_success)
                                <span class="badge badge-success">Success</span>
                            @else
                                <span class="badge badge-danger">Failed</span>
                            @endif
                            <br>
                            <strong>Tanggal:</strong> {{ $conversation->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @if($conversation->error_message)
                        <div class="alert alert-danger mt-2"><strong>Error:</strong> {{ $conversation->error_message }}</div>
                    @endif
                    <hr>
                    <p><strong><i class="fas fa-user"></i> Pesan:</strong></p>
                    <div class="bg-light p-2 rounded">{!! nl2br(e($conversation->message)) !!}</div>
                    <p class="mt-2"><strong><i class="fas fa-robot"></i> Balasan:</strong></p>
                    @if($conversation->response)
                        <div class="bg-light p-2 rounded">{!! nl2br(e($conversation->response)) !!}</div>
                    @else
                        <div class="bg-light p-2 rounded text-muted">Tidak ada balasan</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection
