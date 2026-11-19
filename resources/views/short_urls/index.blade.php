
<!DOCTYPE html>
<html>
<head>
    <title>Short URLs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Short URLs</h4>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
                Dashboard
            </a>
        </div>

        <div class="card-body">
            @if($shortUrls->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company</th>
                                <th>Created By</th>
                                <th>Original URL</th>
                                <th>Short URL</th>
                                <th>Created At</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($shortUrls as $key => $url)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $url->company->name ?? 'N/A' }}</td>
                                    <td>{{ $url->user->name ?? 'N/A' }}</td>
                                    <td style="max-width:250px; word-break:break-all;">
                                        {{ $url->original_url }}
                                    </td>
                                    <td>
                                        {{ url('/s/' . $url->short_code) }}
                                    </td>
                                    <td>{{ $url->created_at->format('d-m-Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No short URLs found.
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
