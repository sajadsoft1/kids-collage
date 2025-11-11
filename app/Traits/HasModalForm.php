<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

/**
 * Trait برای مدیریت فرم مودال در PowerGrid Tables
 *
 * این Trait امکان ایجاد و ویرایش رکوردها را در یک مودال فراهم می‌کند
 */
trait HasModalForm
{
    // Modal properties
    public bool $showModal  = false;
    public ?int $editingId  = null;
    public array $modalData = [];

    /**
     * تعریف فیلدهای مودال
     *
     * @return array
     *
     * مثال:
     * [
     *     ['name' => 'title', 'type' => 'input', 'label' => 'عنوان', 'required' => true],
     *     ['name' => 'description', 'type' => 'textarea', 'label' => 'توضیحات'],
     *     ['name' => 'published', 'type' => 'toggle', 'label' => 'وضعیت انتشار', 'default' => false],
     * ]
     */
    abstract protected function modalFields(): array;

    /** دریافت کلاس Model */
    abstract protected function getModelClass(): string;

    /** دریافت نام Action برای ذخیره */
    abstract protected function getStoreActionClass(): string;

    /** دریافت نام Action برای بروزرسانی */
    abstract protected function getUpdateActionClass(): string;

    /** دریافت نام مدل برای نمایش در پیام‌ها */
    abstract protected function getModelTranslationKey(): string;

    /** باز کردن مودال برای ایجاد رکورد جدید */
    public function openCreateModal(): void
    {
        $this->resetModalData();
        $this->editingId = null;
        $this->showModal = true;
    }

    /** باز کردن مودال برای ویرایش رکورد */
    #[On('openEditModal')]
    public function openEditModal(int $id): void
    {
        $this->resetModalData();
        $modelClass = $this->getModelClass();
        $model      = $modelClass::findOrFail($id);

        $this->editingId = $id;

        // پر کردن داده‌ها از مدل
        foreach ($this->modalFields() as $field) {
            $fieldName = $field['name'];
            $value     = $model->{$fieldName};

            // اگر فیلد یک Enum باشه، مقدارش رو بگیر
            if (is_object($value) && enum_exists($value::class)) {
                $value = $value->value;
            }

            $this->modalData[$fieldName] = $value;
        }

        $this->showModal = true;
    }

    /** ریست کردن داده‌های مودال */
    protected function resetModalData(): void
    {
        $this->modalData = [];

        foreach ($this->modalFields() as $field) {
            $this->modalData[$field['name']] = $field['default'] ?? $this->getDefaultValue($field['type']);
        }
    }

    /** دریافت مقدار پیش‌فرض بر اساس نوع فیلد */
    protected function getDefaultValue(string $type): mixed
    {
        return match ($type) {
            'toggle', 'checkbox' => false,
            'number' => 0,
            'select' => null,
            default => '',
        };
    }

    /** قوانین اعتبارسنجی */
    protected function rules(): array
    {
        $rules = [];

        foreach ($this->modalFields() as $field) {
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            }

            if (isset($field['rules'])) {
                if (is_array($field['rules'])) {
                    $fieldRules = array_merge($fieldRules, $field['rules']);
                } else {
                    $fieldRules[] = $field['rules'];
                }
            } else {
                // قوانین پیش‌فرض بر اساس نوع فیلد
                $fieldRules[] = match ($field['type']) {
                    'toggle', 'checkbox' => 'boolean',
                    'number' => 'numeric',
                    'email' => 'email',
                    default => 'string',
                };
            }

            $rules['modalData.' . $field['name']] = implode('|', $fieldRules);
        }

        return $rules;
    }

    /** ذخیره یا بروزرسانی رکورد */
    public function saveModal(): void
    {
        try {
            $validated = $this->validate();
            $payload   = $validated['modalData'];
        } catch (\Illuminate\Validation\ValidationException $e) {
            // تبدیل پیام‌های خطا از فرمت modalData.title به modalData.title
            foreach ($e->errors() as $key => $messages) {
                $this->addError($key, $messages[0]);
            }

            return;
        }

        if ($this->editingId) {
            // بروزرسانی
            $modelClass   = $this->getModelClass();
            $model        = $modelClass::findOrFail($this->editingId);
            $updateAction = $this->getUpdateActionClass();
            $updateAction::run($model, $payload);

            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans($this->getModelTranslationKey())])
            );
        } else {
            // ایجاد
            $storeAction = $this->getStoreActionClass();
            $storeAction::run($payload);

            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans($this->getModelTranslationKey())])
            );
        }

        $this->showModal = false;
        $this->resetModalData();

        // رفرش جدول PowerGrid
        if (isset($this->tableName)) {
            $this->dispatch('pg:eventRefresh-' . $this->tableName);
        }
    }

    /** بستن مودال */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetModalData();
    }

    /** دریافت عنوان مودال */
    public function getModalTitle(): string
    {
        $modelName = trans($this->getModelTranslationKey());

        if ($this->editingId) {
            return trans('general.page.edit.title_simple', ['model' => $modelName]);
        }

        return trans('general.page.create.title', ['model' => $modelName]);
    }

    /**
     * دریافت نام‌های attribute برای پیام‌های اعتبارسنجی
     * این متد آخرین بخش از کلیدهای nested را به عنوان attribute مشخص می‌کند
     *
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return $this->generateAttributes();
    }

    /**
     * تولید نام‌های attribute برای کلیدهای nested در validation
     *
     * @return array<string, string>
     */
    protected function generateAttributes(): array
    {
        return collect($this->rules())->filter(function ($item, $key) {
            return Str::contains($key, '.');
        })->map(function ($item, $key) {
            // دریافت آخرین بخش از کلید (مثلاً 'title' از 'modalData.title')
            $lastSegment = last(explode('.', $key));

            // تلاش برای دریافت ترجمه
            $translationKey = 'validation.attributes.' . $lastSegment;
            $translated     = trans($translationKey);

            // اگر ترجمه برابر با کلید بود (یعنی ترجمه پیدا نشد)، از کلید اصلی استفاده کن
            return ($translated !== $translationKey) ? $translated : $lastSegment;
        })->toArray();
    }
}
