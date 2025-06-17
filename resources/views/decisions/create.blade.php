@php
use App\Models\Paper;
@endphp

<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Make Decision</h4>
                        <a href="{{ route('decisions.index', $paper->conference) }}" class="btn btn-secondary">
                            Back to Papers
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Paper Details</h5>
                            <p><strong>Title:</strong> {{ $paper->title }}</p>
                            <p><strong>Author:</strong> {{ $paper->author->name }}</p>
                            <p><strong>Status:</strong> {{ $paper->status }}</p>
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

                        <form action="{{ route('decisions.update', $paper) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="decision" class="form-label">Decision</label>
                                <select name="decision" id="decision" class="form-select @error('decision') is-invalid @enderror" required>
                                    <option value="">Select a decision</option>
                                    <option value="accept">Accept</option>
                                    <option value="revision">Request Revision</option>
                                    <option value="reject">Reject</option>
                                </select>
                                @error('decision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="decision_notes" class="form-label">Decision Notes</label>
                                <textarea name="decision_notes" id="decision_notes" rows="4" 
                                    class="form-control @error('decision_notes') is-invalid @enderror" 
                                    required>{{ old('decision_notes') }}</textarea>
                                @error('decision_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="camera-ready-section" style="display: none;">
                                <label for="camera_ready_deadline" class="form-label">Camera-Ready Deadline</label>
                                <input type="date" name="camera_ready_deadline" id="camera_ready_deadline" 
                                    class="form-control @error('camera_ready_deadline') is-invalid @enderror">
                                @error('camera_ready_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Decision</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('decision').addEventListener('change', function() {
            const cameraReadySection = document.getElementById('camera-ready-section');
            if (this.value === 'accept') {
                cameraReadySection.style.display = 'block';
                document.getElementById('camera_ready_deadline').required = true;
            } else {
                cameraReadySection.style.display = 'none';
                document.getElementById('camera_ready_deadline').required = false;
            }
        });
    </script>
    @endpush
</x-app-layout> 