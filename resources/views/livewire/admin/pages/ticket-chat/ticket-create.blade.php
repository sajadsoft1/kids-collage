<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <form wire:submit="submit">
        <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
            @if($errors->has('general'))
                <x-alert icon="o-x-circle" class="mb-4 alert-error" :description="$errors->first('general')" />
            @endif

            {{-- Section 1: Subject + Department & Priority --}}
            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-input wire:model="title" :label="__('ticket_chat.subject')" placeholder="{{ __('ticket_chat.subject') }}" />
                </div>
                <x-select wire:model="department_id" :label="__('ticket_chat.department')" :options="$departments"
                    option-value="id" option-label="name" placeholder="{{ __('ticket_chat.department') }}" />
                <x-select wire:model="priority" :label="__('ticket_chat.priority')" :options="[
                    ['id' => 'low', 'name' => __('ticket_chat.priority_low')],
                    ['id' => 'medium', 'name' => __('ticket_chat.priority_medium')],
                    ['id' => 'high', 'name' => __('ticket_chat.priority_high')],
                    ['id' => 'urgent', 'name' => __('ticket_chat.priority_urgent')],
                ]" option-value="id" option-label="name" />
            </div>

            {{-- Section 2: Description --}}
            <div class="mt-6">
                <x-textarea wire:model="description" :label="__('validation.attributes.body')" rows="6"
                    placeholder="{{ __('validation.attributes.body') }}" />
            </div>

            {{-- Section 3: Attachments (click or drop zone) --}}
            <div class="mt-6">
                <label class="label">
                    <span class="label-text">{{ __('ticket_chat.attachments') }}</span>
                </label>
                <label for="ticket-create-files"
                    class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-base-300 bg-base-200/50 p-6 text-center transition-colors hover:border-primary hover:bg-primary/5"
                    x-data="{ over: false }"
                    x-on:dragover.prevent="over = true"
                    x-on:dragleave="over = false"
                    x-on:drop.prevent="
                        over = false;
                        const dt = new DataTransfer();
                        for (const f of $event.dataTransfer.files) dt.items.add(f);
                        $refs.fileInput.files = dt.files;
                        $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    "
                    :class="{ 'border-primary bg-primary/5': over }">
                    <input type="file" id="ticket-create-files" x-ref="fileInput" wire:model="files" multiple
                        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                        class="hidden" />
                    <x-icon name="o-paper-clip" class="size-10 text-base-content/40" />
                    <p class="text-sm text-base-content/70">{{ __('ticket_chat.add_file') }}</p>
                    <p class="text-xs text-base-content/50">{{ __('ticket_chat.max_files') }}</p>
                </label>
                @if(count($files) > 0)
                    <ul class="mt-3 flex flex-wrap gap-2">
                        @foreach($files as $index => $file)
                            @if($file)
                                <li class="badge badge-lg badge-ghost gap-1">
                                    {{ $file->getClientOriginalName() }}
                                    <button type="button" wire:click="removeFile({{ $index }})"
                                        class="btn btn-ghost btn-xs">
                                        <x-icon name="o-x-mark" class="size-3" />
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </x-card>

        <div class="mt-6 flex flex-wrap items-center gap-4" wire:loading.class="pointer-events-none opacity-70">
            <x-button type="submit" class="btn-primary btn-wide" spinner="submit" wire:loading.attr="disabled"
                :label="__('ticket_chat.send')" icon="o-paper-airplane" />
            <x-button type="reset" wire:loading.attr="disabled" :label="__('general.reset')" class="btn-ghost" />
            <span class="text-sm text-base-content/60" wire:loading wire:target="files,submit">
                {{ __('ticket_chat.uploading') }}
            </span>
        </div>
    </form>
</div>
