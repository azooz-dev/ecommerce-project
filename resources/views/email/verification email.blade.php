<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header {
      background-color: #007bff;
      color: #ffffff;
      text-align: center;
      padding: 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 24px;
    }

    .content {
      padding: 20px;
      color: #333333;
      line-height: 1.6;
    }

    .content h2 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .content p {
      margin: 0 0 20px;
    }

    .button {
      display: block;
      width: fit-content;
      margin: 20px auto;
      background-color: #007bff;
      color: #ffffff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 5px;
    }

    .footer {
      text-align: center;
      padding: 10px;
      font-size: 12px;
      color: #777777;
      background-color: #f4f4f4;
    }
  </style>
</head>

<body>
  <div class="email-container">
    <!-- Header Section -->
    <div class="header">
      <h1>Verify Your Email Address</h1>
    </div>

    <!-- Content Section -->
    <div class="content">
      <h2>Hello {{ $user->first_name . ' ' . $user->last_name }},</h2>
      <p>Thank you for signing up with {{ config('app.name') }}. To complete your registration, please verify your email
        address by clicking the button below:</p>
      <a href="{{ route('users.verify', ['token' => $user->verification_token]) }}" class="button">Verify Email</a>
      <p>If you did not sign up for this account, you can safely ignore this email.</p>
    </div>

    <!-- Footer Section -->
    <div class="footer">
      <p>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
  </div>
</body>

</html>