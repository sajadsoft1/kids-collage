<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Resource;

use App\Actions\Resource\StoreResourceAction;
use App\Actions\Resource\UpdateResourceAction;
use App\Enums\ResourceType;
use App\Models\Course;
use App\Models\CourseSession;
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
    public string $title             = '';
    public string $description       = '';
    public string $type              = '';
    public string $path              = '';
    public string $resourceable_type = '';
    public ?int $resourceable_id     = null;
    public bool $is_public           = true;
    public int $order                = 0;
    public $file;

    public function mount(Resource $resource): void
    {
        $this->model = $resource;
        if ($this->model->id) {
            $this->title             = $this->model->title;
            $this->description       = $this->model->description;
            $this->type              = $this->model->type->value;
            $this->path              = $this->model->path;
            $this->resourceable_type = $this->model->resourceable_type;
            $this->resourceable_id   = $this->model->resourceable_id;
            $this->is_public         = $this->model->is_public;
            $this->order             = $this->model->order;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'type'              => 'required|string|in:' . implode(',', array_column(ResourceType::cases(), 'value')),
            'resourceable_type' => 'required|string',
            'resourceable_id'   => 'required|integer',
            'is_public'         => 'boolean',
            'order'             => 'required|integer|min:0',
        ];

        // Dynamic validation based on resource type
        if ($this->type === ResourceType::LINK->value) {
            $rules['path'] = 'required|url';
        } else {
            $rules['file'] = 'required|file';
        }

        return $rules;
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Prepare payload for action
        $actionPayload = [
            'title'             => $payload['title'],
            'description'       => $payload['description'],
            'type'              => ResourceType::from($payload['type']),
            'resourceable_type' => $payload['resourceable_type'],
            'resourceable_id'   => $payload['resourceable_id'],
            'is_public'         => $payload['is_public'],
            'order'             => $payload['order'],
        ];

        if ($this->type === ResourceType::LINK->value) {
            $actionPayload['path'] = $payload['path'];
        } else {
            $actionPayload['file'] = $payload['file'];
        }

        if ($this->model->id) {
            UpdateResourceAction::run($this->model, $actionPayload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('resource.model')]),
                redirectTo: route('admin.resource.index')
            );
        } else {
            StoreResourceAction::run($actionPayload);
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

    #[Computed]
    public function resourceableOptions(): array
    {
        $options = [];

        // Course Templates
        $options = CourseTemplate::select('id')->get()->map(function ($template) {
            return [
                'label' => "Course Template: {$template->title}",
                'value' => "{$template->id}",
                'group' => 'Course Templates',
            ];
        });

        // Course Session Templates
        $options = CourseSessionTemplate::with('courseTemplate')
            ->select('id', 'course_template_id')
            ->get()
            ->map(function ($template) {
                return [
                    'label' => "Session: {$template?->title} ({$template?->courseTemplate?->title})",
                    'value' => "{$template->id}",
                    'group' => 'Session Templates',
                ];
            });

        // Course Sessions
        $options = CourseSession::with(['sessionTemplate.courseTemplate'])
            ->select('id', 'course_session_template_id')
            ->get()
            ->map(function ($session) {
                return [
                    'label' => "Session Instance: {$session->sessionTemplate?->title} ({$session->sessionTemplate?->courseTemplate?->title})",
                    'value' => "{$session->id}",
                    'group' => 'Course Sessions',
                ];
            });

        return $options->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.pages.resource.resource-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.resource.index'), 'label' => trans('general.page.index.title', ['model' => trans('resource.model')])],
                ['label' => $this->model->id ? trans('general.page.edit.title', ['model' => trans('resource.model')]) : trans('general.page.create.title', ['model' => trans('resource.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.resource.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
