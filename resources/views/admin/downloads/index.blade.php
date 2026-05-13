@extends('admin.layouts.adminlte')

@section('title', 'Download - Portal Kemenag')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-download mr-2"></i>Download</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Download</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <a href="{{ route('admin.downloads.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Download
                    </a>
                </div>
                <div class="card-tools">
                    <form action="{{ route('admin.downloads.index') }}" method="GET" class="form-inline">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Cari title..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Title</th>
                            <th>File</th>
                            <th>Ukuran</th>
                            <th>Downloads</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($downloads as $key => $download)
                        <tr>
                            <td>{{ $downloads->firstItem() + $key }}</td>
                            <td>{{ $download->title }}</td>
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
                                <a href="{{ route('admin.downloads.edit', $download->id) }}" class="btn btn-info btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.downloads.destroy', $download->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data download.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $downloads->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
