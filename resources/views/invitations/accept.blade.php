
<!DOCTYPE html>
<html>
<head>
    <title>Accept Invitation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-header">
                    <h4>Accept Invitation</h4>
                </div>

                <div class="card-body">

                    <p>
                        You have been invited to join
                        <strong>{{ $invitation->company->name }}</strong>.
                    </p>

                    <p>
                        Email: <strong>{{ $invitation->email }}</strong><br>
                        Role: <strong>{{ $invitation->role }}</strong>
                    </p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('invitations.accept.submit', $invitation->token) }}"
                          method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Name</label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            Accept Invitation & Create Account
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
