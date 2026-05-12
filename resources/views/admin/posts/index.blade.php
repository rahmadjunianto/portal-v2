{{-- resources\views\admin\posts\index.blade.php --}}
{{-- Posts List with AdminLTE --}}

@extends('admin.layouts.adminlte')

@section('title', 'Posts')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Posts</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Posts</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Posts</h3>
        <div class="card-tools">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add New Post
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
                    <th>Category</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $key => $post)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            @if($post->is_headline)
                                <span class="badge badge-danger">Headline</span>
                            @endif
                            @if($post->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @endif
                        </td>
                        <td>{{ $post->category->name ?? '-' }}</td>
                        <td>
                            @if($post->type === 'berita')
                                <span class="badge badge-primary">Berita</span>
                            @elseif($post->type === 'pengumuman')
                                <span class="badge badge-info">Pengumuman</span>
                            @elseif($post->type === 'lowongan')
                                <span class="badge badge-success">Lowongan</span>
                            @endif
                        </td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $post->author->name ?? 'Unknown' }}</td>
                        <td>
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-info btn-xs">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
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
                        <td colspan="7" class="text-center">No posts found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        {{ $posts->links() }}
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
