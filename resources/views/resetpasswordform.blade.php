<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    .form-group input {
        width: calc(100% - 20px);
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .form-group input:focus {
        border-color: #007BFF;
    }

    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007BFF;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .error-message {
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #f5c6cb;
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 5px;
        display: none;
    }
</style>

<body>
    <div class="container">
        <h2>{{ __('labels.resetpasswordform.reset_password') }}</h2>
        <form id="resetPasswordForm" action="{{ route('password.reset') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="token" id="token" value="{{ $data['token'] }}">
            <input type="hidden" name="uuid" id="uuid" value="{{ $data['uuid'] }}">

            <div class="form-group">
                <label for="password">{{ __('labels.resetpasswordform.new_password') }}</label>
                <input type="password" id="password" name="password" placeholder="Enter your new password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{ __('labels.resetpasswordform.confirm_new_password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm your new password">
            </div>
            <div id="responseMessage"></div>
            <br>

            <button type="submit">{{ __('labels.resetpasswordform.reset_password_button') }}</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('resetPasswordForm').addEventListener('submit', async function(event) {
                event.preventDefault();
                const password = document.getElementById('password').value;
                const password_confirmation = document.getElementById('password_confirmation').value;
                const token = document.getElementById('token').value;
                const uuid = document.getElementById('uuid').value;
                const responseMessage = document.getElementById('responseMessage');

                try {
                    const response = await fetch('http://127.0.0.1:8000/api/reset-password', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            password,
                            password_confirmation,
                            token,
                            uuid
                        })
                    });
                    const result = await response.json();
                    if (result.message) {
                        responseMessage.innerHTML = `${result.message}`;
                        if (result.success == true) {
                            responseMessage.style.color = "green";
                            setTimeout(function() {
                                location.reload();
                            }, 5000);
                        } else {
                            responseMessage.style.color = "red";
                        }
                    } else {
                        responseMessage.innerHTML = `${result.errorResponse.errors.password[0]}`;
                        responseMessage.style.color = "red";
                    }
                } catch (error) {
                    console.log('Eroor', error);
                }
            });
        });
    </script>
</body>

</html>
