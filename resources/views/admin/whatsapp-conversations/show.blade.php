@extends('admin.layouts.adminlte')

@section('title', 'WhatsApp - ' . $contactName)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>
                <i class="fab fa-whatsapp text-success"></i> 
                {{ $contactName }}
                <small class="text-muted" style="font-size: 14px;">{{ $phone }}</small>
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp-conversations.index') }}">WhatsApp</a></li>
                <li class="breadcrumb-item active">{{ $contactName }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<style>
    .chat-container {
        height: calc(100vh - 250px);
        min-height: 500px;
        background: #e5ddd5;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c5c5c5' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        overflow-y: auto;
        padding: 20px;
    }
    .chat-bubble {
        max-width: 65%;
        padding: 10px 15px;
        border-radius: 7px;
        position: relative;
        margin-bottom: 8px;
        font-size: 14px;
        line-height: 1.5;
    }
    .chat-bubble.user {
        background: #fff;
        margin-left: auto;
        border-top-right-radius: 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .chat-bubble.assistant {
        background: #fff;
        margin-right: auto;
        border-top-left-radius: 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .chat-bubble.system {
        background: #fff3cd;
        margin: 10px auto;
        max-width: 80%;
        text-align: center;
        font-size: 12px;
        color: #856404;
    }
    .chat-time {
        font-size: 10px;
        color: #999;
        margin-top: 5px;
        text-align: right;
    }
    .chat-role {
        font-size: 10px;
        font-weight: bold;
        color: #888;
        margin-bottom: 3px;
    }
    .chat-role.user { color: #25d366; }
    .chat-role.assistant { color: #128c7e; }
    .message-date {
        text-align: center;
        margin: 15px 0;
    }
    .message-date span {
        background: rgba(255,255,255,0.9);
        padding: 5px 15px;
        border-radius: 15px;
        font-size: 12px;
        color: #666;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .chat-header {
        background: #fff;
        border-bottom: 1px solid #ddd;
        padding: 10px 15px;
        display: flex;
        align-items: center;
    }
    .chat-actions {
        margin-left: auto;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="chat-header">
                <div class="chat-avatar-small mr-3" style="width: 40px; height: 40px; background: #25d366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    {{ substr($phone, -2) }}
                </div>
                <div>
                    <strong>{{ $contactName }}</strong><br>
                    <small class="text-muted">{{ $phone }} • {{ $messages->count() }} pesan</small>
                </div>
                <div class="chat-actions">
                    <form action="{{ route('admin.whatsapp-conversations.destroy', $originalPhone) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus semua percakapan dengan kontak ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                    <a href="{{ route('admin.whatsapp-conversations.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            
            <div class="chat-container" id="chatContainer">
                @php
                    $lastDate = null;
                @endphp
                
                @foreach($messages as $msg)
                    @php
                        $msgDate = $msg->created_at->format('Y-m-d');
                        if ($lastDate !== $msgDate) {
                            $lastDate = $msgDate;
                    @endphp
                            <div class="message-date">
                                <span>{{ $msg->created_at->format('d M Y') }}</span>
                            </div>
                    @php
                        }
                    @endphp
                    
                    <div class="chat-bubble {{ $msg->role }}">
                        @if($msg->role !== 'user')
                            <div class="chat-role {{ $msg->role }}">
                                @if($msg->role === 'system')
                                    <i class="fas fa-cog"></i> System
                                @else
                                    <i class="fab fa-whatsapp"></i> Bot AI
                                @endif
                            </div>
                        @else
                            <div class="chat-role user">
                                <i class="fas fa-user"></i> {{ $msg->name ?? 'User' }}
                            </div>
                        @endif
                        
                        <div class="message-content">{!! nl2br(e($msg->content)) !!}</div>
                        
                        <div class="chat-time">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    // Auto scroll to bottom
    $(document).ready(function() {
        var chatContainer = document.getElementById('chatContainer');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
@endsection
