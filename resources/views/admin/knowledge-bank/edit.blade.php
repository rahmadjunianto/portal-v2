@extends('admin.layouts.adminlte')

@section('title', 'Edit Knowledge Bank')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Edit Knowledge Bank</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge-bank.index') }}">Knowledge Bank</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Pertanyaan</h3>
    </div>
    
    <form action="{{ route('admin.knowledge-bank.update', $knowledgeBank->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="service_id">Layanan</label>
                        <select name="service_id" id="serviceSelect" class="form-control">
                            <option value="">-- Tidak terkait layanan (FAQ Umum) --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $knowledgeBank->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                    @if($service->category)
                                        ({{ $service->category->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih layanan jika pertanyaan terkait layanan tertentu</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="question">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" name="question" id="question" class="form-control" 
                               value="{{ old('question', $knowledgeBank->question) }}" required>
                    </div>
                    
                    <div class="form-group" id="answerField">
                        <label for="answer">Jawaban</label>
                        <textarea name="answer" id="answer" class="form-control" rows="4">{{ old('answer', $knowledgeBank->answer) }}</textarea>
                        <small class="text-muted">Kosongkan jika memilih layanan. Jawaban akan digenerate otomatis.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" name="tags" id="tags" class="form-control" 
                               value="{{ old('tags', $knowledgeBank->tags) }}" placeholder="Contoh: cuti, izin, surat">
                        <small class="text-muted">Pisahkan dengan koma</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <input type="number" name="priority" id="priority" class="form-control" 
                                       value="{{ old('priority', $knowledgeBank->priority) }}" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" 
                                           {{ old('is_active', $knowledgeBank->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="callout callout-info">
                        <h5><i class="icon fas fa-info"></i> Tips:</h5>
                        <ul class="mb-0">
                            <li>Jika pertanyaan terkait <strong>layanan tertentu</strong>, pilih layanan.</li>
                            <li>Jawaban akan <strong>di-generate otomatis</strong> dari data layanan.</li>
                            <li>Jika <strong>FAQ umum</strong>, kosongkan layanan dan isi jawaban manual.</li>
                            <li>Tag membantu chatbot menemukan jawaban.</li>
                            <li>Priority lebih tinggi = dicocokkan lebih dulu.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Update
            </button>
            <a href="{{ route('admin.knowledge-bank.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('serviceSelect').addEventListener('change', function() {
    const answerField = document.getElementById('answerField');
    if (this.value) {
        answerField.style.opacity = '0.5';
    } else {
        answerField.style.opacity = '1';
    }
});
document.getElementById('serviceSelect').dispatchEvent(new Event('change'));
</script>
@endpush
