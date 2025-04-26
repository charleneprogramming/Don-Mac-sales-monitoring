<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Add custom styles -->
    @yield('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Conditionally Include Sidebar -->
            @if (auth()->check() && !request()->is('admin/login')) {{-- Show navbar only if user is authenticated and not on admin-login --}}
                @include('Components.NavBar.navbar')
            @endif

            <!-- Main Content -->
            <div class="{{ auth()->check() && !request()->is('admin/login') ? 'col-md-10 offset-md-2' : 'col-md-12' }}">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
