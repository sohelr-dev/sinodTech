<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }
        .header {
            background-color: #002e5b; 
            padding: 30px;
            text-align: center;
        }
        .header img {
            width: 60px;
            border-radius: 50%;
            border: 3px solid #ffcc00;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        h2 {
            margin-top: 0;
            font-size: 22px;
            font-weight: 700;
            color: #111;
        }
        .otp-box {
            background-color: #f4f7fa;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #002e5b;
            margin: 0;
        }
        .btn {
            display: inline-block;
            background-color: #ffcc00; 
            color: #002e5b !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 0;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .divider {
            margin: 30px 0;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('assets/images/fnfLogo.jpg') }}" alt="FNF Trip Logo">
        </div>

        <div class="content">
            <h2>Reset Your Password</h2>
            <p>Hello,</p>
            <p>We received a request to reset the password for your <strong>FNF Trip</strong> account. Use the verification code below to proceed:</p>
            
            <div class="otp-box">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #666; text-uppercase; letter-spacing: 1px;">Your OTP Code</p>
                <h1 class="otp-code">{{ $code }}</h1>
            </div>

            <div style="text-align: center;">
                <p>Alternatively, you can click the button below to reset it directly:</p>
                <a href="{{ $link }}" class="btn">Reset Password Now</a>
            </div>

            <div class="divider"></div>

            <p style="font-size: 13px; color: #666;">
                <strong>Note:</strong> This link and code will expire in <strong>10 minutes</strong>. If you did not request a password reset, please ignore this email or contact support.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date("Y") }} FNF Trip Global Network. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>