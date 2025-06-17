<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Reports: {{ $conference->title }}</h2>
                    <div>
                        <a href="{{ route('reports.download.full-report', $conference) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download Full Report
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Conference Details -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Conference Details</h4>
                                <div>
                                    <a href="{{ route('reports.conference-details', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.conference-details', $conference) }}"
                                    class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">View comprehensive details about the conference including basic information, committees, important dates, and statistics.</p>
                            </div>
                        </div>
                    </div>


                    <!-- Review and Decisions -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Review and Decisions</h4>
                                <div>
                                    <a href="{{ route('reports.conference-details', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.conference-details', $conference) }}"
                                    class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Detailed report of paper reviews, decisions, and review statistics including completion rates and reviewer performance.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Proceedings -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Proceedings</h4>
                                <div>
                                    <a href="{{ route('reports.conference-details', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.conference-details', $conference) }}"
                                    class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Information about the conference proceedings including published papers, ISBN/ISSN details, and publication statistics.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Program Book -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Program Book</h4>
                                <div>
                                    <a href="{{ route('reports.conference-details', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.conference-details', $conference) }}"
                                    class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Complete program schedule including sessions, presentations, and speaker information.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Statistics -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Submission Statistics</h4>
                                <div>
                                    <a href="{{ route('reports.download.conference-details', $conference) }}"
                                    class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <h5 class="text-primary">{{ $stats['submission']['total'] }}</h5>
                                        <p class="text-muted mb-0">Total Submissions</p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h5 class="text-success">{{ $stats['submission']['accepted'] }}</h5>
                                        <p class="text-muted mb-0">Accepted Papers</p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-danger">{{ $stats['submission']['rejected'] }}</h5>
                                        <p class="text-muted mb-0">Rejected Papers</p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-info">{{ $stats['submission']['approved_for_proceedings'] }}</h5>
                                        <p class="text-muted mb-0">Approved for Proceedings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Statistics -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Review Statistics</h4>
                                <div>
                                    <a href="{{ route('reports.download.conference-details', $conference) }}"
                                    class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($stats['review'])
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">Total Reviews</h6>
                                            <h5 class="text-primary">{{ $stats['review']['total_reviews'] }}</h5>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">Average Score</h6>
                                            <h5 class="text-success">{{ number_format($stats['review']['average_score'], 1) }}</h5>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">Active Reviewers</h6>
                                            <h5 class="text-info">{{ $conference->reviewers()->count() }}</h5>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">Reviews per Paper</h6>
                                            <h5 class="text-warning">{{ number_format($stats['review']['total_reviews'] / $conference->papers()->count(), 1) }}</h5>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">No review data available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
