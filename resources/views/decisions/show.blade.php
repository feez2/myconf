@php
use App\Models\Paper;
@endphp

<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Paper Decision Details</h4>
                        <a href="{{ route('decisions.index', $paper->conference) }}" class="btn btn-secondary">
                            Back to Papers
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Paper Details</h5>
                            <p><strong>Title:</strong> {{ $paper->title }}</p>
                            <p><strong>Author:</strong> {{ $paper->author->name }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $paper->status_color }}">
                                    {{ $paper->status }}
                                </span>
                            </p>
                            @if($paper->decision_made_at)
                                <p><strong>Decision Date:</strong> {{ $paper->decision_made_at->format('F j, Y') }}</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h5>Reviews</h5>
                            @foreach($paper->reviews as $review)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6>Reviewer: {{ $review->reviewer->name }}</h6>
                                        <p><strong>Score:</strong> {{ $review->score }}</p>
                                        <p><strong>Comments:</strong></p>
                                        <p>{{ $review->comments }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($paper->decision_notes)
                            <div class="mb-4">
                                <h5>Decision Notes</h5>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $paper->decision_notes }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($paper->camera_ready_deadline)
                            <div class="mb-4">
                                <h5>Camera-Ready Information</h5>
                                <p><strong>Deadline:</strong> {{ $paper->camera_ready_deadline->format('F j, Y') }}</p>
                                @if($paper->camera_ready_file)
                                    <p><strong>Status:</strong> Camera-ready version submitted</p>
                                @else
                                    <p><strong>Status:</strong> Camera-ready version pending</p>
                                @endif
                            </div>
                        @endif

                        @if(!in_array($paper->status, [Paper::STATUS_ACCEPTED, Paper::STATUS_REJECTED]))
                            <div class="d-grid">
                                <a href="{{ route('decisions.create', $paper) }}" class="btn btn-primary">
                                    Make Decision
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
