<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | FNF Trip</title>
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

        .reset-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            padding: 40px;
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
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #6366f1;
            font-size: 1.5rem;
            box-shadow: 0 10px 15px rgba(99, 102, 241, 0.1);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4b5563;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        .input-group {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            transition: 0.3s;
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group:focus-within {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #9ca3af;
            padding-left: 15px;
        }

        .form-control {
            background: transparent;
            border: none;
            padding: 12px 10px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .password-toggle {
            cursor: pointer;
            padding-right: 15px;
            color: #9ca3af;
            transition: 0.2s;
        }

        .password-toggle:hover {
            color: #6366f1;
        }

        .btn-update {
            background: var(--primary-gradient);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .error-msg {
            font-size: 0.75rem;
            color: #dc3545;
            text-align: left;
            margin-top: 4px;
            padding-left: 5px;
        }
    </style>
</head>

<body>

    <div class="reset-card">
        <div class="icon-box">
            <i class="fa-solid fa-lock-open"></i>
        </div>

        <h2 class="fw-bold text-dark">Reset Password</h2>
        <p class="text-muted small mb-4">Set your new password to regain access to your FNF Trip account.</p>
        @if ($errors->has('email'))
            <div class="alert alert-danger border-0 rounded-4 small mb-4 text-start" role="alert"
                style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                    <input type="password" name="password" id="newPass" class="form-control"
                        placeholder="enter new password" required autofocus autocomplete="new-password">
                    <span class="password-toggle" onclick="togglePass('newPass')">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-check-double"></i></span>
                    <input type="password" name="password_confirmation" id="confirmPass" class="form-control"
                        placeholder="confirm new password" required autocomplete="new-password">
                    <span class="password-toggle" onclick="togglePass('confirmPass')">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                @error('password_confirmation')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-update">
                Update Password
            </button>
        </form>
            <a href="{{ route('login') }}" class="back-link mt-3 btn btn-outline-info btn-sm rounded-pill text-dark">
                 <i class="fa-solid fa-arrow-left"></i> Back to Login
            </a>
    </div>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            const icon = event.currentTarget.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>

</html>
