<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CertificateTemplate;

use App\Actions\CertificateTemplate\StoreCertificateTemplateAction;
use App\Actions\CertificateTemplate\UpdateCertificateTemplateAction;
use App\Helpers\Constants;
use App\Models\CertificateTemplate;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

/**
 * CertificateTemplateUpdateOrCreate Component
 *
 * Handles creating and updating certificate templates with layout,
 * text placeholders, and media (logo, background, signature).
 */
class CertificateTemplateUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public CertificateTemplate $model;

    public string $selectedTab = 'basic-tab';

    public string $title = '';

    public string $slug = '';

    public bool $is_default = false;

    public string $layout = CertificateTemplate::LAYOUT_CLASSIC;

    public ?string $header_text = null;

    public ?string $body_text = null;

    public ?string $footer_text = null;

    public ?string $institute_name = null;

    public $logo;

    public $background;

    public $signature;

    public function mount(CertificateTemplate $certificateTemplate): void
    {
        $this->model = $certificateTemplate;

        if ($this->model->exists) {
            $this->title = $this->model->title;
            $this->slug = $this->model->slug;
            $this->is_default = $this->model->is_default;
            $this->layout = $this->model->layout;
            $this->header_text = $this->model->header_text;
            $this->body_text = $this->model->body_text;
            $this->footer_text = $this->model->footer_text;
            $this->institute_name = $this->model->institute_name;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'is_default' => 'boolean',
            'layout' => 'required|string|in:classic,minimal,custom',
            'header_text' => 'nullable|string',
            'body_text' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'institute_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug ?: \Illuminate\Support\Str::slug($this->title),
            'is_default' => $this->is_default,
            'layout' => $this->layout,
            'header_text' => $this->header_text,
            'body_text' => $this->body_text,
            'footer_text' => $this->footer_text,
            'institute_name' => $this->institute_name,
            'logo' => $this->logo,
            'background' => $this->background,
            'signature' => $this->signature,
        ];

        try {
            if ($this->model->exists) {
                UpdateCertificateTemplateAction::run($this->model, $data);
                $message = trans('general.model_has_updated_successfully', ['model' => trans('certificateTemplate.model')]);
            } else {
                StoreCertificateTemplateAction::run($data);
                $message = trans('general.model_has_stored_successfully', ['model' => trans('certificateTemplate.model')]);
            }

            $this->success(
                title: $message,
                redirectTo: route('admin.certificate-template.index')
            );
        } catch (Throwable $e) {
            $this->error($e->getMessage(), timeout: 5000);
        }
    }

    /** Layout options for select. */
    public function layoutOptions(): array
    {
        $options = CertificateTemplate::layoutOptions();

        return collect($options)->map(fn (string $label, string $value) => [
            'id' => $value,
            'name' => $label,
        ])->values()->all();
    }

    /** Sample data for preview. */
    public function previewData(): array
    {
        return [
            'student_name' => 'John Doe',
            'course_title' => 'Sample Course',
            'course_level' => 'Beginner',
            'issue_date' => now()->format('F j, Y'),
            'grade' => 'A',
            'certificate_number' => 'CERT-000001',
            'duration' => '40 hours',
            'institute_name' => $this->institute_name ?: config('app.name'),
        ];
    }

    /** Resolve preview text with placeholders replaced. */
    public function previewText(?string $text): string
    {
        if ( ! $text) {
            return '';
        }

        $data = $this->previewData();

        foreach ($data as $key => $value) {
            $text = str_replace('{{' . $key . '}}', $value, $text);
        }

        return $text;
    }

    public function render(): View
    {
        return view('livewire.admin.pages.certificateTemplate.certificateTemplate-update-or-create', [
            'edit_mode' => $this->model->exists,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.certificate-template.index'), 'label' => trans('certificateTemplate.page.index_title')],
                ['label' => $this->model->exists
                    ? trans('certificateTemplate.page.edit_title')
                    : trans('certificateTemplate.page.create_title')],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.certificate-template.index'), 'icon' => 's-arrow-left'],
            ],
            'layoutOptions' => $this->layoutOptions(),
            'placeholders' => CertificateTemplate::placeholders(),
            'logoUrl' => $this->logo?->temporaryUrl()
                ?? ($this->model->exists ? $this->model->getFirstMediaUrl('logo', Constants::RESOLUTION_512_SQUARE) : null),
            'backgroundUrl' => $this->background?->temporaryUrl()
                ?? ($this->model->exists ? $this->model->getFirstMediaUrl('background', Constants::RESOLUTION_854_480) : null),
            'signatureUrl' => $this->signature?->temporaryUrl()
                ?? ($this->model->exists ? $this->model->getFirstMediaUrl('signature', Constants::RESOLUTION_400_300) : null),
        ]);
    }
}
