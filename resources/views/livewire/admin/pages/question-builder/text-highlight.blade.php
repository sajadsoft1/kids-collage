<div class="space-y-6">
    {{-- Template Text Input --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            متن تمپلیت
            <span class="text-xs text-gray-500">(از #...# برای مشخص کردن بخش‌های قابل انتخاب استفاده کنید)</span>
        </label>
        <x-textarea wire:model.live.debounce.500ms="templateText" rows="4"
            placeholder="مثال: این یک متن #تست است# برای بررسی ویژگی سوال. لطفا #گزینه درست# را انتخاب کنید" />
        <x-button wire:click="extractOptionsFromTemplate" icon="o-sparkles" class="btn-primary">
            استخراج گزینه‌ها از تمپلیت
        </x-button>
    </div>

    {{-- Extracted Options Preview --}}
    @if (!empty($this->getExtractedOptions()))
        <div class="p-4 bg-gray-50 rounded-lg">
            <div class="text-sm font-medium text-gray-700 mb-2">گزینه‌های استخراج شده:</div>
            <div class="space-y-1">
                @foreach ($this->getExtractedOptions() as $index => $option)
                    <div class="flex items-center gap-2 p-2 bg-white rounded">
                        <span
                            class="w-6 h-6 flex items-center justify-center text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                            {{ $index + 1 }}
                        </span>
                        <span>{{ $option }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Config --}}
    <div class="pt-6 mt-6 border-t border-gray-200">
        <h4 class="mb-3 font-medium text-gray-700">تنظیمات</h4>
        <div class="space-y-4">
            {{-- Total Selections Info --}}
            @if (!empty($this->getExtractedOptions()))
                <div class="p-3 bg-blue-50 rounded-lg">
                    <div class="text-sm text-gray-700">
                        <strong>تعداد کل بخش‌های مشخص شده:</strong> {{ count($this->getExtractedOptions()) }}
                    </div>
                </div>
            @endif

            {{-- Min Selections --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    حداقل تعداد انتخاب (تعداد بخش‌هایی که کاربر باید انتخاب کند)
                </label>
                <x-input type="number" wire:model.live="config.min_selections" min="1" :max="count($this->getExtractedOptions())"
                    placeholder="مثلاً 2" />
            </div>

            {{-- Max Selections --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    حداکثر تعداد انتخاب (اختیاری - خالی بگذارید برای نامحدود)
                </label>
                <x-input type="number" wire:model.live="config.max_selections" min="1" :max="count($this->getExtractedOptions())"
                    placeholder="مثلاً 3 یا خالی" />
            </div>

            {{-- Scoring Type --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">نوع نمره‌دهی</label>
                <x-select wire:model.live="config.scoring_type">
                    <option value="partial">نمره جزئی</option>
                    <option value="exact">دقیقاً مطابق</option>
                </x-select>
            </div>
        </div>
    </div>

    <x-alert icon="o-information-circle" class="alert-info alert-soft">
        برای استفاده از تمپلیت، متن را با #...# علامت‌گذاری کنید. برای مثال: این یک متن #تست است# برای بررسی.
    </x-alert>
</div>
