<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - {{ config('app.tagline') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full">
        @auth
        <!-- Navigation -->
        <nav class="bg-indigo-600" x-data="{ mobileMenuOpen: false }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-white font-bold text-xl">{{ config('app.name') }}</h1>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                    <a href="{{ route('admin.enrollments.index') }}" class="text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">Enrollments</a>
                                @elseif(Auth::user()->isTrainee())
                                    <a href="{{ route('trainee.dashboard') }}" class="text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                    <a href="{{ route('trainee.profile.show') }}" class="text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <span class="text-white text-sm mr-4">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-white hover:bg-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center rounded-md bg-indigo-600 p-2 text-indigo-200 hover:bg-indigo-500 hover:text-white focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="md:hidden">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-indigo-500 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('admin.enrollments.index') }}" class="text-white hover:bg-indigo-500 block px-3 py-2 rounded-md text-base font-medium">Enrollments</a>
                    @elseif(Auth::user()->isTrainee())
                        <a href="{{ route('trainee.dashboard') }}" class="text-white hover:bg-indigo-500 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <a href="{{ route('trainee.profile.show') }}" class="text-white hover:bg-indigo-500 block px-3 py-2 rounded-md text-base font-medium">Profile</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:bg-indigo-500 block w-full text-left px-3 py-2 rounded-md text-base font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
        @endauth

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">@yield('page-title', 'Dashboard')</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
