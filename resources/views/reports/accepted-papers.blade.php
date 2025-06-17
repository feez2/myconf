<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Accepted Papers: {{ $conference->title }}</h2>
                    <div>
                        <a href="{{ route('reports.download.accepted-papers', $conference) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Download as PDF
                        </a>
                        <a href="{{ route('reports.index', $conference) }}" class="btn btn-outline-secondary">
                            Back to Reports
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if($papers->isEmpty())
                            <div class="alert alert-info">
                                No accepted papers for this conference.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Authors</th>
                                            <th>Approved for Proceedings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($papers as $paper)
                                            <tr>
                                                <td>{{ $paper->title }}</td>
                                                <td>
                                                    @foreach($paper->authors as $author)
                                                        {{ $author->name }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($paper->approved_for_proceedings)
                                                        <span class="badge bg-success">Yes</span>
                                                    @else
                                                        <span class="badge bg-warning">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
