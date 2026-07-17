@extends('admin.layouts.adminlte')

@section('title', 'Unknown Questions')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Unknown Questions</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">AI Chatbot</li>
            <li class="breadcrumb-item active">Unknown Questions</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total</h6>
                        <h3 class="mb-0">{{ $statusCounts['all'] }}</h3>
                    </div>
                    <i class="fas fa-comments" style="font-size: 32px; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Belum Diproses</h6>
                        <h3 class="mb-0">{{ $statusCounts['unprocessed'] }}</h3>
                    </div>
                    <i class="fas fa-clock" style="font-size: 32px; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Sedang Diproses</h6>
                        <h3 class="mb-0">{{ $statusCounts['processing'] }}</h3>
                    </div>
                    <i class="fas fa-spinner" style="font-size: 32px; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Sudah Ditambahkan</h6>
                        <h3 class="mb-0">{{ $statusCounts['resolved'] }}</h3>
                    </div>
                    <i class="fas fa-check-circle" style="font-size: 32px; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex">
            <a href="{{ route('admin.unknown-questions.index') }}" 
               class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm mr-1">
                Semua ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('admin.unknown-questions.index', ['status' => 'unprocessed']) }}" 
               class="btn {{ request('status') == 'unprocessed' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm mr-1">
                Belum ({{ $statusCounts['unprocessed'] }})
            </a>
            <a href="{{ route('admin.unknown-questions.index', ['status' => 'processing']) }}" 
               class="btn {{ request('status') == 'processing' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm mr-1">
                Proses ({{ $statusCounts['processing'] }})
            </a>
            <a href="{{ route('admin.unknown-questions.index', ['status' => 'resolved']) }}" 
               class="btn {{ request('status') == 'resolved' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                Selesai ({{ $statusCounts['resolved'] }})
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Pertanyaan</th>
                    <th width="100">Ditanya</th>
                    <th width="120">Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unknownQuestions as $index => $question)
                    <tr>
                        <td>{{ $unknownQuestions->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $question->question }}</strong>
                            @if($question->suggestedService)
                                <br><small class="text-success">
                                    <i class="fas fa-check mr-1"></i>
                                    Disarankan: {{ $question->suggestedService->name }}
                                </small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-secondary">{{ $question->count }}x</span>
                        </td>
                        <td>
                            @switch($question->status)
                                @case('unprocessed')
                                    <span class="badge badge-warning">Belum Diproses</span>
                                    @break
                                @case('processing')
                                    <span class="badge badge-info">Sedang Diproses</span>
                                    @break
                                @case('resolved')
                                    <span class="badge badge-success">Sudah Ditambahkan</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('admin.unknown-questions.create', $question->id) }}" 
                               class="btn btn-primary btn-xs">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                            <form action="{{ route('admin.unknown-questions.destroy', $question->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada pertanyaan yang belum terjawab</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        {{ $unknownQuestions->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
