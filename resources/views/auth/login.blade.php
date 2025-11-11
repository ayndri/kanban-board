{{--
    This is a redesigned, modern login page for a Laravel application.
    It replaces the default Breeze or Jetstream login view with a more visually appealing layout.
    The design features a two-column card layout on larger screens, with a branding panel on the left and the login form on the right.
    On mobile devices, it gracefully collapses into a single-column view.

    Key Features:
    - Fully responsive design.
    - Modern card-based UI with shadow and rounded corners.
    - Input fields with integrated SVG icons for better user experience.
    - A clean and focused layout, centered on the page.
    - Preserves all original Laravel backend functionality (@csrf, session status, error handling, old input).
--}}
<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="relative flex flex-col m-6 space-y-8 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0">
            <!-- Left Side -->
            <div class="relative p-8 md:p-12">
                <h1 class="mb-3 text-4xl font-bold">Selamat Datang</h1>
                <p class="max-w-sm mb-8 font-light text-gray-600 dark:text-gray-400">
                    Silakan masuk untuk mengakses dasbor Anda dan mengelola semua informasi penting di satu tempat.
                </p>

                <form method="POST" action="{{ route('login') }}" class="flex flex-col space-y-6">
                    @csrf

                    <!-- Session Status Message -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="block w-full py-3 pl-10 pr-3 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full py-3 pl-10 pr-3 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-gray-400">Ingat saya</label>
                        </div>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Lupa password?
                        </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div>
                        <x-primary-button class="justify-center w-full py-3 text-lg">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>

            <!-- Right Side (Image) -->
            <div class="relative hidden md:block">
                <img src="https://images.unsplash.com/photo-1585314062340-f1a5a7c9328d?q=80&w=1887&auto=format&fit=crop"
                    alt="Image"
                    class="object-cover w-[400px] h-full hidden rounded-r-2xl md:block" />
                <!-- You can add overlay text or logo here if needed -->
            </div>

        </div>
    </div>
</x-guest-layout>