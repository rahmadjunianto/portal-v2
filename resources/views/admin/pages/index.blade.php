{{-- resources\views\admin\pages\index.blade.php --}}
{{-- Pages List with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Pages')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Pages</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Pages</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Pages</h3>
        <div class="card-tools">
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add New Page
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
        
        <table class="table table-bordered table-striped data-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Type</th>
                    <th>Published</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $key => $page)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td><code>{{ $page->slug }}</code></td>
                        <td>
                            @if($page->page_type)
                                <span class="badge badge-info">{{ $page->page_type }}</span>
                            @else
                                <span class="badge badge-secondary">Default</span>
                            @endif
                        </td>
                        <td>
                            @if($page->published_at)
                                {{ $page->published_at->format('d M Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-info btn-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No pages found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
        <div class="card-footer">
            {{ $pages->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('.data-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});
</script>
@endpush
