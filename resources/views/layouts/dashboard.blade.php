<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'FaceGuard AI - Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-dark-bg text-text-primary font-sans antialiased">
    <div class="flex min-h-screen">
        <aside class="fixed inset-y-0 left-0 w-64 bg-dark-card border-r border-border-subtle z-50 flex flex-col">
            <div class="h-16 flex items-center gap-3 px-6 border-b border-border-subtle">
                <div class="w-8 h-8 rounded-lg bg-accent-blue flex items-center justify-center text-white font-bold text-sm">FG</div>
                <span class="text-lg font-semibold tracking-tight">FaceGuard AI</span>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-accent-blue text-white' : 'text-text-secondary hover:text-text-primary hover:bg-dark-elevated' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('dashboard.live_monitoring.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-text-secondary hover:text-text-primary hover:bg-dark-elevated">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                    Live Monitoring
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('employees.*') ? 'bg-accent-blue text-white' : 'text-text-secondary hover:text-text-primary hover:bg-dark-elevated' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-3.488-6.952M15 19.128v.003M15 19.128a48.47 48.47 0 01-6.364-2.464M15 19.128a48.47 48.47 0 01-2.464-6.364M15 19.128A9.375 9.375 0 0012 21a9.375 9.375 0 00-3-1.872M15 19.128a48.47 48.47 0 01-2.464-6.364M15 19.128A9.375 9.375 0 0012 21a9.375 9.375 0 00-3-1.872M12 15a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    Employees
                </a>

                <a href="{{ route('dashboard.camera.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('cameras.*') ? 'bg-accent-blue text-white' : 'text-text-secondary hover:text-text-primary hover:bg-dark-elevated' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 012.25 2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 012.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25-2.25v.008h.008a2.25 2.25 0 002.25 2.25v.008h.008a2.25 2.25 0 012.25-2.25v-.008h-.008z"/></svg>
                    Cameras
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-text-secondary hover:text-text-primary hover:bg-dark-elevated">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Detection History
                </a>

                <a href="{{ route('dashboard.setting.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-text-secondary hover:text-text-primary hover:bg-dark-elevated">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003 1.008c-.283.29-.26.767.065 1.088l1.24.987c.328.263.45.72.3 1.12l-.72 1.152a1.125 1.125 0 01-1.587.243l-1.003-1.004a1.125 1.125 0 00-1.37.49l-1.217.456a1.125 1.125 0 01-1.075-.124 2.469 2.469 0 01-.22-.127c-.332-.184-.582-.496-.645-.87l-.213-1.281c-.09-.542-.56-.94-1.11-.94H9.594c-.55 0-1.02.398-1.11.94l-.213 1.281c-.063.374-.313.686-.645.87a2.44 2.44 0 01-.22.127c-.324.196-.72.257-1.075.124l-1.217-.456a1.125 1.125 0 01-1.37-.49L3.06 9.369a1.125 1.125 0 01.26-1.431l1.003-1.008c.283-.29.26-.767-.065-1.088l-1.24-.987c-.328-.263-.45-.72-.3-1.12l.72-1.152a1.125 1.125 0 011.587-.243l1.003 1.004a1.125 1.125 0 001.37-.49l1.217-.456c.355-.133.751-.072 1.075.124.077.044.146.087.22.127.332.184.582.496.645.87l.213 1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-text-secondary hover:text-text-primary hover:bg-dark-elevated">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                    Help
                </a>
            </nav>

            <div class="p-3 border-t border-border-subtle">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-dark-elevated">
                    <div class="w-9 h-9 rounded-full bg-accent-blue flex items-center justify-center text-white text-sm font-semibold">AK</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">Alexander K.</p>
                        <p class="text-xs text-text-secondary truncate">Security Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 ml-64">
            <header class="sticky top-0 z-40 h-16 bg-dark-surface/80 backdrop-blur-md border-b border-border-subtle flex items-center justify-between px-6">
                <div>
                    <h1 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-text-secondary">@yield('timestamp', 'Oct 24, 2025 — 10:42 AM')</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative hidden md:block">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" placeholder="Search anything..." class="w-64 pl-9 pr-4 py-1.5 text-sm bg-dark-card border border-border-subtle rounded-lg text-text-primary placeholder:text-text-secondary focus:outline-none focus:ring-1 focus:ring-accent-blue">
                    </div>

                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-dark-card border border-border-subtle">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        <span class="text-xs font-medium text-success">System Online</span>
                    </div>

                    <button class="relative p-2 rounded-lg hover:bg-dark-elevated text-text-secondary hover:text-text-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-danger"></span>
                    </button>
                </div>
            </header>

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>
</html>
