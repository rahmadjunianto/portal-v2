@extends('admin.layouts.adminlte')

@section('title', 'Chat Conversation - ' . $conversation->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Chat Conversation</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.chat-conversations.index') }}">Chat Conversations</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Conversation</h3>
            <div class="card-tools">
                <a href="{{ route('admin.chat-conversations.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <form action="{{ route('admin.chat-conversations.destroy', $conversation->id) }}" 
                      method="POST" 
                      style="display: inline;"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus conversation ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 150px;">Nama</th>
                            <td>{{ $conversation->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $conversation->email }}</td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>{{ $conversation->subject }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $conversation->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Messages ({{ $conversation->messages->count() }})</h3>
        </div>
        
        <div class="card-body">
            @if($conversation->messages->count() > 0)
                <div class="timeline">
                    @foreach($conversation->messages as $message)
                        <div class="time-label">
                            <span class="bg-primary">
                                {{ $message->created_at->format('d M Y H:i') }}
                            </span>
                        </div>
                        <div>
                            <i class="fas fa-{{ $message->is_from_admin ? 'user-shield bg-info' : 'user bg-secondary' }}"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $message->created_at->diffForHumans() }}
                                </span>
                                <h3 class="timeline-header">
                                    <strong>{{ $message->is_from_admin ? 'Admin' : $conversation->name }}</strong>
                                </h3>
                                <div class="timeline-body">
                                    {!! nl2br(e($message->message)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada pesan dalam conversation ini.
                </div>
            @endif
        </div>
    </div>
@endsection
