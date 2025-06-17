<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Manage Papers for Proceedings - {{ $proceedings->title }}</h4>
                        <a href="{{ route('proceedings.index', $proceedings->conference) }}" class="btn btn-secondary">
                            Back to Proceedings
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('proceedings.update-papers', $proceedings) }}" method="POST">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Include</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Camera-Ready</th>
                                            <th>Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($papers as $paper)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="papers[{{ $paper->id }}][in_proceedings]"
                                                        value="1" {{ $paper->in_proceedings ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $paper->title }}</td>
                                                <td>{{ $paper->author->name }}</td>
                                                <td>
                                                    @if($paper->camera_ready_file)
                                                        <span class="badge bg-success">Uploaded</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" name="papers[{{ $paper->id }}][proceedings_order]"
                                                        value="{{ $paper->proceedings_order }}" class="form-control form-control-sm" style="width: 70px;">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
