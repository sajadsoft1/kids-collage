<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-tabs wire:model="selectedTab" active-class="bg-primary rounded !text-base-content"
        label-class="px-4 py-3 font-semibold" label-div-class="p-2 rounded bg-base-100">

        {{-- Basic info tab --}}
        <x-tab name="basic-tab" label="{{ trans('general.page_sections.data') }}" icon="o-information-circle">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="grid grid-cols-1 col-span-2 gap-4">
                    <x-card :title="trans('certificateTemplate.page.title')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4">
                            <x-input :label="trans('certificateTemplate.page.title')" wire:model.live.debounce.300ms="title" required />
                            <x-input :label="trans('certificateTemplate.page.slug')" wire:model.live.debounce.300ms="slug" :hint="trans('general.optional')" />
                            <x-input :label="trans('certificateTemplate.page.institute_name')" wire:model.live.debounce.300ms="institute_name" />
                        </div>
                    </x-card>
                </div>
                <div class="col-span-1">
                    <x-card shadow separator progress-indicator="submit">
                        <x-select :label="trans('certificateTemplate.page.layout')" wire:model="layout" :options="$layoutOptions" option-label="name"
                            option-value="id" :placeholder="trans('general.please_select_an_option')" />
                        <x-toggle :label="trans('certificateTemplate.page.is_default')" wire:model="is_default" right class="mt-4" />
                    </x-card>
                </div>
            </div>
        </x-tab>

        {{-- Design tab: header, body, footer text with placeholders --}}
        <x-tab name="design-tab" label="{{ trans('certificateTemplate.page.header_text') }}" icon="o-document-text">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="grid grid-cols-1 col-span-2 gap-4">
                    <x-card :title="trans('certificateTemplate.page.header_text')" shadow separator progress-indicator="submit">
                        <x-textarea :label="trans('certificateTemplate.page.header_text')" wire:model.live.debounce.300ms="header_text" rows="3" :hint="trans('certificateTemplate.placeholder_hint')" />
                    </x-card>
                    <x-card :title="trans('certificateTemplate.page.body_text')" shadow separator progress-indicator="submit">
                        <x-textarea :label="trans('certificateTemplate.page.body_text')" wire:model.live.debounce.300ms="body_text" rows="5" />
                    </x-card>
                    <x-card :title="trans('certificateTemplate.page.footer_text')" shadow separator progress-indicator="submit">
                        <x-textarea :label="trans('certificateTemplate.page.footer_text')" wire:model.live.debounce.300ms="footer_text" rows="2" />
                    </x-card>
                </div>
                <div class="col-span-1">
                    <x-card :title="trans('certificateTemplate.page.preview')" shadow separator class="sticky top-16">
                        <p class="text-sm text-base-content/70 mb-2">{{ trans('certificateTemplate.page.preview') }}:
                        </p>
                        <ul class="space-y-1 text-sm list-disc list-inside">
                            @foreach ($placeholders as $key)
                                <li><code class="px-1 rounded bg-base-200">{{ $key }}</code>
                                    {{ trans('certificateTemplate.placeholders.' . $key) }}</li>
                            @endforeach
                        </ul>
                    </x-card>
                </div>
            </div>
        </x-tab>

        {{-- Media tab: logo, background, signature --}}
        <x-tab name="media-tab" label="{{ trans('certificateTemplate.page.logo') }}" icon="o-photo">
            <x-card shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <x-file wire:model="logo" :label="trans('certificateTemplate.page.logo')"
                            accept="image/jpeg,image/png,image/gif,image/webp" />
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo"
                                class="mt-2 rounded max-h-24 object-contain" />
                        @endif
                    </div>
                    <div>
                        <x-file wire:model="background" :label="trans('certificateTemplate.page.background')"
                            accept="image/jpeg,image/png,image/gif,image/webp" />
                        @if ($backgroundUrl)
                            <img src="{{ $backgroundUrl }}" alt="Background"
                                class="mt-2 rounded max-h-24 object-cover w-full" />
                        @endif
                    </div>
                    <div>
                        <x-file wire:model="signature" :label="trans('certificateTemplate.page.signature')"
                            accept="image/jpeg,image/png,image/gif,image/webp" />
                        @if ($signatureUrl)
                            <img src="{{ $signatureUrl }}" alt="Signature"
                                class="mt-2 rounded max-h-24 object-contain" />
                        @endif
                    </div>
                </div>
            </x-card>
        </x-tab>

        {{-- Preview tab: full certificate-style preview --}}
        <x-tab name="preview-tab" label="{{ trans('certificateTemplate.page.preview') }}" icon="o-eye">
            <x-card :title="trans('certificateTemplate.page.preview')" shadow separator>
                {{-- Text-only preview --}}
                <div class="space-y-4 p-4 rounded-lg bg-base-200/50 border border-base-300 mb-6">
                    @if ($header_text)
                        <div><strong>{{ __('certificateTemplate.page.header_text') }}:</strong>
                            <p class="mt-1">{{ $this->previewText($header_text) }}</p>
                        </div>
                    @endif
                    @if ($body_text)
                        <div><strong>{{ __('certificateTemplate.page.body_text') }}:</strong>
                            <p class="mt-1 whitespace-pre-wrap">{{ $this->previewText($body_text) }}</p>
                        </div>
                    @endif
                    @if ($footer_text)
                        <div><strong>{{ __('certificateTemplate.page.footer_text') }}:</strong>
                            <p class="mt-1">{{ $this->previewText($footer_text) }}</p>
                        </div>
                    @endif
                    @if (empty($header_text) && empty($body_text) && empty($footer_text))
                        <p class="text-base-content/70">{{ __('general.optional') }}</p>
                    @endif
                </div>
                {{-- Full certificate preview (layout like final PDF) --}}
                <p class="text-sm text-base-content/70 mb-2">{{ trans('certificateTemplate.page.preview') }} ({{ __('general.show') }}):</p>
                <div class="relative max-w-2xl mx-auto p-6 md:p-10 min-h-[400px] rounded-lg border-2 border-base-300 bg-base-100 shadow-inner overflow-hidden">
                    @if ($backgroundUrl ?? null)
                        <img src="{{ $backgroundUrl }}" alt="" class="absolute inset-0 z-0 w-full h-full object-cover opacity-90" />
                    @endif
                    <div class="relative z-10">
                        @if ($logoUrl ?? null)
                            <img src="{{ $logoUrl }}" alt="Logo" class="max-w-[120px] max-h-[120px] mb-4 object-contain" />
                        @endif
                        @if ($institute_name ?? null)
                            <div class="text-sm mb-2">{{ $institute_name }}</div>
                        @else
                            <div class="text-sm mb-2 text-base-content/70">{{ config('app.name') }}</div>
                        @endif
                        @if ($header_text)
                            <div class="text-center text-xl font-bold mb-4">{{ $this->previewText($header_text) }}</div>
                        @endif
                        @if ($body_text)
                            <div class="text-center text-base leading-relaxed mb-4 whitespace-pre-wrap">{{ $this->previewText($body_text) }}</div>
                        @endif
                        @if ($signatureUrl ?? null)
                            <div class="text-center mt-4">
                                <img src="{{ $signatureUrl }}" alt="Signature" class="inline-block max-w-[180px] max-h-[80px] object-contain" />
                            </div>
                        @endif
                        @if ($footer_text)
                            <div class="text-center text-sm mt-6">{{ $this->previewText($footer_text) }}</div>
                        @endif
                    </div>
                </div>
            </x-card>
        </x-tab>
    </x-tabs>

    <x-admin.shared.form-actions />
</form>
