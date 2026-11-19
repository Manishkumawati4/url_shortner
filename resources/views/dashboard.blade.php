
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">

                <h3 class="mb-0">
                    Welcome, {{ Auth::user()->name }}
                </h3>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Logout
                    </button>
                </form>

            </div>
        </div>

        <div class="card-body">

            <h5>
                Role: {{ Auth::user()->role }}
            </h5>

            <h5>
                Company:
                {{ Auth::user()->company?->name ?? 'No Company' }}
            </h5>

            <hr>

            <h5 class="mb-3">Dashboard Menu</h5>

            {{-- SuperAdmin Menu --}}
            @if(Auth::user()->role === 'SuperAdmin')

                <a href="{{ route('companies.index') }}"
                   class="btn btn-primary mb-2">
                    Manage Companies
                </a>

            @endif

            @if(in_array(Auth::user()->role, ['SuperAdmin', 'Admin']))

                <a href="{{ route('invitations.index') }}"
                class="btn btn-info mb-2">
                    Manage Invitations
                </a>

                <a href="{{ route('invitations.create') }}"
                class="btn btn-success mb-2">
                    Invite User
                </a>

            @endif

            @if(in_array(Auth::user()->role, ['Sales', 'Manager']))
                <a href="{{ route('short-urls.create') }}" class="btn btn-primary mb-2">
                    Create Short URL
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['Admin', 'Member']))
                <a href="{{ route('short-urls.index') }}" class="btn btn-info mb-2">
                    View Short URLs
                </a>
            @endif

        </div>

        

    </div>

</div>

</body>
</html>
