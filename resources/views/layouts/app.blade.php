
<!DOCTYPE html>
<html>
<head>
    <title>Support Ticket System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark px-3">

    <a class="navbar-brand text-white" href="/dashboard">
        🎫 Support System
    </a>

    <div>

        <a class="text-white me-3" href="/dashboard">Dashboard</a>
        <a class="text-white me-3" href="/tickets">Tickets</a>

        <!-- Logout -->
        <form action="/logout" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-danger">
                Logout
            </button>
        </form>

    </div>

</nav>

<div class="container mt-4">

    @yield('content')

</div>

</body>
</html>

