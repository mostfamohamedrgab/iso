<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .content {
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            margin-top: 20px;
        }

        .content {
            padding: 20px;
            color: #555;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Q Val</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>Your account has been created successfully. Here are your login details:</p>
            <ul>
                <li>Email: {{ $user->email }}</li>
                <li>Password: {{ $password }}</li>
            </ul>

            <a href="{{ $actionUrl }}" class="button">Login</a>

            <p>Please keep this information safe and do not share it with anyone.</p>
            <p>Best Regards,</p>
        </div>
    </div>
</body>
</html>
