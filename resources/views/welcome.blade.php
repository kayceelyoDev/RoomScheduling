<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RoomSched — Smart Room Scheduling</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .hero-gradient { background: linear-gradient(135deg, #eef2ff 0%, #f0f9ff 50%, #f8fafc 100%); }
        .feature-card { transition: all 0.2s ease; }
        .feature-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px -8px rgba(79, 70, 229, 0.15); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .fade-in { animation: fadeUp 0.6s ease forwards; }
        .fade-in-delay-1 { animation: fadeUp 0.6s 0.1s ease forwards; opacity: 0; }
        .fade-in-delay-2 { animation: fadeUp 0.6s 0.2s ease forwards; opacity: 0; }
        .fade-in-delay-3 { animation: fadeUp 0.6s 0.3s ease forwards; opacity: 0; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="antialiased">

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                    <i data-lucide="calendar-range" class="w-4 h-4 text-white"></i>
                </div>
                <span class="font-bold text-slate-900 text-sm">RoomSched</span>
            </div>
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">Dashboard →</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">My Dashboard →</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">Sign In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg shadow-sm transition">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-[92vh] flex items-center">
        <div class="max-w-6xl mx-auto px-6 py-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-full px-3 py-1 text-xs font-semibold text-indigo-700 mb-6 fade-in">
                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></div>
                    School Room Management System
                </div>
                <h1 class="text-5xl lg:text-6xl font-black text-slate-900 leading-tight mb-6 fade-in-delay-1">
                    Schedule rooms,<br><span class="text-indigo-600">not headaches.</span>
                </h1>
                <p class="text-lg text-slate-500 mb-8 leading-relaxed max-w-lg fade-in-delay-2">
                    Effortlessly manage classroom schedules, sections, and room allocations. Teachers can request rooms, admins approve with one click.
                </p>
                <div class="flex flex-wrap gap-3 fade-in-delay-3">
                    @auth
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-200 transition-all hover:shadow-lg hover:shadow-indigo-200 hover:-translate-y-0.5">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-200 transition-all hover:shadow-lg hover:shadow-indigo-200 hover:-translate-y-0.5">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            Sign In
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-xl border border-slate-200 shadow-sm transition-all hover:-translate-y-0.5">
                            Create Account
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        @endif
                    @endauth
                </div>

                <!-- Stats -->
                <div class="flex flex-wrap gap-8 mt-12 pt-8 border-t border-slate-100 fade-in-delay-3">
                    <div>
                        <div class="text-2xl font-black text-slate-900">Real-time</div>
                        <div class="text-xs text-slate-500 font-medium mt-0.5">Schedule Visibility</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900">Zero</div>
                        <div class="text-xs text-slate-500 font-medium mt-0.5">Booking Conflicts</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-slate-900">1-click</div>
                        <div class="text-xs text-slate-500 font-medium mt-0.5">Request Approval</div>
                    </div>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="relative hidden lg:block animate-float">
                <div class="bg-white rounded-2xl shadow-2xl shadow-indigo-100 border border-slate-100 p-5 max-w-md ml-auto">
                    <!-- Mini Calendar Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-white"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-800">Today's Schedule</span>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::today()->format('D, M j') }}</span>
                    </div>
                    <!-- Mock Schedule Rows -->
                    <div class="space-y-2">
                        @foreach([['CS 101 - Algorithms', 'GBL-101', '7:00 AM', 'indigo'], ['ENG 201 - Physics', 'SCI-203', '9:30 AM', 'emerald'], ['MATH 301 - Calculus', 'GBL-102', '1:00 PM', 'amber'], ['IT 401 - Networks', 'IT-Lab', '3:30 PM', 'indigo']] as [$name, $room, $time, $color])
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors">
                            <div class="w-1 h-8 rounded-full bg-{{ $color }}-500 shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-slate-800 truncate">{{ $name }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    {{ $room }}
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 shrink-0">{{ $time }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Request button mock -->
                    <div class="mt-4 p-3 bg-indigo-50 border border-dashed border-indigo-200 rounded-xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-white rounded-lg border border-indigo-100 flex items-center justify-center">
                            <i data-lucide="plus" class="w-4 h-4 text-indigo-600"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-indigo-700">Request a Room</div>
                            <div class="text-[10px] text-indigo-400">Click any slot to start</div>
                        </div>
                    </div>
                </div>

                <!-- Floating badge -->
                <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-lg border border-slate-100 p-3 flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-800">Request Approved</div>
                        <div class="text-[9px] text-slate-400">Room GBL-203 · Monday</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">Everything you need</p>
                <h2 class="text-3xl font-black text-slate-900 mb-3">Built for schools, used daily</h2>
                <p class="text-slate-500 max-w-md mx-auto">A complete room management solution designed to eliminate scheduling conflicts and streamline requests.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['calendar-days', 'indigo', 'Visual Timeline', 'See all room occupancy in a beautiful horizontal timeline. Spot free slots at a glance without any confusion.'],
                    ['inbox', 'emerald', 'Smart Requests', 'Teachers click on a slot and submit a room request. Admins approve with one click — schedule is created automatically.'],
                    ['shield-check', 'amber', 'Conflict Prevention', 'Built-in duplicate detection ensures no two classes are ever booked for the same room at the same time.'],
                    ['users', 'slate', 'Role Management', 'Separate admin, teacher, and student interfaces. Everyone sees exactly what they need, nothing more.'],
                    ['refresh-cw', 'indigo', 'Recurring Schedules', 'Set up weekly recurring classes with MWF, TTh, or custom day patterns. Update once, apply everywhere.'],
                    ['bar-chart-2', 'emerald', 'Admin Dashboard', 'Real-time KPIs for rooms, teachers, sections, and today\'s active schedules — all in one clean dashboard.'],
                ] as [$icon, $color, $title, $desc])
                <div class="feature-card p-6 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <div class="w-10 h-10 bg-{{ $color }}-100 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="{{ $icon }}" class="w-5 h-5 text-{{ $color }}-600"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">{{ $title }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Footer Section -->
    <section class="py-20 bg-indigo-600">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-black text-white mb-3">Ready to get started?</h2>
            <p class="text-indigo-200 mb-8">Sign in below to access your dashboard.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-700 font-semibold rounded-xl shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-700 font-semibold rounded-xl shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Sign In to RoomSched
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar-range" class="w-3.5 h-3.5 text-white"></i>
                </div>
                <span class="font-bold text-white text-sm">RoomSched</span>
            </div>
            <p class="text-xs text-slate-500">Room Scheduling System &copy; {{ date('Y') }}</p>
        </div>
    </footer>

</body>
</html>
