<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            text-align: center;
            padding: 20px;
            max-width: 600px;
        }

        h1 {
            font-size: 72px;
            margin: 0;
            color: #ff4d4f;
        }

        p {
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.5;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 48px;
            }

            p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>404</h1>
        <p>Oops! The page you're looking for doesn't exist or has been moved.</p>
        <a href="/">Back to Home</a>
    </div>
</body>

</html>