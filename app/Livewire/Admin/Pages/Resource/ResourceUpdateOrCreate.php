<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Resource;

use App\Actions\Resource\StoreResourceAction;
use App\Actions\Resource\UpdateResourceAction;
use App\Enums\ResourceType;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Models\Resource;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class ResourceUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Resource $model;
    public string $title = '';
    public string $description = '';
    public ?string $type = null;
    public string $path = '';
    public int $is_public = 1;
    public int $order = 1;
    public $file;
    public array $relationships = [];

    public function mount(Resource $resource): void
    {
        $this->model = $resource;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->type = $this->model->type->value;
            $this->path = $this->model->path;
            $this->is_public = (int) $this->model->is_public;
            $this->order = $this->model->order;

            // Load existing relationships
            $this->relationships = $this->model->courseSessionTemplates->map(function ($template) {
                return [
                    'course_template_id' => $template->course_template_id,
                    'course_session_template_id' => $template->id,
                ];
            })->toArray();
        }
    }

    /** Reset file and path when type changes */
    public function updatedType(): void
    {
        $this->file = null;
        $this->path = '';
        $this->resetValidation(['file', 'path']);
    }

    protected function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:' . implode(',', ResourceType::values()),
            'is_public' => 'boolean',
            'order' => 'required|integer|min:0',
            'relationships' => 'required|array|min:1',
            'relationships.*.course_session_template_id' => 'required|integer|exists:course_session_templates,id',
            'relationships.*.course_template_id' => 'required|integer|exists:course_templates,id',
        ];

        // Dynamic validation based on resource type
        if ($this->type && $this->type === ResourceType::LINK->value) {
            $rules['path'] = 'required|url';
        } elseif ($this->type) {
            // In edit mode, file is optional if already exists
            if ($this->model->id && $this->model->isUploadedFile()) {
                $rules['file'] = 'nullable|file';
            } else {
                $rules['file'] = 'required|file';
            }
        }

        return $rules;
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Prepare payload for action
        $actionPayload = [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'type' => ResourceType::from($payload['type'])->value,
            'is_public' => $payload['is_public'],
            'order' => $payload['order'],
        ];

        if ($this->type === ResourceType::LINK->value) {
            $actionPayload['path'] = $payload['path'];
        } else {
            if (isset($payload['file']) && $payload['file']) {
                $actionPayload['file'] = $payload['file'];
            }
        }

        // Extract course_session_template_ids from relationships
        $courseSessionTemplateIds = collect($payload['relationships'])
            ->pluck('course_session_template_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if ($this->model->id) {
            UpdateResourceAction::run($this->model, $actionPayload);

            // Sync relationships with pivot table
            $this->model->courseSessionTemplates()->sync($courseSessionTemplateIds);

            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('resource.model')]),
                redirectTo: route('admin.resource.index')
            );
        } else {
            $resource = StoreResourceAction::run($actionPayload);

            // Attach relationships to pivot table
            $resource->courseSessionTemplates()->attach($courseSessionTemplateIds);

            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('resource.model')]),
                redirectTo: route('admin.resource.index')
            );
        }
    }

    #[Computed]
    public function resourceTypes(): array
    {
        return ResourceType::options();
    }

    public function addRelationship(): void
    {
        $this->relationships[] = [
            'course_template_id' => null,
            'course_session_template_id' => null,
        ];
    }

    public function removeRelationship(int $index): void
    {
        unset($this->relationships[$index]);
        $this->relationships = array_values($this->relationships);
    }

    public function getCourseSessionTemplatesForTemplate(?int $courseTemplateId): array
    {
        if ( ! $courseTemplateId) {
            return [];
        }

        return CourseSessionTemplate::where('course_template_id', $courseTemplateId)
            ->select('id')
            ->get()
            ->map(fn ($template) => [
                'value' => $template->id,
                'label' => $template->title,
                'disabled' => in_array($template->id, collect($this->relationships)->pluck('course_session_template_id')->toArray()),
            ])
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.pages.resource.resource-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.resource.index'), 'label' => trans('general.page.index.title', ['model' => trans('resource.model')])],
                ['label' => $this->model->id ? trans('general.page.edit.title', ['model' => trans('resource.model')]) : trans('general.page.create.title', ['model' => trans('resource.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.resource.index'), 'icon' => 's-arrow-left'],
            ],
            'courseTemplates' => CourseTemplate::select('id')->get()->map(fn ($template) => [
                'value' => $template->id,
                'label' => $template->title,
            ])->toArray(),
        ]);
    }
}
