@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="space-y-6">
            {{-- Basic Info Card --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">اطلاعات پایه</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Type --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            نوع سوال <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="type"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            {{ $isEditMode ? 'disabled' : '' }}>
                            <option value="">انتخاب کنید</option>
                            @foreach ($types as $typeOption)
                                <option value="{{ $typeOption->value }}">
                                    {{ $typeOption->label() }} - {{ $typeOption->description() }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">دسته‌بندی</label>
                        <select wire:model.live="category_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">انتخاب کنید</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">موضوع</label>
                        <select wire:model="subject_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">انتخاب کنید</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Competency --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">شایستگی</label>
                        <select wire:model="competency_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">انتخاب کنید</option>
                            @foreach ($competencies as $competency)
                                <option value="{{ $competency->id }}">{{ $competency->name }}</option>
                            @endforeach
                        </select>
                        @error('competency_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Difficulty --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">سطح دشواری</label>
                        <select wire:model="difficulty"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">انتخاب کنید</option>
                            @foreach ($difficulties as $diff)
                                <option value="{{ $diff->value }}">{{ $diff->label() }}</option>
                            @endforeach
                        </select>
                        @error('difficulty')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Default Score --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            نمره پیش‌فرض <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.25" wire:model="default_score"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('default_score')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tags --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">برچسب‌ها</label>
                        <input type="text" wire:model="tags" placeholder="برچسب‌ها را با کاما جدا کنید"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('tags')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Question Content Card --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">محتوای سوال</h3>

                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        عنوان سوال <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="title" rows="2"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="سوال خود را وارد کنید..."></textarea>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Body --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">متن اضافی</label>
                    <textarea wire:model="body" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="توضیحات اضافی، متن مرجع، یا داده‌های سوال..."></textarea>
                    @error('body')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Explanation --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">توضیحات پاسخ</label>
                    <textarea wire:model="explanation" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="توضیح پاسخ صحیح و دلیل غلط بودن سایر گزینه‌ها..."></textarea>
                    @error('explanation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Type-specific Options --}}
            @if ($type)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">گزینه‌ها و تنظیمات</h3>

                    @if ($type === 'single_choice')
                        <livewire:question-builder.single-choice :options="$options" :config="$config"
                            :key="'single-choice-' . ($question?->id ?? 'new')" />
                    @elseif($type === 'multiple_choice')
                        <livewire:question-builder.multiple-choice :options="$options" :config="$config"
                            :key="'multiple-choice-' . ($question?->id ?? 'new')" />
                    @elseif($type === 'ordering')
                        <livewire:question-builder.ordering :options="$options" :config="$config" :key="'ordering-' . ($question?->id ?? 'new')" />
                    @endif
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('questions.index') }}"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    انصراف
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ $isEditMode ? 'ذخیره تغییرات' : 'ایجاد سوال' }}
                </button>
            </div>
        </div>
    </x-card>

    <x-admin.shared.form-actions />
</form>
