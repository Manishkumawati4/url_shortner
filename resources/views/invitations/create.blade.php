
<!DOCTYPE html>
<html>
<head>
    <title>Create Invitation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Send Invitation</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('invite_url'))
                <div class="alert alert-info">
                    <strong>Invitation Link:</strong>

                    <input type="text"
                           class="form-control mt-2"
                           value="{{ session('invite_url') }}"
                           readonly>

                    <small class="text-muted">
                        Share this link with the invited user.
                    </small>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('invitations.store') }}" method="POST">
                @csrf

                @if(auth()->user()->role === 'SuperAdmin')
                    <div class="form-group">
                        <label>Select Company</label>

                        <select name="company_id" class="form-control" required>
                            <option value="">Select Company</option>

                            @foreach($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group">
                        <label>Company</label>

                        <input type="text"
                               class="form-control"
                               value="{{ auth()->user()->company?->name }}"
                               readonly>
                    </div>
                @endif

                <div class="form-group">
                    <label>Email Address</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Enter email address"
                           required>
                </div>

                
                <div class="form-group">
                    <label>Select Role</label>

                    <select name="role" class="form-control" required>
                        <option value="">Select Role</option>

                        @if(auth()->user()->role === 'SuperAdmin')
                            <option value="Member">Member</option>
                            <option value="Sales">Sales</option>
                            <option value="Manager">Manager</option>
                        @elseif(auth()->user()->role === 'Admin')
                            <option value="Sales">Sales</option>
                            <option value="Manager">Manager</option>
                        @endif
                    </select>
                </div>


                <button type="submit" class="btn btn-primary">
                    Send Invitation
                </button>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    Back
                </a>
            </form>

        </div>
    </div>

</div>

</body>
</html>
