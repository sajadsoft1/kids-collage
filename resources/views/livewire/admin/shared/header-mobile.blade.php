<x-nav sticky class="h-16 border-b shadow-lg backdrop-blur-md lg:hidden bg-base-100/80 border-base-content/10">
    <x-slot:brand>
        <div class="flex gap-3 items-center">
            <div
                    class="flex justify-center items-center w-8 h-8 bg-gradient-to-r rounded-lg from-primary to-secondary">
                <x-icon name="o-cube" class="w-5 h-5 text-white"/>
            </div>
            <span class="text-lg font-bold">Karnoweb</span>
        </div>
    </x-slot:brand>
    <x-slot:actions>
        <div class="flex gap-2 items-center">
            <x-theme-toggle/>
            <label for="main-drawer" class="lg:hidden">
                <x-icon name="o-bars-3" class="w-6 h-6 cursor-pointer"/>
            </label>
        </div>
    </x-slot:actions>
</x-nav>