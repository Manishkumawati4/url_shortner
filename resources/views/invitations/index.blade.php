
<!DOCTYPE html>
<html>
<head>
    <title>Invitations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-3">
        <h3>Invitations</h3>

        <a href="{{ route('invitations.create') }}" class="btn btn-primary">
            Create Invitation
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Invited By</th>
                        <th>Expiry</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($invitations as $invitation)
                        <tr>
                            <td>{{ $invitation->id }}</td>
                            <td>{{ $invitation->company?->name }}</td>
                            <td>{{ $invitation->email }}</td>
                            <td>{{ $invitation->role }}</td>
                            <td>{{ $invitation->inviter?->name }}</td>
                            <td>{{ $invitation->expires_at->format('d-m-Y H:i') }}</td>
                            <td>
                                @if($invitation->accepted_at)
                                    <span class="badge badge-success">Accepted</span>
                                @elseif($invitation->expires_at->isPast())
                                    <span class="badge badge-danger">Expired</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No invitations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Back to Dashboard
            </a>

        </div>
    </div>

</div>

</body>
</html>
