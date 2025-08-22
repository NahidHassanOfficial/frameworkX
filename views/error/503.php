<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - 503 Service Unavailable</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            text-align: center;
        }

        .container {
            padding: 20px;
            max-width: 600px;
        }

        h1 {
            font-size: 48px;
            margin: 0;
            color: #e67e22;
        }

        p {
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.5;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #ccc;
            border-top: 4px solid #e67e22;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px 0;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
                font-size: 36px;
            }

            p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>503 - Service Unavailable</h1>
        <div class="spinner"></div>
        <p>We're undergoing scheduled maintenance. The site will be back online soon. Thank you for your patience!</p>
        <a href="javascript:window.location.reload()">Retry</a>
    </div>
</body>

</html>