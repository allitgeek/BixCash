@extends('layouts.admin')

@section('title', 'Admin Dashboard - BixCash')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats Grid - Single Row Layout --}}
    <div class="grid grid-cols-7 gap-3 mb-6 overflow-x-auto">
        {{-- Total Users Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-blue-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Total Users</p>
                </div>
            </div>
        </div>

        {{-- Admin Users Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-purple-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:bg-purple-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['admin_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Admin Users</p>
                </div>
            </div>
        </div>

        {{-- Customers Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-green-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-green-500/10 flex items-center justify-center text-green-600 group-hover:bg-green-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['customer_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Customers</p>
                </div>
            </div>
        </div>

        {{-- Partners Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-orange-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-600 group-hover:bg-orange-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['partner_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Partners</p>
                </div>
            </div>
        </div>

        {{-- Active Brands Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-indigo-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_brands'] }}<span class="text-sm text-gray-400 ml-1">/{{ $stats['total_brands'] }}</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Active Brands</p>
                </div>
            </div>
        </div>

        {{-- Active Categories Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-pink-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-pink-500/10 flex items-center justify-center text-pink-600 group-hover:bg-pink-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_categories'] }}<span class="text-sm text-gray-400 ml-1">/{{ $stats['total_categories'] }}</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Active Categories</p>
                </div>
            </div>
        </div>

        {{-- Active Slides Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-teal-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-teal-500/10 flex items-center justify-center text-teal-600 group-hover:bg-teal-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_slides'] }}<span class="text-sm text-gray-400 ml-1">/{{ $stats['total_slides'] }}</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Active Slides</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Users Card with Modern Design --}}
    <div class="bg-white/70 backdrop-blur-xl rounded-2xl border border-white/20 shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200/50 bg-gradient-to-r from-gray-50/50 to-transparent">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">Recent Users</h3>
                </div>
                <span class="text-sm font-medium text-gray-500">Latest registrations</span>
            </div>
        </div>
        <div class="p-6">
            <ul class="space-y-1">
                @forelse($recentUsers as $recentUser)
                    <li class="group flex items-center justify-between p-4 rounded-xl hover:bg-gray-50/80 border border-transparent hover:border-gray-200/50 transition-all duration-200 ease-in-out">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg shadow-lg group-hover:scale-110 transition-transform duration-200">
                                    {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-base font-semibold text-gray-900 truncate">{{ $recentUser->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $recentUser->email }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-500/10 to-purple-500/10 text-blue-700 border border-blue-200/50">
                                {{ $recentUser->role->display_name ?? 'No Role' }}
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="flex items-center justify-center p-8 text-gray-500 text-sm">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="font-medium">No recent users found</p>
                            <p class="text-xs text-gray-400 mt-1">New user registrations will appear here</p>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
