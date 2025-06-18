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

                <!-- Analytics Charts Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="bi bi-graph-up"></i> Analytics Dashboard</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Chart 1: Submissions by Status -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 analytics-card">
                                            <div class="card-header">
                                                <h5>Submissions by Status</h5>
                                            </div>
                                            <div class="card-body">
                                                @if(array_sum($chartData) > 0)
                                                    <div class="chart-container">
                                                        <canvas id="submissionsByStatusChart"></canvas>
                                                    </div>
                                                @else
                                                    <div class="no-data-message">
                                                        <p>No submission data available</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart 2: Submission Count Over Time -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 analytics-card">
                                            <div class="card-header">
                                                <h5>Submission Count Over Time</h5>
                                            </div>
                                            <div class="card-body">
                                                @if(!empty($submissionsOverTime))
                                                    <div class="chart-container">
                                                        <canvas id="submissionsOverTimeChart"></canvas>
                                                    </div>
                                                @else
                                                    <div class="no-data-message">
                                                        <p>No submission timeline data available</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart 3: Review Completion Progress -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 analytics-card">
                                            <div class="card-header">
                                                <h5>Review Completion Progress</h5>
                                            </div>
                                            <div class="card-body">
                                                @if(!empty($reviewCompletionData))
                                                    <div class="chart-container">
                                                        <canvas id="reviewCompletionChart"></canvas>
                                                    </div>
                                                @else
                                                    <div class="no-data-message">
                                                        <p>No review completion data available</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart 4: Acceptance vs Rejection Rates -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 analytics-card">
                                            <div class="card-header">
                                                <h5>Acceptance vs Rejection Rates</h5>
                                            </div>
                                            <div class="card-body">
                                                @if(array_sum($acceptanceData) > 0)
                                                    <div class="chart-container">
                                                        <canvas id="acceptanceRatesChart"></canvas>
                                                    </div>
                                                @else
                                                    <div class="no-data-message">
                                                        <p>No decision data available</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart 5: Submissions per Conference -->
                                    <div class="col-md-12 mb-4">
                                        <div class="card analytics-card">
                                            <div class="card-header">
                                                <h5>Submissions per Conference</h5>
                                            </div>
                                            <div class="card-body">
                                                @if(!empty($conferencesData))
                                                    <div class="chart-container horizontal">
                                                        <canvas id="conferencesChart"></canvas>
                                                    </div>
                                                @else
                                                    <div class="no-data-message">
                                                        <p>No conference data available</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="{{ route('reports.reviews', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.reviews', $conference) }}"
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
                                    <a href="{{ route('reports.proceedings', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.proceedings', $conference) }}"
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
                                    <a href="{{ route('reports.program-book', $conference) }}"
                                    class="btn btn-sm btn-info me-1 d-inline-flex align-items-center shadow-sm px-3 text-white">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('reports.download.program-book', $conference) }}"
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

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .chart-container.horizontal {
            height: 400px;
        }
        
        .no-data-message {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #6c757d;
            font-style: italic;
        }
        
        .analytics-card {
            transition: transform 0.2s ease-in-out;
        }
        
        .analytics-card:hover {
            transform: translateY(-2px);
        }
    </style>
    
    <script>
        // Chart 1: Submissions by Status (Pie Chart)
        @if(array_sum($chartData) > 0)
        try {
            const submissionsByStatusCtx = document.getElementById('submissionsByStatusChart');
            if (submissionsByStatusCtx) {
                new Chart(submissionsByStatusCtx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($chartData)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($chartData)) !!},
                            backgroundColor: [
                                '#007bff', // Submitted
                                '#ffc107', // Under Review
                                '#17a2b8', // Revision Required
                                '#28a745', // Accepted
                                '#dc3545', // Rejected
                                '#6c757d'  // Withdrawn
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : '0.0';
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error creating submissions by status chart:', error);
        }
        @endif

        // Chart 2: Submission Count Over Time (Line Chart)
        @if(!empty($submissionsOverTime))
        try {
            const submissionsOverTimeCtx = document.getElementById('submissionsOverTimeChart');
            if (submissionsOverTimeCtx) {
                new Chart(submissionsOverTimeCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_keys($submissionsOverTime)) !!},
                        datasets: [{
                            label: 'Submissions',
                            data: {!! json_encode(array_values($submissionsOverTime)) !!},
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#007bff',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error creating submissions over time chart:', error);
        }
        @endif

        // Chart 3: Review Completion Progress (Bar Chart)
        @if(!empty($reviewCompletionData))
        try {
            const reviewCompletionCtx = document.getElementById('reviewCompletionChart');
            if (reviewCompletionCtx) {
                new Chart(reviewCompletionCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_column($reviewCompletionData, 'reviewer')) !!},
                        datasets: [{
                            label: 'Assigned',
                            data: {!! json_encode(array_column($reviewCompletionData, 'assigned')) !!},
                            backgroundColor: '#6c757d',
                            borderColor: '#6c757d',
                            borderWidth: 1
                        }, {
                            label: 'Completed',
                            data: {!! json_encode(array_column($reviewCompletionData, 'completed')) !!},
                            backgroundColor: '#28a745',
                            borderColor: '#28a745',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error creating review completion chart:', error);
        }
        @endif

        // Chart 4: Acceptance vs Rejection Rates (Doughnut Chart)
        @if(array_sum($acceptanceData) > 0)
        try {
            const acceptanceRatesCtx = document.getElementById('acceptanceRatesChart');
            if (acceptanceRatesCtx) {
                new Chart(acceptanceRatesCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_keys($acceptanceData)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($acceptanceData)) !!},
                            backgroundColor: [
                                '#28a745', // Accepted
                                '#dc3545', // Rejected
                                '#ffc107'  // Revision Required
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : '0.0';
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error creating acceptance rates chart:', error);
        }
        @endif

        // Chart 5: Submissions per Conference (Horizontal Bar Chart)
        @if(!empty($conferencesData))
        try {
            const conferencesCtx = document.getElementById('conferencesChart');
            if (conferencesCtx) {
                new Chart(conferencesCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($conferencesData)) !!},
                        datasets: [{
                            label: 'Submissions',
                            data: {!! json_encode(array_values($conferencesData)) !!},
                            backgroundColor: '#17a2b8',
                            borderColor: '#17a2b8',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error creating conferences chart:', error);
        }
        @endif
    </script>
</x-app-layout>
