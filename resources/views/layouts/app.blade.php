<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RoomSched') }}{{ isset($title) ? ' · ' . $title : '' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }

            /* Page Loader */
            #page-loader {
                position: fixed; inset: 0; background: #f8fafc;
                z-index: 9999; display: flex; align-items: center; justify-content: center;
                transition: opacity 0.3s ease;
            }
            #page-loader.hidden { opacity: 0; pointer-events: none; }
            .loader-ring {
                width: 36px; height: 36px;
                border: 3px solid #e0e7ff;
                border-top-color: #4f46e5;
                border-radius: 50%;
                animation: spin 0.7s linear infinite;
            }
            @keyframes spin { to { transform: rotate(360deg); } }

            /* Button spinner */
            .btn-loading { position: relative; pointer-events: none; }
            .btn-loading::after {
                content: ''; position: absolute;
                width: 14px; height: 14px;
                top: 50%; left: 50%;
                margin: -7px 0 0 -7px;
                border: 2px solid rgba(255,255,255,0.35);
                border-top-color: white;
                border-radius: 50%;
                animation: spin 0.6s linear infinite;
            }
            .btn-loading > * { opacity: 0; }

            /* Skeleton loader */
            .skeleton { background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 6px; }
            @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

            /* Smooth page transitions */
            .page-fade { animation: fadeIn 0.2s ease-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

            /* Uniform table styles */
            .rss-table thead th { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; background: #f8fafc; padding: 0.75rem 1.25rem; border-bottom: 1px solid #e2e8f0; }
            .rss-table tbody tr { transition: background 0.1s; }
            .rss-table tbody tr:hover { background: #f8fafc; }
            .rss-table tbody td { padding: 0.875rem 1.25rem; font-size: 0.875rem; color: #475569; border-bottom: 1px solid #f1f5f9; }

            /* Uniform status badge */
            .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.65rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.03em; text-transform: uppercase; }
            .badge-indigo { background: #eef2ff; color: #4338ca; }
            .badge-green { background: #f0fdf4; color: #15803d; }
            .badge-amber { background: #fffbeb; color: #b45309; }
            .badge-red { background: #fef2f2; color: #b91c1c; }
            .badge-slate { background: #f1f5f9; color: #475569; }

            /* Toast animation */
            .toast-enter { animation: toastIn 0.35s ease; }
            @keyframes toastIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">

        <!-- Page Loader -->
        <div id="page-loader">
            <div class="loader-ring"></div>
        </div>

        <div class="min-h-screen page-fade">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-slate-100 shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                {{ $slot }}
            </main>
        </div>

        <script>
            // Hide loader on page ready
            window.addEventListener('load', function() {
                const loader = document.getElementById('page-loader');
                loader.classList.add('hidden');
                setTimeout(() => loader.remove(), 400);
            });

            // Button loading state
            document.addEventListener('DOMContentLoaded', function() {
                // Add spinner to submit buttons on form submit
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = form.querySelector('[type="submit"]');
                        if (submitBtn && !submitBtn.dataset.noLoader) {
                            setTimeout(() => submitBtn.classList.add('btn-loading'), 50);
                        }
                    });
                });

                // Confirm delete with custom handling
                document.querySelectorAll('[data-confirm]').forEach(el => {
                    el.addEventListener('click', function(e) {
                        if (!confirm(this.dataset.confirm)) e.preventDefault();
                    });
                });
            });
        </script>
    </body>
</html>
