<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Dashboard</title>

    <!-- ✅ Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Inventory System</h1>

            <div class="space-x-4">
                @auth
                    <span class="text-gray-700">Hi, {{ Auth::user()->name }}</span>
                    <a href="{{ url('/home') }}" class="text-indigo-600 hover:underline">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline">Logout</button>
                    </form>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Manage Products -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold">Manage Products</h2>
                    <p class="mt-2 text-sm text-gray-600">Add, update or remove items.</p>
                    <a href="#" class="inline-block mt-4 text-indigo-600 hover:underline">Go to Products →</a>
                </div>

                <!-- View Stock -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold">Stock Status</h2>
                    <p class="mt-2 text-sm text-gray-600">Track inventory and alerts.</p>
                    <a href="#" class="inline-block mt-4 text-indigo-600 hover:underline">View Stock →</a>
                </div>

                <!-- Reports -->
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold">Reports</h2>
                    <p class="mt-2 text-sm text-gray-600">Generate and download reports.</p>
                    <a href="#" class="inline-block mt-4 text-indigo-600 hover:underline">Generate Reports →</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-500 mt-16 mb-4">
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </footer>

</body>
</html>
