@extends('admin.layouts.adminlte')

@section('title', 'Knowledge Bank')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Knowledge Bank</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">AI Chatbot</li>
            <li class="breadcrumb-item active">Knowledge Bank</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-10">
                <form action="{{ route('admin.knowledge-bank.index') }}" method="GET" class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari pertanyaan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="service" class="form-control">
                            <option value="">Semua Layanan</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ route('admin.knowledge-bank.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>
            </div>
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
                    <th>Layanan</th>
                    <th>Tags</th>
                    <th width="80">Priority</th>
                    <th width="80">Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $index => $entry)
                    <tr>
                        <td>{{ $entries->firstItem() + $index }}</td>
                        <td><strong>{{ $entry->question }}</strong></td>
                        <td>
                            @if($entry->service)
                                <span class="badge badge-primary">{{ $entry->service->name }}</span>
                            @else
                                <span class="badge badge-secondary">FAQ Umum</span>
                            @endif
                        </td>
                        <td>
                            @if($entry->tags)
                                @foreach(explode(',', $entry->tags) as $tag)
                                    <span class="badge badge-info">{{ trim($tag) }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
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
                            <a href="{{ route('admin.knowledge-bank.edit', $entry->id) }}" class="btn btn-info btn-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.knowledge-bank.destroy', $entry->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        {{ $entries->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
