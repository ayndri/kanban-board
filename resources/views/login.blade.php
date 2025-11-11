<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-size: cover;
            background-position: center;
            padding: 20px;
        }

        /* Login Card Wrapper */
        .login-wrapper {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        /* Logo */
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        /* Headings */
        .login-wrapper h2 {
            color: #1c1c1e;
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .login-wrapper p {
            color: #6a6a6a;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* Form Group */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        /* Input Fields */
        .input-field {
            width: 100%;
            padding: 14px 20px 14px 45px;
            /* Add padding for icon */
            border: 1px solid #dcdcdc;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-field::placeholder {
            color: #9a9a9a;
        }

        .input-field:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        /* Input Icons */
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            width: 20px;
            height: 20px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
        }

        /* Links section */
        .links-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .links-section a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        .links-section a:hover {
            text-decoration: underline;
        }

        /* New user registration link */
        .register-section {
            margin-top: 30px;
            font-size: 14px;
            color: #6a6a6a;
        }

        .register-section a {
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
        }

        .register-section a:hover {
            text-decoration: underline;
        }

        /* Error messages */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
            border: 1px solid #f5c6cb;
        }

        .error-message ul {
            padding-left: 20px;
            margin-top: 5px;
        }
    </style>
</head>

<body style="background-image: url('{{ asset('image/bg.png') }}');">

    <div class="login-wrapper">
        <img src="{{ asset('/image/logopt.png') }}" alt="Logo" class="logo">
        <h2>Selamat Datang Kembali</h2>
        <p>Silakan masukkan detail akun Anda untuk melanjutkan.</p>

        <form action="{{ route('check') }}" method="post">
            @csrf

            <!-- Session Status/Error Messages -->
            @if ($errors->any())
            <div class="error-message">
                <strong>Oops! Terjadi kesalahan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
                <input type="email" id="email" name="email" class="input-field" placeholder="Masukkan email Anda" required value="{{ old('email') }}">
            </div>

            <div class="input-group">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <input type="password" id="password" name="password" class="input-field" placeholder="Masukkan password" required>
            </div>

            <div class="links-section">
                <label for="remember_me" class="flex items-center text-gray-600 cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 mr-2 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500">
                    Ingat saya
                </label>
                <a href="#">Lupa Password?</a>
            </div>

            <button type="submit" class="submit-btn">LOGIN</button>

        </form>
    </div>

</body>

</html>