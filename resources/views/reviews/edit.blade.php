@php
use App\Models\Review;
@endphp

<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Submit Review for: {{ $review->paper->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('reviews.update', $review) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="score" class="form-label">Score (1-10) *</label>
                                <input type="number" class="form-control @error('score') is-invalid @enderror"
                                       id="score" name="score" min="1" max="10"
                                       value="{{ old('score') }}" required>
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">10 = Excellent, 1 = Poor</small>
                            </div>

                            <div class="mb-3">
                                <label for="recommendation" class="form-label">Recommendation *</label>
                                <select class="form-select @error('recommendation') is-invalid @enderror"
                                        id="recommendation" name="recommendation" required>
                                    @foreach(Review::recommendationOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ old('recommendation', $review->recommendation) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('recommendation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments to Author *</label>
                                <textarea class="form-control @error('comments') is-invalid @enderror"
                                          id="comments" name="comments" rows="6" required>{{ old('comments') }}</textarea>
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Please provide constructive feedback for the author (50-2000 characters)</small>
                            </div>

                            <div class="mb-3">
                                <label for="confidential_comments" class="form-label">Confidential Comments (Optional)</label>
                                <textarea class="form-control @error('confidential_comments') is-invalid @enderror"
                                          id="confidential_comments" name="confidential_comments" rows="4">{{ old('confidential_comments') }}</textarea>
                                @error('confidential_comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('reviews.show', $review) }}" class="btn btn-secondary me-md-2">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
