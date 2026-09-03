@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('timestamp', 'Oct 24, 2025 — 10:42 AM')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm text-text-secondary mb-1">Total Registered Employees</p>
                    <p class="text-2xl font-semibold">1,248</p>
                    <div class="mt-3 flex items-center gap-1 text-xs font-medium text-success">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                        <span>12% from last month</span>
                    </div>
                </div>
                <div class="absolute -right-3 -top-3 w-20 h-20 text-accent-blue opacity-20">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.25a8.25 8.25 0 0114.997 0"/></svg>
                </div>
            </div>

            <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm text-text-secondary mb-1">Recognized Today</p>
                    <p class="text-2xl font-semibold">847</p>
                    <div class="mt-3 flex items-center gap-1 text-xs font-medium text-success">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                        <span>8.2% accuracy</span>
                    </div>
                </div>
                <div class="absolute -right-3 -top-3 w-20 h-20 text-success opacity-20">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 19.5a2.25 2.25 0 01-2.25-2.25V6A2.25 2.25 0 014.5 3.75h4.5a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25h-.75v4.5h.75a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25H4.5z"/></svg>
                </div>
            </div>

            <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm text-text-secondary mb-1">Unknown Detections</p>
                    <p class="text-2xl font-semibold">23</p>
                    <div class="mt-3 flex items-center gap-1 text-xs font-medium text-danger">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        <span>3 critical alerts</span>
                    </div>
                </div>
                <div class="absolute -right-3 -top-3 w-20 h-20 text-danger opacity-20">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
            </div>

            <div class="bg-dark-card border border-border-subtle rounded-xl p-5 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm text-text-secondary mb-1">Active Cameras</p>
                    <p class="text-2xl font-semibold">12/14</p>
                    <div class="mt-3 flex items-center gap-1 text-xs font-medium text-success">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                        <span>85.7% uptime</span>
                    </div>
                </div>
                <div class="absolute -right-3 -top-3 w-20 h-20 text-accent-blue opacity-20">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 012.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 002.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25-2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-border-subtle">
                    <div>
                        <h2 class="text-base font-semibold">Live Video Feed</h2>
                        <p class="text-xs text-text-secondary">Camera 01 — Main Entrance</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger/10 border border-danger/20 text-xs font-medium text-danger">
                            <span class="w-1.5 h-1.5 rounded-full bg-danger animate-pulse"></span>
                            LIVE
                        </span>
                    </div>
                </div>
                <div class="relative aspect-video bg-dark-bg">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center space-y-3">
                            <svg class="w-12 h-12 mx-auto text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                            <p class="text-sm text-text-secondary">Camera feed placeholder</p>
                        </div>
                    </div>

                    <div class="absolute top-4 left-4 space-y-2">
                        <div class="bg-accent-blue/90 text-white text-[10px] font-mono px-2 py-1 rounded border border-accent-blue">Alexander K. — 98.4%</div>
                        <div class="bg-success/90 text-white text-[10px] font-mono px-2 py-1 rounded border border-success">Sarah M. — 96.1%</div>
                    </div>

                    <div class="absolute bottom-4 left-4 flex items-center gap-2">
                        <div class="bg-dark-bg/80 backdrop-blur-sm text-[10px] text-text-secondary px-2 py-1 rounded border border-border-subtle font-mono">00:00:12</div>
                    </div>
                </div>
            </div>

            <div class="bg-dark-card border border-border-subtle rounded-xl flex flex-col">
                <div class="p-4 border-b border-border-subtle">
                    <h2 class="text-base font-semibold">Recent Detections</h2>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-border-subtle">
                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">AK</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">Alexander K.</p>
                            <p class="text-xs text-text-secondary">Main Entrance</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">98.4%</p>
                            <p class="text-[11px] text-text-secondary">2 min ago</p>
                        </div>
                    </div>

                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-success/20 flex items-center justify-center text-success text-xs font-semibold">SM</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">Sarah M.</p>
                            <p class="text-xs text-text-secondary">Parking Lot B</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">96.1%</p>
                            <p class="text-[11px] text-text-secondary">5 min ago</p>
                        </div>
                    </div>

                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">RJ</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">Robert J.</p>
                            <p class="text-xs text-text-secondary">Server Room</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">97.8%</p>
                            <p class="text-[11px] text-text-secondary">8 min ago</p>
                        </div>
                    </div>

                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-danger/20 flex items-center justify-center text-danger text-xs font-semibold">?</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">Unknown Person</p>
                            <p class="text-xs text-text-secondary">Warehouse</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-danger">—</p>
                            <p class="text-[11px] text-text-secondary">12 min ago</p>
                        </div>
                    </div>

                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-accent-blue/20 flex items-center justify-center text-accent-blue text-xs font-semibold">LW</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">Lisa W.</p>
                            <p class="text-xs text-text-secondary">Reception</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">99.2%</p>
                            <p class="text-[11px] text-text-secondary">14 min ago</p>
                        </div>
                    </div>

                    <div class="p-3 flex items-center gap-3 hover:bg-dark-elevated/50 transition-colors">
                        <div class="w-9 h-9 rounded-full bg-success/20 flex items-center justify-center text-success text-xs font-semibold">DK</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">David K.</p>
                            <p class="text-xs text-text-secondary">Loading Dock</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-success">95.5%</p>
                            <p class="text-[11px] text-text-secondary">18 min ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-dark-card border border-border-subtle rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-semibold">7-Day Recognition Activity</h2>
                    <p class="text-xs text-text-secondary">Daily recognized vs unknown detections</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-accent-blue"></span>
                        <span class="text-text-secondary">Recognized</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-danger"></span>
                        <span class="text-text-secondary">Unknown</span>
                    </div>
                </div>
            </div>
            <div class="relative w-full h-64">
                <svg class="w-full h-full" viewBox="0 0 700 200" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradBlue" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#2563EB" stop-opacity="0.4"/>
                            <stop offset="100%" stop-color="#2563EB" stop-opacity="0"/>
                        </linearGradient>
                        <linearGradient id="gradRed" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#EF4444" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#EF4444" stop-opacity="0"/>
                        </linearGradient>
                    </defs>

                    <path d="M0,180 C50,160 100,140 150,120 C200,100 250,90 300,70 C350,50 400,60 450,40 C500,20 550,50 600,30 C650,10 700,40 700,20 L700,200 L0,200 Z" fill="url(#gradBlue)"/>
                    <path d="M0,190 C50,180 100,170 150,175 C200,180 250,160 300,165 C350,170 400,150 450,155 C500,160 550,140 600,145 C650,150 700,130 700,140 L700,200 L0,200 Z" fill="url(#gradRed)"/>

                    <path d="M0,180 C50,160 100,140 150,120 C200,100 250,90 300,70 C350,50 400,60 450,40 C500,20 550,50 600,30 C650,10 700,40 700,20" fill="none" stroke="#2563EB" stroke-width="2"/>
                    <path d="M0,190 C50,180 100,170 150,175 C200,180 250,160 300,165 C350,170 400,150 450,155 C500,160 550,140 600,145 C650,150 700,130 700,140" fill="none" stroke="#EF4444" stroke-width="2"/>

                    <circle cx="0" cy="180" r="4" fill="#2563EB"/>
                    <circle cx="150" cy="120" r="4" fill="#2563EB"/>
                    <circle cx="300" cy="70" r="4" fill="#2563EB"/>
                    <circle cx="450" cy="40" r="4" fill="#2563EB"/>
                    <circle cx="600" cy="30" r="4" fill="#2563EB"/>
                    <circle cx="700" cy="20" r="4" fill="#2563EB"/>

                    <circle cx="0" cy="190" r="4" fill="#EF4444"/>
                    <circle cx="150" cy="175" r="4" fill="#EF4444"/>
                    <circle cx="300" cy="165" r="4" fill="#EF4444"/>
                    <circle cx="450" cy="155" r="4" fill="#EF4444"/>
                    <circle cx="600" cy="145" r="4" fill="#EF4444"/>
                    <circle cx="700" cy="140" r="4" fill="#EF4444"/>

                    <line x1="0" y1="200" x2="700" y2="200" stroke="rgba(255,255,255,0.06)" stroke-width="1"/>
                    <line x1="0" y1="150" x2="700" y2="150" stroke="rgba(255,255,255,0.06)" stroke-width="1" stroke-dasharray="4 4"/>
                    <line x1="0" y1="100" x2="700" y2="100" stroke="rgba(255,255,255,0.06)" stroke-width="1" stroke-dasharray="4 4"/>
                    <line x1="0" y1="50" x2="700" y2="50" stroke="rgba(255,255,255,0.06)" stroke-width="1" stroke-dasharray="4 4"/>
                </svg>

                <div class="absolute bottom-0 left-0 right-0 flex justify-between px-2 text-[11px] text-text-secondary font-mono">
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                    <span>Sun</span>
                </div>
            </div>
        </div>
    </div>
@endsection
