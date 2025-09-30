<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseTemplate;

use App\Actions\CourseTemplate\StoreCourseTemplateAction;
use App\Actions\CourseTemplate\UpdateCourseTemplateAction;
use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Enums\CourseLevelEnum;
use App\Enums\CourseTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\CourseTemplate;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class CourseTemplateUpdateOrCreate extends Component
{
    use Toast;

    public CourseTemplate $model;
    public string $selectedTab           = 'informations-tab';
    public string $slug                  = '';
    public string $title                 = '';
    public string $description           = '';
    public string $body                  = '';
    public string $type                  = CourseTypeEnum::IN_PERSON->value;
    public string $level                 = CourseLevelEnum::BIGGINER->value;
    public array $categories             = [];
    public array $tags                   = [];
    public int $category_id              = 1;
    public bool $is_self_paced           = false;
    public array $prerequisitesList          = [];
    public array $prerequisites          = [];
    public int $sessions_count         = 10;
    public $image;

    public function mount(CourseTemplate $courseTemplate): void
    {
        $this->model      = $courseTemplate;
        $this->categories = Category::where('type', CategoryTypeEnum::COURSE)
            ->where('published', BooleanEnum::ENABLE)
            ->get()
            ->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();

        $this->prerequisitesList = CourseTemplate::all()->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();

        if ($this->model->id) {
            $this->slug           = $this->model->slug;
            $this->title          = $this->model->title;
            $this->description    = $this->model->description;
            $this->body           = $this->model->body;
            $this->type           = $this->model->type->value;
            $this->level          = $this->model->level->value;
            $this->prerequisites  = $this->model->prerequisites;
            $this->is_self_paced  = $this->model->is_self_paced;
            $this->category_id    = $this->model->category_id;
            $this->tags           = $this->model->tags()->pluck('name')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'body'        => 'required|string',
            'category_id' => 'required|exists:categories,id,type,blog',
            'image'       => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags'        => 'nullable|array',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateCourseTemplateAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('courseTemplate.model')]),
                redirectTo: route('admin.course-template.index')
            );
        } else {
            $payload['slug'] = StringHelper::slug($this->title);
            StoreCourseTemplateAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('courseTemplate.model')]),
                redirectTo: route('admin.course-template.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.courseTemplate.courseTemplate-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.course-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('courseTemplate.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('courseTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course-template.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
