{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Scanner AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">🤖 Resume Scanner AI</h2>
        <div class="card shadow p-4">
            @yield('content')
        </div>
    </div>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Scanner AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #eef2f3, #8e9eab);
            min-height: 100vh;
            font-family: "Poppins", sans-serif;
        }

        .app-header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            text-align: center;
            transition: 0.3s ease;
        }

        .app-header h2 {
            font-weight: 700;
            font-size: 1.8rem;
        }

        .card {
            border: none;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Header -->
        <div class="app-header">
            <h2>Resume Scanner AI</h2>
            <p class="mb-0">Smartly match resumes with job descriptions using AI</p>
        </div>

        <!-- Main Content -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>