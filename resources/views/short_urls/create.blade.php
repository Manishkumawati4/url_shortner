
<!DOCTYPE html>
<html>
<head>
    <title>Create Short URL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Create Short URL</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('short_url'))
                <div class="form-group">
                    <label>Your Short URL</label>
                    <div class="input-group">
                        <input type="text"
                               id="shortUrl"
                               class="form-control"
                               value="{{ session('short_url') }}"
                               readonly>

                        <div class="input-group-append">
                            <button type="button"
                                    class="btn btn-success"
                                    onclick="copyShortUrl()">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('short-urls.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Original URL</label>
                    <input type="url"
                           name="original_url"
                           class="form-control"
                           placeholder="https://example.com"
                           value="{{ old('original_url') }}"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Generate Short URL
                </button>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    Back to Dashboard
                </a>
            </form>

        </div>
    </div>
</div>

<script>
    function copyShortUrl() {
        const input = document.getElementById('shortUrl');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);
    }
</script>

</body>
</html>
