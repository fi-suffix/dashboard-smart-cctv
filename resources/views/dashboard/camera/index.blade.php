@extends('layouts.dashboard')

@section('title', 'Cameras')
@section('page-title', 'Cameras')
@section('timestamp', 'Oct 24, 2025 — 10:42 AM')


@section('content')
    <div class="bg-dark-card border border-border-subtle rounded-xl overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-border-subtle">
            <div>
                <h2 class="text-base font-semibold">Camera Management</h2>
                <p class="text-xs text-text-secondary">Monitor and configure CCTV cameras</p>
            </div>
            <button class="px-4 py-2 bg-accent-blue hover:bg-accent-blue-hover text-white text-sm font-medium rounded-lg transition-colors">
                Add Camera
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            <div class="bg-dark-elevated/40 border border-border-subtle rounded-xl p-4 hover:border-accent-blue/40 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-dark-card flex items-center justify-center text-text-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 012.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 002.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25-2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Camera 01</p>
                            <p class="text-[11px] text-text-secondary">Main Entrance</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-success/10 text-success text-[11px] font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Online
                    </span>
                </div>
                <div class="aspect-video bg-dark-bg rounded-lg mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <div class="flex items-center justify-between text-xs text-text-secondary">
                    <span>192.168.1.101</span>
                    <span>RTSP</span>
                </div>
            </div>

            <div class="bg-dark-elevated/40 border border-border-subtle rounded-xl p-4 hover:border-accent-blue/40 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-dark-card flex items-center justify-center text-text-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 012.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 002.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25-2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Camera 02</p>
                            <p class="text-[11px] text-text-secondary">Parking Lot B</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-success/10 text-success text-[11px] font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Online
                    </span>
                </div>
                <div class="aspect-video bg-dark-bg rounded-lg mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <div class="flex items-center justify-between text-xs text-text-secondary">
                    <span>192.168.1.102</span>
                    <span>RTSP</span>
                </div>
            </div>

            <div class="bg-dark-elevated/40 border border-border-subtle rounded-xl p-4 hover:border-accent-blue/40 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-dark-card flex items-center justify-center text-text-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.833-.744 1.666-1.088 2.5M15 13.5a2.25 2.25 0 01-2.25 2.25H6.75m13.5 0a2.25 2.25 0 01-2.25-2.25M6.75 15a2.25 2.25 0 002.25 2.25h.008v.008h-.008a2.25 2.25 0 01-2.25-2.25M6.75 15V6.75m9 8.25V6.75m0 0a2.25 2.25 0 012.25-2.25h.008v-.008h-.008a2.25 2.25 0 01-2.25 2.25v.008h.008a2.25 2.25 0 012.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25 2.25v.008h.008a2.25 2.25 0 002.25 2.25v.008h.008a2.25 2.25 0 002.25-2.25v-.008h-.008a2.25 2.25 0 00-2.25-2.25v.008h.008a2.25 2.25 0 01-2.25-2.25v-.008h-.008z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Camera 03</p>
                            <p class="text-[11px] text-text-secondary">Server Room</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-danger/10 text-danger text-[11px] font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>Offline
                    </span>
                </div>
                <div class="aspect-video bg-dark-bg rounded-lg mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-text-secondary opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <div class="flex items-center justify-between text-xs text-text-secondary">
                    <span>192.168.1.103</span>
                    <span>RTSP</span>
                </div>
            </div>
        </div>
    </div>
@endsection
