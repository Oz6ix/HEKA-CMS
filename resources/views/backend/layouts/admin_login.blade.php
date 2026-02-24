<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HEKA — Sign In</title>
    <link rel="shortcut icon" href="{{ asset('assets/brand/heka-icon.png') }}"/>
    <link rel="icon" href="{{ asset('assets/brand/heka-icon.png') }}" type="image/png"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a5f' },
                        heka: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534' },
                    }
                }
            }
        }
    </script>
    <style>
        .login-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        .glass-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
        }
        .floating-shape {
            animation: float 6s ease-in-out infinite;
        }
        .floating-shape:nth-child(2) { animation-delay: -2s; }
        .floating-shape:nth-child(3) { animation-delay: -4s; }
        @keyframes float {
            0%,100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen login-bg flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Decorative Background Shapes -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="floating-shape absolute -top-32 -left-32 w-96 h-96 rounded-full bg-gradient-to-br from-emerald-500/10 to-teal-500/10 blur-3xl"></div>
            <div class="floating-shape absolute top-1/2 -right-32 w-80 h-80 rounded-full bg-gradient-to-br from-blue-500/10 to-indigo-500/10 blur-3xl"></div>
            <div class="floating-shape absolute -bottom-32 left-1/3 w-96 h-96 rounded-full bg-gradient-to-br from-cyan-500/8 to-emerald-500/8 blur-3xl"></div>
        </div>

        <!-- Login Card -->
        <div class="relative z-10 w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('assets/brand/heka-logo-full.png') }}" alt="HEKA" class="h-20 mx-auto mb-3 drop-shadow-lg">
                <p class="text-slate-400 text-sm">Next Gen Clinic Management System</p>
            </div>

            <!-- Card -->
            <div class="glass-card rounded-2xl shadow-2xl shadow-black/20 border border-white/20 p-8">
                <!-- Session Messages -->
                @if(session('message'))
                    <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <h2 class="text-xl font-bold text-slate-800 mb-1">Welcome back</h2>
                <p class="text-sm text-slate-500 mb-6">Sign in to your account to continue</p>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="login_form">
                    @csrf
                    <div class="space-y-4">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400 text-sm"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="admin@clinic.com">
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-400 text-sm"></i>
                                </div>
                                <input type="password" name="password" required id="password_field"
                                    class="block w-full pl-10 pr-10 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                    <i class="fas fa-eye text-sm" id="toggle_icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember + Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                                <span class="ml-2 text-sm text-slate-600">Remember me</span>
                            </label>
                            <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors">Forgot password?</a>
                        </div>

                        <!-- Submit -->
                        <button type="submit" id="login_button"
                            class="w-full flex justify-center items-center gap-2 py-2.5 px-4 rounded-lg bg-gradient-to-r from-slate-800 to-slate-700 text-white text-sm font-semibold hover:from-slate-700 hover:to-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 shadow-lg shadow-slate-900/20 transition-all duration-200">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} HEKA — Next Gen Clinic Management System
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var field = document.getElementById('password_field');
            var icon = document.getElementById('toggle_icon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
