
<!DOCTYPE html>
<html>
<head>
    <title>Companies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h3>Companies</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">
        Add Company
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Slug</th>
                <th>Created At</th>
            </tr>
        </thead>

        <tbody>
            @forelse($companies as $company)
                <tr>
                    <td>{{ $company->id }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->slug }}</td>
                    <td>{{ $company->created_at->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No companies found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>

</div>

</body>
</html>
