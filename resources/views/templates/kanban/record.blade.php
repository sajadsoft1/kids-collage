<div class="rounded-xl w-80 max-w-full shadow-lg {{ $styles['record'] }} cursor-pointer hover:shadow-xl transition-shadow duration-200"
    id="{{ $record['id'] }}" @if ($recordClickEnabled) wire:click="onRecordClick('{{ $record['id'] }}')" @endif>

    <!-- Top section with priority indicator -->
    <div class="flex items-center mb-4">
        @php
            $priorityColors = [
                'low' => 'bg-green-500',
                'medium' => 'bg-yellow-500',
                'high' => 'bg-orange-500',
                'urgent' => 'bg-red-500',
            ];
            $priorityColor = $priorityColors[$record['priority']] ?? 'bg-gray-500';
        @endphp
        <div class="h-2 w-16 {{ $priorityColor }} rounded-full"></div>
        <div class="ml-2 text-xs font-medium text-gray-500 uppercase">{{ $record['priority'] }}</div>
    </div>

    <!-- Main content section -->
    <div class="flex justify-between items-start mb-3">
        <div class="flex-1">
            <h3 class="mb-2 text-lg font-semibold text-gray-900 line-clamp-2">{{ $record['title'] }}</h3>
            @if (!empty($record['description']))
                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($record['description'], 100) }}</p>
            @endif
        </div>
    </div>

    <!-- Card type and status badges -->
    <div class="flex gap-2 items-center mb-3">
        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
            {{ ucfirst($record['card_type']) }}
        </span>
        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
            {{ ucfirst($record['status_enum']) }}
        </span>
    </div>

    <!-- Bottom section with due date and assignees -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-3 text-sm text-gray-500">
            @if (!empty($record['due_date']))
                <div class="flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="{{ $record['is_overdue'] ? 'text-red-600 font-medium' : '' }}">
                        {{ \Carbon\Carbon::parse($record['due_date'])->format('M j') }}
                    </span>
                </div>
            @endif

            @if ($record['is_overdue'])
                <span class="text-xs font-medium text-red-600">Overdue</span>
            @endif
        </div>

        <!-- Assignees avatars -->
        @if (!empty($record['assignees']))
            <div class="flex -space-x-2">
                @foreach (array_slice($record['assignees'], 0, 3) as $assignee)
                    <div
                        class="flex justify-center items-center w-6 h-6 text-xs font-medium text-gray-700 bg-gray-300 rounded-full border-2 border-white">
                        {{ strtoupper(substr($assignee, 0, 1)) }}
                    </div>
                @endforeach
                @if (count($record['assignees']) > 3)
                    <div
                        class="flex justify-center items-center w-6 h-6 text-xs font-medium text-white bg-gray-400 rounded-full border-2 border-white">
                        +{{ count($record['assignees']) - 3 }}
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>
