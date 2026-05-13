@extends('admin.layouts.adminlte')

@section('title', 'Download - Portal Kemenag')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Download</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Download</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('admin.downloads.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari judul, deskripsi..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('admin.downloads.index') }}" method="GET">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('admin.downloads.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Download
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
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Judul</th>
                            <th style="width: 100px;">Tipe</th>
                            <th style="width: 100px;">Ukuran</th>
                            <th style="width: 100px;">Diunduh</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($downloads as $key => $download)
                            <tr>
                                <td>{{ $downloads->firstItem() + $key }}</td>
                                <td>
                                    <strong>{{ $download->title }}</strong>
                                    @if($download->description)
                                        <br><small class="text-muted">{{ Str::limit($download->description, 60) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ strtoupper($download->file_type) }}</span>
                                </td>
                                <td>{{ $download->file_size_formatted }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $download->downloads_count }}x</span>
                                </td>
                                <td>
                                    @if($download->is_published)
                                        <span class="badge badge-success">Published</span>
                                    @else
                                        <span class="badge badge-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.downloads.edit', $download->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.downloads.destroy', $download->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus download ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data download.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="float-right">
                {{ $downloads->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
