{{-- @extends('layouts.app')

@section('content')
     <h4>Scan Result</h4>
     <p><strong>Job:</strong> {{ $resume->job->title ?? 'N/A' }}</p>
     <p><strong>Match Score:</strong> {{ $score ?? 0 }}/10</p>

     <hr>
     <h6>AI Feedback:</h6>
     <pre class="bg-light p-3 rounded">{{ $aiResult }}</pre>

     <a href="{{ route('job.create') }}" class="btn btn-primary mt-3">Scan Another Resume</a>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h3 class="text-center mb-4 text-info">
                <i class="bi bi-bar-chart"></i> Resume Scan Result
            </h3>

            <div class="mb-4 text-center">
                <h5><strong>{{ $job->title }}</strong></h5>
                <h6 class="text-muted">Match Score: 
                    <span class="badge bg-success fs-6">{{ number_format($score, 1) }}/10</span>
                </h6>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="bi bi-list-check"></i> Required Skills (From Job Description)</h6>
                            <p>{{ $job->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-success mb-3"><i class="bi bi-stars"></i> Matched Skills (From Resume)</h6>
                            @if(!empty($matchedSkills))
                                <ul class="list-group list-group-flush">
                                    @foreach($matchedSkills as $skill)
                                        <li class="list-group-item"><i class="bi bi-check-circle text-success"></i> {{ $skill }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No matched skills detected.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($missingSkills))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-danger mb-3"><i class="bi bi-exclamation-circle"></i> Skills Missing or Need Improvement</h6>
                    <ul class="list-group list-group-flush">
                        @foreach($missingSkills as $skill)
                            <li class="list-group-item"><i class="bi bi-x-circle text-danger"></i> {{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm d-none">
                <div class="card-body">
                    <h6 class="text-secondary"><i class="bi bi-chat-text"></i> AI Detailed Feedback</h6>
                    <div class="bg-light p-3 rounded border mt-2" style="white-space: pre-wrap; line-height:1.6;">
                        {!! nl2br(e($aiResult)) !!}
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('job.create') }}" class="btn btn-primary btn-lg me-2">
                    <i class="bi bi-arrow-repeat"></i> Scan Another Resume
                </a>
            </div>
        </div>
    </div>
</div>
@endsection