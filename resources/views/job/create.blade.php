{{-- @extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('job.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Job Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Job Description</label>
        <textarea name="description" class="form-control" rows="5" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100">Next → Upload Resume</button>
</form>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h3 class="text-center mb-4 text-primary">
                <i class="bi bi-briefcase"></i> Create Job Description
            </h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('job.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Job Title</label>
                    <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g., React.js Frontend Developer" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Job Description</label>
                    <textarea name="description" class="form-control" rows="6" placeholder="List required skills, tools, and experience..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    Next → Upload Resume
                </button>
            </form>
        </div>
    </div>
</div>
@endsection