@extends('admin.layouts.adminlte')
@section('title', 'Detail Chat')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Chat</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.chatbot.logs') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="30%">ID</th>
                                    <td>{{ $conversation->id }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $conversation->user_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $conversation->user_email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $conversation->user_phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $conversation->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($conversation->is_success)
                                            <span class="badge badge-success">Berhasil</span>
                                        @else
                                            <span class="badge badge-danger">Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th width="30%">Tokens Used</th>
                                    <td>{{ $conversation->tokens_used ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>{{ $conversation->role ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>IP Address</th>
                                    <td>{{ $conversation->ip_address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>User Agent</th>
                                    <td>{{ Str::limit($conversation->user_agent ?? '-', 50) }}</td>
                                </tr>
                                @if($conversation->error_message)
                                    <tr>
                                        <th>Error</th>
                                        <td class="text-danger">{{ $conversation->error_message }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Pertanyaan User</h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $conversation->message ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Respons AI</h3>
                                </div>
                                <div class="card-body">
                                    @if($conversation->response)
                                        @php
                                            $response = json_decode($conversation->response, true);
                                        @endphp
                                        @if(is_array($response) && isset($response['answer']))
                                            <p>{!! nl2br(e($response['answer'])) !!}</p>
                                        @else
                                            <p>{!! nl2br(e($conversation->response)) !!}</p>
                                        @endif
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
