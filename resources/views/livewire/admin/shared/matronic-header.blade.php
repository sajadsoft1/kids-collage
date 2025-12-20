<div>
    {{-- Fixed Header --}}
    <header class="sticky top-0 z-30 w-full h-16 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between h-full px-6">
            {{-- Left Side: Separator, Toggle, Breadcrumbs --}}
            <div class="flex items-center gap-4 flex-1">
                {{-- Separator Line --}}
                <div class="h-6 w-px bg-gray-300"></div>

                {{-- Sidebar Toggle (Mobile) --}}
                @if (!$showMenu)
                    <button @click="$dispatch('toggle-sidebar')"
                        class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </button>
                @endif

                {{-- Sidebar Toggle (Desktop) --}}
                <button @click="$dispatch('toggle-sidebar')"
                    class="hidden lg:flex p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    <x-icon name="o-bars-3" class="w-5 h-5" />
                </button>

                {{-- Breadcrumbs --}}
                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                    <x-icon name="o-chevron-right" class="w-4 h-4 text-gray-400" />
                    <span class="text-gray-900 font-medium">Updates</span>
                </nav>
            </div>

            {{-- Right Side: Icons, Search, Buttons, User --}}
            <div class="flex items-center gap-3">
                {{-- Action Icons --}}
                <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Coffee">
                    <x-icon name="o-sparkles" class="w-5 h-5" />
                </button>
                <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Code">
                    <x-icon name="o-code-bracket" class="w-5 h-5" />
                </button>
                <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Bookmark">
                    <x-icon name="o-bookmark" class="w-5 h-5" />
                </button>

                {{-- Search Bar --}}
                <div class="hidden md:flex items-center relative">
                    <x-icon name="o-magnifying-glass" class="absolute left-3 w-4 h-4 text-gray-400" />
                    <input type="text" placeholder="Search"
                        class="pl-10 pr-8 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                    <x-icon name="o-chevron-down" class="absolute right-3 w-4 h-4 text-gray-400" />
                </div>

                {{-- Reports Button --}}
                <button
                    class="hidden md:flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <x-icon name="o-document-text" class="w-4 h-4" />
                    <span>Reports</span>
                </button>

                {{-- Add Button --}}
                <button
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-black hover:bg-gray-900 rounded-lg transition-colors">
                    <x-icon name="o-plus" class="w-4 h-4" />
                    <span class="hidden sm:inline">Add</span>
                </button>

                {{-- User Profile Avatar --}}
                <div class="relative">
                    <div
                        class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center cursor-pointer hover:ring-2 hover:ring-gray-300 transition-all">
                        <span
                            class="text-gray-700 text-sm font-medium">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div
                        class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white">
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
