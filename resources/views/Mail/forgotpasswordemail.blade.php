<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            margin-bottom: 10px;
        }

        .reset-link {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 0;
            border-radius: 4px;
            margin-top: 20px;
        }

        .reset-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        <p>Click the button below to reset your password:</p>
        <a href="{{ $resetLink }}" class="reset-link">Reset Password</a>
        <p>If you didn't request a password reset, you can ignore this message.</p>
        <p>Thank you!</p>
    </div>
</body>
</html>
