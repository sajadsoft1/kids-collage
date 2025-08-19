<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Helpers\Utils;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class DynamicTranslate extends Component
{
    use Toast;

    public mixed $model;
    public string $translate_modal_tab = 'fa';
    public array $form                 = [];
    public string $back_route          = '';
    public array $rules                = [];
    public string $class;

    public function mount(string $class, int $id)
    {
        $this->class      = $class;
        $this->back_route = 'admin.' . Str::kebab($class) . '.index';
        $this->model      = Utils::getEloquent($class)::find($id);
        foreach (config('app.supported_locales') as $locale) {
            foreach ($this->model->translatable as $field) {
                $this->form[$locale][$field]                   = $this->model->translationsPure()->where('key', $field)->where('locale', $locale)->first()->value ?? '';
                $this->rules['form.' . $locale . '.' . $field] = 'required|string';
            }
        }
    }

    protected function rules(): array
    {
        return $this->rules;
    }

    protected function validationAttributes(): array
    {
        return [
            'form.*.title'       => trans('validation.attributes.title'),
            'form.*.description' => trans('validation.attributes.description'),
            'form.*.body'        => trans('validation.attributes.body'),
        ];
    }

    public function submit(): void
    {
        try {
            foreach ($this->validate() as $data) {
                foreach ($data as $locale => $form) {
                    foreach ($form as $key => $value) {
                        $this->model->translationsPure()->updateOrCreate([
                            'key'    => $key,
                            'locale' => $locale,
                        ], [
                            'value' => $value,
                        ]);
                        cache()->forget(generateCacheKey($this->model::class, $this->model->id, $key, $locale));
                    }
                }
            }
            $this->success(
                title: trans('general.translation_has_updated_successfully'),
                redirectTo: route($this->back_route)
            );
        } catch (ValidationException $e) {
            foreach (config('app.supported_locales') as $locale) {
                foreach ($e->validator->errors()->toArray() as $key => $errorMessage) {
                    $this->addError($key, $errorMessage[0]); // Add the error to the error bag
                }

                foreach ($e->validator->errors()->toArray() as $key => $errorMessage) {
                    Str::contains($key, '.' . $locale . '.') ? $this->translate_modal_tab = $locale : null;

                    break;
                }
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.shared.dynamic-translate', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.' . $this->class . '.index'), 'label' => trans('general.page.index.title', ['model' => trans($this->class . '.model')])],
                ['label' => $this->model->title],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.' . $this->class . '.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
