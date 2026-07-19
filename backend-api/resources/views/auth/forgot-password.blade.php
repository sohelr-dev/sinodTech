<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | FNF Trip</title>
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('assets/images/fnfLogo.jpg') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --glass-bg: rgba(255, 255, 255, 0.9);
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('{{ asset('assets/images/fnfLogo.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .forgot-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.4);
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-box {
            width: 72px;
            height: 72px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2);
            color: #6366f1;
            font-size: 1.75rem;
        }

        h2 {
            font-weight: 700;
            color: var(--text-main);
            font-size: 1.75rem;
            margin-bottom: 12px;
            letter-spacing: -0.025em;
        }

        .sub-text {
            color: var(--text-muted);
            font-size: 0.925rem;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #4b5563;
            display: block;
            text-align: left;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 10px;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 70%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .form-control {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            padding: 12px 12px 12px 48px;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: #fff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .btn-submit {
            background: var(--primary-gradient);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: 0.3s;
            margin-bottom: 24px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            filter: brightness(1.05);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: 0.2s;
        }

        .back-link:hover {
            color: #6366f1;
        }
    </style>
</head>

<body>

    <div class="forgot-card">
        <div class="icon-box">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h2>Forgot Password?</h2>
        <p class="sub-text">
            No worries! Enter your registered email address below and we'll send you a secure link to reset your
            password.
        </p>

        @if (session('status') === 'We have emailed your password reset link.')
            <div class="alert alert-success border-0 rounded-4 small mb-4 text-start" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Password reset link and code has been sent to your email 📩
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-wrapper">
                <label class="form-label">Email Address</label>
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" class="form-control" placeholder="Enter your email"
                    value="{{ old('email') }}" required autofocus>

            </div>
            @error('email')
                <div class="text-danger text-start small mt-1 mb-2" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                </div>
            @enderror

            <button type="submit" class="btn-submit">
                Send Reset Link
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Login
        </a>
    </div>

</body>

</html>
