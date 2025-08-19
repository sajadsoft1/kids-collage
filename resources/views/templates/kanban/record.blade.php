<div class="rounded-xl w-80 max-w-full shadow-lg {{ $styles['record'] }}" id="{{ $record['id'] }}"
    @if ($recordClickEnabled) wire:click="onRecordClick('{{ $record['id'] }}')" @endif>

    <!-- Top section with status bar -->
    <div class="flex items-center mb-4">
        <div class="h-2 w-16 bg-green-500 rounded-full"></div>
    </div>

    <!-- Main content section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 space-x-reverse">
            <span class="text-xl font-medium"> {{ $record['title'] }}</span>
        </div>
    </div>

    <!-- Bottom section with status icons and avatar -->
    <div class="flex justify-between items-center mt-4">
        <div class="flex items-center space-x-4 space-x-reverse text-gray-400 text-sm">
            <!-- View icon -->
            <div class="flex items-center space-x-1 space-x-reverse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <!-- Date icon and text -->
            <div class="flex items-center space-x-1 space-x-reverse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Aug 23</span>
            </div>
            <!-- Menu icon -->
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </div>
        </div>
        <!-- User avatar -->
        @if (Arr::get($record, 'userAvatar'))
            <div class="w-8 h-8 rounded-full overflow-hidden">
                <img src="{{ $record['userAvatar'] }}" alt="User Avatar" class="w-full h-full object-cover">
            </div>
        @endif
    </div>

</div>
