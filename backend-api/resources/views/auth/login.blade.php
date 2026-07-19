<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FNF Trip - Login</title>
    
    <link rel="icon" href="{{ asset('assets/images/fnfLogo.jpg') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            /* Background image with a dark overlay for better contrast */
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('{{ asset('assets/images/fnfLogo.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-radius: 28px;
            width: 100%;
            max-width: 480px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeIn 0.6s ease-out;
        }

        .logo-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-box img {
            width: 70px;
            margin-bottom: 10px;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
        }

        .logo-box h2 {
            font-weight: 800;
            color: #1f2937;
            font-size: 1.8rem;
            margin: 0;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .input-group {
            background: #f3f4f6;
            border-radius: 12px;
            border: 1.5px solid transparent;
            transition: 0.3s;
            margin-bottom: 18px;
        }

        .input-group:focus-within {
            border-color: var(--primary);
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
            background: transparent;
            box-shadow: none;
            border: none;
        }

        .password-toggle {
            cursor: pointer;
            padding-right: 15px;
            color: #9ca3af;
            display: flex;
            align-items: center;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .google-btn {
            background: white;
            border: 1px solid #e5e7eb;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            transition: 0.3s;
        }

        .google-btn:hover {
            background: #f9fafb;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #9ca3af;
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        .toggle-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #6b7280;
        }

        .toggle-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .d-none {
            display: none !important;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <div class="logo-box">
            <img src="{{ asset('assets/images/fnfLogo.jpg') }}" alt="Logo">
            <h2 id="authTitle">Sign In</h2>
        </div>
        @if (session('status'))
            <div id="googleLoginError" class="alert alert-danger rounded-pill shadow text-center mx-auto mt-4" role="alert"
                data-aos="fade-down-left" data-aos-duration="1000" data-aos-offset="0"><i
                    class="fa-solid fa-triangle-exclamation me-2"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-2">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                        placeholder="name@example.com" required>
                </div>
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-2">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="loginPass" value="{{ old('password') }}"
                        class="form-control" placeholder="••••••••" required>
                    <span class="password-toggle" onclick="togglePass('loginPass')"><i
                            class="fa-regular fa-eye"></i></span>
                </div>
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">Keep me logged in</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-decoration-none small fw-bold"
                    style="color: var(--primary);">Forgot your password?</a>
            </div>

            <button type="submit" class="btn-primary-custom">Sign In</button>
        </form>

        <form id="registerForm" class="d-none" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-2">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Create Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="regPass" class="form-control"
                        placeholder="Min 8 characters" required>
                    <span class="password-toggle" onclick="togglePass('regPass')"><i
                            class="fa-regular fa-eye"></i></span>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-shield-check"></i></span>
                    <input type="password" name="password_confirmation" id="regPassConfirm" class="form-control"
                        placeholder="Repeat password" required>
                </div>
            </div>

            <button type="submit" class="btn-primary-custom">Create Account</button>
        </form>

        <div class="divider">OR</div>

        <a class="google-btn text-decoration-none" href="{{ route('google.login') }}">
            <img src="https://cdn-icons-png.flaticon.com/128/300/300221.png" width="20" alt="google">
            Continue with Google
        </a>

        <div class="toggle-link">
            <span id="toggleText">New to FNF Trip?</span>
            <a href="javascript:void(0)" onclick="toggleForms()" id="toggleBtn">Sign Up</a>
        </div>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const authTitle = document.getElementById('authTitle');
            const toggleText = document.getElementById('toggleText');
            const toggleBtn = document.getElementById('toggleBtn');

            if (loginForm.classList.contains('d-none')) {
                loginForm.classList.remove('d-none');
                registerForm.classList.add('d-none');
                authTitle.innerText = "Sign In";
                toggleText.innerText = "New to FNF Trip?";
                toggleBtn.innerText = "Sign Up";
            } else {
                loginForm.classList.add('d-none');
                registerForm.classList.remove('d-none');
                authTitle.innerText = "Create Account";
                toggleText.innerText = "Already have an account?";
                toggleBtn.innerText = "Sign In";
            }
        }

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
        AOS.init();
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                const alert = document.getElementById('googleLoginError');
                if (alert) {
                    alert.classList.add('fade'); // Bootstrap fade
                    setTimeout(() => alert.remove(), 500); // remove after fade
                }
            }, 4000); // 4 seconds
        });
    </script>
</body>

</html>
