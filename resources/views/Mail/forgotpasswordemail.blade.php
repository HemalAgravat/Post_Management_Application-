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
        <h1>{{ __('labels.forgotpasswordemail.reset_password') }}</h1>
        <p>{{ __('labels.forgotpasswordemail.click_link_below') }}</p>
        <a href="{{ $data['resetLink'] }}" class="reset-link">{{ __('labels.forgotpasswordemail.reset_password') }}</a>
        <p>{{ __('labels.forgotpasswordemail.password_ignore') }}</p>
        <p>{{ __('labels.forgotpasswordemail.thank_you') }}</p>
    </div>
</body>

</html>
