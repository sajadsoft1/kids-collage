<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\ContactUs;

use App\Actions\ContactUs\StoreContactUsAction;
use App\Actions\ContactUs\UpdateContactUsAction;
use App\Enums\YesNoEnum;
use App\Models\ContactUs;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class ContactUsUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public ContactUs $model;
    public string $name;
    public string $email;
    public string $mobile;
    public string $comment;
    public ?string $admin_note;
    public int $follow_up = YesNoEnum::NO->value;

    public bool $published = false;

    public function mount(ContactUs $contactUs): void
    {
        $this->model = $contactUs;
        if ($this->model->id) {
            $this->name = $this->model->name;
            $this->email = $this->model->email;
            $this->mobile = $this->model->mobile;
            $this->comment = $this->model->comment;
            $this->admin_note = $this->model->admin_note;
            $this->follow_up = $this->model->follow_up->value;
        }
    }

    protected function rules(): array
    {
        return [
            'admin_note' => 'nullable|string',
            'follow_up' => 'required|boolean',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        if ($this->model->id) {
            try {
                UpdateContactUsAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('contactUs.model')]),
                    redirectTo: route('admin.contact-us.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreContactUsAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('contactUs.model')]),
                    redirectTo: route('admin.contact-us.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.contactUs.contactUs-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.contact-us.index'), 'label' => trans('general.page.index.title', ['model' => trans('contactUs.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('contactUs.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.contact-us.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
