@section('pageTitle')
    <h1 class="font-medium text-base text-gray-900 dark:text-gray-100">
        Get Started
    </h1>
@endsection

@section('breadcrumbs')
    <div class="flex items-center flex-wrap gap-1 text-sm">
        <a class="text-gray-700 hover:text-primary dark:text-gray-300" href="{{ route('admin.dashboard') }}">
            Home
        </a>
        <span class="text-gray-400 text-sm">/</span>
        <span class="text-gray-900 dark:text-gray-100">Network</span>
    </div>
@endsection

@section('toolbar')
    <!-- Search Button -->
    <button
        class="btn btn-icon w-9 h-9 rounded-md hover:bg-gray-200 hover:text-primary text-gray-600 dark:hover:bg-gray-700 dark:text-gray-300"
        title="Search">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>

    <!-- Export Button -->
    <a href="#"
        class="btn btn-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md flex items-center gap-2 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export
    </a>
@endsection

{{-- Main Page Content --}}
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-3 lg:gap-5 xl:gap-7.5">

    {{-- Sample Card 1 --}}
    <div class="card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Users</h3>
                <p class="text-2xl font-bold text-primary">1,248</p>
            </div>
        </div>
    </div>

    {{-- Sample Card 2 --}}
    <div class="card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Active Now</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">342</p>
            </div>
        </div>
    </div>

    {{-- Sample Card 3 --}}
    <div class="card bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pending</h3>
                <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">89</p>
            </div>
        </div>
    </div>

    {{-- User List Card --}}
    <div
        class="lg:col-span-2 xl:col-span-3 card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-4 lg:p-6">
            <h2 class="text-lg lg:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Users</h2>

            <div class="overflow-x-auto -mx-4 lg:mx-0">
                <table class="w-full min-w-[640px]">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-start py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">User
                            </th>
                            <th class="text-start py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Email</th>
                            <th class="text-start py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Status</th>
                            <th class="text-start py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 5; $i++)
                            <tr
                                class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}"
                                            alt="User {{ $i }}" class="w-8 h-8 rounded-full" />
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">User
                                            {{ $i }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    user{{ $i }}@example.com
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $i % 2 == 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $i % 2 == 0 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <button class="text-primary hover:text-primary/80 text-sm font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
