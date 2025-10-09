{{-- @extends('layouts.app')

@section('content')
    <h5 class="mb-3">Job: {{ $job->title }}</h5>

    <form method="POST" action="{{ route('resume.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="job_id" value="{{ $job->id }}">

        <div class="mb-3">
            <label class="form-label">Upload Resume (PDF, DOCX, or TXT)</label>
            <input type="file" name="resume" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Scan Resume</button>
    </form>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h3 class="text-center mb-4 text-success">
                <i class="bi bi-upload"></i> Upload Your Resume
            </h3>

            <div class="mb-4">
                <h5 class="fw-semibold">Job Title: <span class="text-primary">{{ $job->title }}</span></h5>
                <p class="text-muted">{{ Str::limit($job->description, 1000) }}</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('resume.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Resume (PDF, DOCX, or TXT)</label>
                    <input type="file" name="resume" class="form-control form-control-lg" required>
                </div>

                <button type="submit" class="btn btn-success w-100 btn-lg">
                    <i class="bi bi-search"></i> Scan Resume
                </button>
            </form>
        </div>
    </div>
</div>
@endsection