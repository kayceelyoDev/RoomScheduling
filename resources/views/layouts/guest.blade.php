<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        
        <style>
            .bg-auth {
                background: radial-gradient(circle at top right, #e0e7ff 0%, #ffffff 50%, #f1f5f9 100%);
                position: relative;
                overflow: hidden;
            }
            .bg-auth::before {
                content: '';
                position: absolute;
                top: -10%;
                right: -10%;
                width: 40%;
                height: 40%;
                background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 70%);
                border-radius: 50%;
                z-index: 0;
            }
            .bg-auth::after {
                content: '';
                position: absolute;
                bottom: -10%;
                left: -10%;
                width: 35%;
                height: 35%;
                background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
                border-radius: 50%;
                z-index: 0;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-up {
                animation: slideUp 0.6s ease-out forwards;
            }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-auth min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">
            <div class="mb-8 animate-slide-up" style="animation-delay: 0.1s;">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <div class="p-4 bg-white rounded-2xl shadow-sm border border-slate-100 group-hover:shadow-md transition-all duration-300 group-hover:scale-110">
                        <x-application-logo class="w-12 h-12 fill-current text-indigo-600" />
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-800">RSS <span class="text-indigo-600">Portal</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md glass-card p-8 rounded-[2.5rem] animate-slide-up" style="animation-delay: 0.2s;">
                <div class="mb-8">
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>

                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center animate-slide-up" style="animation-delay: 0.3s;">
                <p class="text-xs text-slate-400 font-medium">© {{ date('Y') }} Room Scheduling System. All rights reserved.</p>
            </div>
        </div>

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
