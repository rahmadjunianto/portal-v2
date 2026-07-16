@extends('admin.layouts.adminlte')

@section('title', 'WhatsApp Conversations')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fab fa-whatsapp text-success"></i> WhatsApp Conversations</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">WhatsApp</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<style>
    .chat-list {
        height: 600px;
        overflow-y: auto;
    }
    .chat-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
    }
    .chat-item:hover {
        background: #f5f5f5;
    }
    .chat-item.active {
        background: #e8f5e9;
    }
    .chat-avatar {
        width: 45px;
        height: 45px;
        background: #25d366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    .chat-info {
        flex: 1;
        min-width: 0;
    }
    .chat-name {
        font-weight: 600;
        color: #333;
    }
    .chat-time {
        font-size: 11px;
        color: #888;
    }
    .chat-preview {
        font-size: 13px;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stats-card {
        border-left: 4px solid #25d366;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #888;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <!-- Stats -->
        <div class="row mb-3">
            <div class="col-6">
                <div class="card stats-card">
                    <div class="card-body p-2">
                        <h5 class="mb-0">{{ number_format($totalContacts) }}</h5>
                        <small class="text-muted">Kontak</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card stats-card">
                    <div class="card-body p-2">
                        <h5 class="mb-0">{{ number_format($totalMessages) }}</h5>
                        <small class="text-muted">Pesan</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Percakapan</h3>
                <div class="card-tools">
                    @if($conversations->count() > 0)
                    <button type="button" class="btn btn-tool" title="Hapus Semua" onclick="return confirm('Hapus semua percakapan?')">
                        <a href="{{ route('admin.whatsapp-conversations.destroyAll') }}" class="text-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @if($conversations->count() > 0)
                    <div class="chat-list">
                        @foreach($conversations as $conv)
                        <a href="{{ route('admin.whatsapp-conversations.show', $conv->phone) }}" class="chat-item text-decoration-none d-flex align-items-center">
                            <div class="chat-avatar mr-3">
                                {{ strtoupper(substr($conv->contact_name ?? $conv->phone, 0, 1)) }}
                            </div>
                            <div class="chat-info">
                                <div class="d-flex justify-content-between">
                                    <span class="chat-name">{{ $conv->contact_name ?? $conv->phone_display ?? $conv->phone }}</span>
                                    <span class="chat-time">{{ $conv->last_message_at->diffForHumans() }}</span>
                                </div>
                                <div class="chat-preview">
                                    <i class="fab fa-whatsapp text-success mr-1"></i>
                                    {{ $conv->phone_display ?? $conv->phone }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fab fa-whatsapp fa-3x mb-3"></i>
                        <p>Belum ada percakapan WhatsApp</p>
                    </div>
                @endif
            </div>
            @if($conversations->hasPages())
                <div class="card-footer">{{ $conversations->links() }}</div>
            @endif
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pilih percakapan</h3>
            </div>
            <div class="card-body text-center" style="padding: 100px 20px; color: #aaa;">
                <i class="fab fa-whatsapp fa-5x mb-3"></i>
                <p>Pilih percakapan dari daftar di sebelah kiri</p>
            </div>
        </div>
    </div>
</div>
@endsection
