@php
    use App\Enums\TicketDepartmentEnum;
    use App\Enums\TicketPriorityEnum;
    use App\Enums\TicketStatusEnum;
    use App\Helpers\Constants;
@endphp
<div class="flex relative flex-1 border border-slate-200 bg-base-100" x-data="{ msgSidebarOpen: true, message: '' }">

    <!-- Messages sidebar -->
    <div id="messages-sidebar"
        class="absolute top-0 bottom-0 z-20 w-full transition-transform duration-200 ease-in-out border-e border-slate-200 md:w-auto md:static md:top-auto md:bottom-auto -me-px md:translate-x-0"
        :class="msgSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div
            class="sticky bg-base-100 top-16 overflow-x-hidden overflow-y-auto no-scrollbar shrink-0 border-r border-slate-200 dark:border-slate-700 md:w-72 xl:w-80 h-[calc(100vh-64px)]">

            <!-- #Marketing group -->
            <div>
                <!-- Group header -->
                <div class="sticky top-0 z-10 bg-base-100">
                    <div class="flex items-center px-5 h-16 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between items-center w-full">
                            <!-- Channel menu -->
                            <x-select class="select-sm" wire:model.live="filter_department" :options="array_merge(
                                [['label' => trans('ticket.enum.department.all'), 'value' => '']],
                                TicketDepartmentEnum::formatedCases(),
                            )"
                                option-label="label" option-value="value" icon="o-user" />


                            <!-- Edit button -->
                            <x-button :tooltip-bottom="trans('ticket.page.new_ticket')"
                                class="btn-sm btn-circle bg-base-100 text-slate-400 shrink-0 ms-2" icon="o-plus"
                                wire:click="$toggle('show_new_modal')" />

                        </div>
                    </div>
                </div>
                <!-- Group body -->
                <div class="px-5 py-4">
                    <!-- Search form -->
                    <x-input :placeholder="trans('datatable.search')" wire:model.live.debounce="search_ticket" />

                    <!-- Direct messages -->
                    <div class="pb-3 mt-4">
                        <div class="mb-3 text-xs font-semibold uppercase text-slate-400">Direct messages</div>
                        <ul class="mb-6">
                            @foreach ($tickets as $ticket)
                                <li class="-mx-2">
                                    <button @class([
                                        'flex items-center justify-between w-full p-2 rounded',
                                        'bg-indigo-100 dark:bg-indigo-900' => $ticket->id === $selectedTicketId,
                                    ]) wire:click="selectTicket({{ $ticket->id }})"
                                        @click="msgSidebarOpen = false; $refs.contentarea.scrollTop = 99999999;">
                                        <div class="flex items-center truncate">
                                            <img class="w-8 h-8 rounded-full me-2"
                                                src="{{ $ticket->user->getFirstMediaUrl('avatar', Constants::RESOLUTION_512_SQUARE) }}"
                                                width="32" height="32" alt="User 01" />
                                            <div class="truncate">
                                                <span
                                                    class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $ticket->user->full_name }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center ms-2">
                                            @if ($ticket->unread_messages_count > 0)
                                                <div
                                                    class="inline-flex px-2 text-xs font-medium leading-5 text-center text-white bg-indigo-400 rounded-full">
                                                    {{ $ticket->unread_messages_count }}
                                                </div>
                                            @endif
                                        </div>
                                    </button>
                                </li>
                            @endforeach

                        </ul>

                    </div>
                    <div
                        class="fixed right-0 bottom-0 left-0 py-2 border-t bg-base-100 border-slate-200 dark:border-slate-700">
                        <div class="flex justify-center space-x-2">
                            @if ($tickets->onFirstPage())
                                <span class="px-3 py-1 text-slate-400">«</span>
                            @else
                                <button wire:click="previousPage('page')"
                                    class="px-3 py-1 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">«</button>
                            @endif

                            @php
                                $currentPage = $tickets->currentPage();
                                $lastPage = $tickets->lastPage();
                                $startPage = max(1, $currentPage - 1);
                                $endPage = min($lastPage, $currentPage + 1);
                            @endphp

                            @if ($startPage > 1)
                                <button wire:click="gotoPage(1, 'page')"
                                    class="px-3 py-1 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">1</button>
                                @if ($startPage > 2)
                                    <span class="px-3 py-1 text-slate-400">...</span>
                                @endif
                            @endif

                            @for ($i = $startPage; $i <= $endPage; $i++)
                                @if ($i == $currentPage)
                                    <span class="px-3 py-1 text-white bg-indigo-600 rounded">{{ $i }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $i }}, 'page')"
                                        class="px-3 py-1 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">{{ $i }}</button>
                                @endif
                            @endfor

                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <span class="px-3 py-1 text-slate-400">...</span>
                                @endif
                                <button wire:click="gotoPage({{ $lastPage }}, 'page')"
                                    class="px-3 py-1 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">{{ $lastPage }}</button>
                            @endif

                            @if ($tickets->hasMorePages())
                                <button wire:click="nextPage('page')"
                                    class="px-3 py-1 text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">»</button>
                            @else
                                <span class="px-3 py-1 text-slate-400">»</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Messages body -->
    @if ($this->selected_ticket)
        <div class="flex flex-col justify-between transition-transform duration-300 ease-in-out grow md:translate-x-0"
            :class="msgSidebarOpen ? 'translate-x-1/3' : 'translate-x-0'">

            <!-- Header -->
            <div class="sticky top-16">
                <div
                    class="flex justify-between items-center px-4 h-16 border-b rtl:flex-row-reverse bg-base-100 border-slate-200 dark:border-slate-700 sm:px-6 md:px-5">
                    <!-- People -->
                    <div class="flex items-center rtl:flex-row-reverse rtl:justify-end">
                        <!-- Close button -->
                        <button class="md:hidden text-slate-400 hover:text-slate-500 ms-4"
                            @click.stop="msgSidebarOpen = !msgSidebarOpen" aria-controls="messages-sidebar"
                            :aria-expanded="msgSidebarOpen">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                            </svg>
                        </button>
                        <!-- People list -->
                        <div class="flex -space-x-3 -ms-px">
                            {{--  --}}
                        </div>
                    </div>
                    <!-- Buttons on the right side -->
                    <div class="flex">
                        <x-button @class([
                            'btn-sm btn-ghost p-1.5 rounded border border-slate-200 dark:border-slate-700 hover:border-slate-300 shadow-sm me-2 ',
                            'text-red-500' => $this->selected_ticket->status->value === 'close',
                        ]) wire:click="toogleTicketStatus" :icon="$this->selected_ticket->status->value === 'close' ? 'o-lock-closed' : 'o-lock-open'"
                            :tooltip-bottom="$this->selected_ticket->status->value === 'open' ? trans('ticket.page.ticket_status_change_to_close'):trans('ticket.page.ticket_status_change_to_open')" />
                        <x-button
                            class="p-1.5 rounded border shadow-sm btn-sm btn-ghost border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 me-2"
                            icon="o-information-circle" wire:click="$toggle('show_ticket_info')" :tooltip-bottom="trans('ticket.page.ticket_details')" />

                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="grow overflow-y-scroll px-4 sm:px-6 md:px-5 py-6 max-h-[calc(92vh-128px)]" x-data
                x-ref="messagesContainer" x-init="let container = $el;
                container.scrollTop = container.scrollHeight;

                container.addEventListener('scroll', function() {
                    if (container.scrollTop === 0) {
                        $wire.loadMoreMessages();
                    }
                });">


                @forelse($messages as $message)
                    <div
                        class="flex items-start mb-4 last:mb-0 {{ $message->user_id !== auth()->id() ? 'flex-row-reverse' : '' }}">
                        <img class="rounded-full {{ $message->user_id !== auth()->id() ? 'ms-4' : 'me-4' }}"
                            src="{{ $message->user->getFirstMediaUrl('avatar', Constants::RESOLUTION_512_SQUARE) }}"
                            width="40" height="40" alt="User 02" />
                        <div class="max-w-[70%]">
                            @if ($message->getFirstMedia('gallery'))
                                @php
                                    $media = $message->getFirstMedia('gallery');
                                    $isImage = in_array($media->mime_type, [
                                        'image/jpeg',
                                        'image/png',
                                        'image/gif',
                                        'image/webp',
                                    ]);
                                    $isVideo = in_array($media->mime_type, ['video/mp4', 'video/webm', 'video/ogg']);
                                    $isAudio = in_array($media->mime_type, [
                                        'audio/mpeg',
                                        'audio/mp3',
                                        'audio/ogg',
                                        'audio/wav',
                                        'audio/x-m4a',
                                    ]);
                                @endphp

                                <div class="mb-2">
                                    @if ($isImage)
                                        <img src="{{ $media->getUrl() }}" alt="Image"
                                            class="max-w-xs rounded-lg shadow-md transition-opacity cursor-pointer hover:opacity-90"
                                            @click="window.open('{{ $media->getUrl() }}', '_blank')">
                                    @elseif($isVideo)
                                        <video controls class="max-w-xs rounded-lg shadow-md">
                                            <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif($isAudio)
                                        <audio controls class="w-full">
                                            <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @else
                                        <a href="{{ $media->getUrl() }}" download="{{ $media->file_name }}"
                                            class="inline-flex items-center px-4 py-2 rounded-lg transition-colors bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700">
                                            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            {{ $media->file_name }}
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if ($message->message)
                                <div
                                    class="text-sm {{ $message->user_id !== auth()->id() ? 'bg-indigo-600 rounded-tr-none' : 'bg-indigo-500 rounded-tl-none' }} text-white p-3 rounded-lg border border-transparent shadow-md mb-1">
                                    {{ $message->message }}
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <div class="text-xs font-medium text-slate-500">
                                    {{ jdate($message->created_at)->format('Y/m/d H:i') }}</div>
                                @if ($message->read_by)
                                    <div class="flex">
                                        <svg class="w-3 h-3 fill-current shrink-0 text-slate-400" viewBox="0 0 12 12">
                                            <path
                                                d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                                        </svg>
                                        <svg class="-ml-1 w-3 h-3 fill-current shrink-0 text-slate-400"
                                            viewBox="0 0 12 12">
                                            <path
                                                d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="flex">
                                        <svg class="w-3 h-3 fill-current shrink-0 text-slate-400" viewBox="0 0 12 12">
                                            <path
                                                d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col justify-center items-center py-12">
                        <div class="mb-4 w-24 h-24 text-slate-400">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-slate-800 dark:text-slate-200">
                            {{ trans('ticket.empty_state.title') }}
                        </h3>
                        <p class="max-w-sm text-sm text-center text-slate-500">
                            {{ trans('ticket.empty_state.description') }}
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0">
                <div
                    class="flex justify-between items-center px-4 h-16 border-t bg-base-100 border-slate-200 dark:border-slate-700 sm:px-6 md:px-5">
                    <!-- Plus button -->
                    <x-button
                        class="bg-transparent border-none hover:border-none shrink-0 text-slate-400 hover:text-slate-500 me-3"
                        :disabled="$this->uploading || $this->selected_ticket->status === TicketStatusEnum::CLOSE">
                        <input type="file" class="hidden" wire:model="file" x-ref="fileInput">
                        <span class="sr-only">Add</span>
                        <div class="relative">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" @click="$refs.fileInput.click()"
                                wire:loading.remove wire:target="file">
                                <path
                                    d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12C23.98 5.38 18.62.02 12 0zm6 13h-5v5h-2v-5H6v-2h5V6h2v5h5v2z" />
                            </svg>
                            <svg class="w-6 h-6 animate-spin fill-current" viewBox="0 0 24 24" wire:loading
                                wire:target="file">
                                <path
                                    d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm4-8c0 2.209-1.791 4-4 4s-4-1.791-4-4 1.791-4 4-4 4 1.791 4 4z" />
                            </svg>
                        </div>
                    </x-button>
                    <!-- Message input -->
                    <div class="grow me-3">
                        <x-textarea
                            class="w-full bg-base-100 border-transparent focus:bg-base-300 focus:border-slate-300 resize-none overflow-hidden h-[3em] min-h-[3em]"
                            wire:model="message" x-model="message" :rows="1" :cols="1"
                            :placeholder="trans('core.search')" :disabled="$this->selected_ticket->status === TicketStatusEnum::CLOSE" x-on:keydown.ctrl.enter="$wire.submitNewMessage()" />
                    </div>
                    <x-button :disabled="$this->selected_ticket->status === TicketStatusEnum::CLOSE" class="btn-primary" type="submit" :label="trans('general.submit')"
                        x-bind:disabled="message === '' && !$this->file" wire:loading.attr="disabled"
                        wire:target="file" @click="$refs.contentarea.scrollTop = 99999999;"
                        wire:click.prevent="submitNewMessage" />
                </div>
            </div>

        </div>
    @else
        <div
            class="flex flex-col justify-center items-center transition-transform duration-300 ease-in-out grow md:translate-x-0">
            لطفا یک تیکت را انتخاب کنید
        </div>
    @endif

    <x-drawer wire:model="show_new_modal" :title="trans('_menu.ticket.new')" separator with-close-button
        class="relative w-11/12 lg:w-1/3">
        <div class="grid grid-cols-1 gap-4 mb-auto">
            <x-input :label="trans('validation.attributes.subject')" wire:model="newTicket.subject" required />

            <x-select :label="trans('validation.attributes.department')" wire:model="newTicket.department" :options="TicketDepartmentEnum::formatedCases()" option-value="value"
                option-label="label" required />

            <x-select :label="trans('validation.attributes.priority')" wire:model="newTicket.priority" :options="TicketPriorityEnum::formatedCases()" option-value="value"
                option-label="label" required />

            <x-textarea :label="trans('validation.attributes.body')" wire:model="newTicket.body" required />


        </div>
        <div class="flex sticky bottom-2 gap-x-1 justify-between items-center mt-10">
            <x-button :label="trans('general.submit')" class="btn-primary btn-wide" icon="o-check"
                wire:click.prevent="submitNewTicket;" />
            <x-button :label="trans('general.cancel')" @click="$wire.show_new_modal = false" />
        </div>
    </x-drawer>

    @if ($this->selected_ticket)
        <x-drawer wire:model="show_ticket_info" :title="$this->selected_ticket->key" separator with-close-button
            class="relative w-11/12 lg:w-1/3">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900 text-sm/6">{{ trans('validation.attributes.subject') }}</dt>
                    <dd class="mt-1 text-gray-700 text-sm/6 sm:col-span-2 sm:mt-0">
                        {{ $this->selected_ticket->subject }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900 text-sm/6">{{ trans('validation.attributes.department') }}
                    </dt>
                    <dd class="mt-1 text-gray-700 text-sm/6 sm:col-span-2 sm:mt-0">
                        {{ $this->selected_ticket->department->title() }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900 text-sm/6">{{ trans('validation.attributes.priority') }}</dt>
                    <dd class="mt-1 text-gray-700 text-sm/6 sm:col-span-2 sm:mt-0">
                        {{ $this->selected_ticket->priority->title() }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900 text-sm/6">{{ trans('validation.attributes.created_at') }}
                    </dt>
                    <dd class="mt-1 text-gray-700 text-sm/6 sm:col-span-2 sm:mt-0">
                        {{ jdate($this->selected_ticket->created_at) }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="font-medium text-gray-900 text-sm/6">{{ trans('validation.attributes.status') }}</dt>
                    <dd @class([
                        'mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0',
                        '!text-red-500' =>
                            $this->selected_ticket->status === TicketStatusEnum::CLOSE,
                    ])>{{ $this->selected_ticket->status->title() }}</dd>
                </div>
                @if ($this->selected_ticket->status === TicketStatusEnum::CLOSE)
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="font-medium text-gray-900 text-sm/6">{{ trans('validation.attributes.closed_by') }}
                        </dt>
                        <dd class="mt-1 text-gray-700 text-sm/6 sm:col-span-2 sm:mt-0">
                            {{ $this->selected_ticket->closeBy->full_name }}</dd>
                    </div>
                @endif
            </dl>
        </x-drawer>
    @endif
</div>
