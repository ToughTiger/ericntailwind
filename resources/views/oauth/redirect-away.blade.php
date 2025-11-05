<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="0; url={{ $url }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to LinkedIn...</title>

    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: #f8fafc;
            color: #1e293b;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 36px 48px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,.06);
        }
        a {
            color: #0a66c2;
            text-decoration: none;
            font-weight: 600;
        }
        .spinner {
            border: 4px solid #e5e7eb;
            border-top: 4px solid #0a66c2;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            margin: 18px auto;
            animation: spin 0.9s linear infinite;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        // Top level navigation (cannot be intercepted)
        window.location.replace(@json($url));
    </script>
</head>

<body>
    <div class="container">
        <div class="spinner"></div>
        <h2>Redirecting to LinkedIn...</h2>
        <p>If you are not redirected automatically, <a href="{{ $url }}">click here</a>.</p>
    </div>
</body>
</html>
