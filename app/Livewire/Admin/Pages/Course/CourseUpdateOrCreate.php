<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Actions\Course\StoreCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Enums\BooleanEnum;
use App\Enums\CourseTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CourseUpdateOrCreate extends Component
{
    use Toast, WithFileUploads;

    public Course $model;
    public ?string $title       = '';
    public ?string $description = '';
    public ?string $body        = '';
    public bool $published      = false;
    public $published_at        = '';
    public array $categories    = [];
    public array $teachers      = [];
    public array $tags          = [];
    public int $category_id     = 1;
    public int $teacher_id      = 1;
    public float $price         = 0;
    public string $type         = '';
    public $start_date          = '';
    public $end_date            = '';
    public $image;

    public function mount(Course $course): void
    {
        $this->model      = $course;
        $this->categories = Category::where('type', 'course')
            ->where('published', BooleanEnum::ENABLE)
            ->get()
            ->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();

        $this->teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get()
            ->map(fn ($item) => ['name' => $item->name, 'id' => $item->id])->toArray();

        $this->published_at = now()->format('Y-m-d');
        $this->start_date   = now()->format('Y-m-d');
        $this->end_date     = now()->addMonths(3)->format('Y-m-d');
        $this->type         = CourseTypeEnum::INPERSON->value;

        if ($this->model->id) {
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->body         = $this->model->body;
            $this->published    = $this->model->published->asBoolean();
            $this->published_at = $this->model->published_at;
            $this->category_id  = $this->model->category_id;
            $this->teacher_id   = $this->model->teacher_id;
            $this->price        = $this->model->price;
            $this->type         = $this->model->type->value;
            $this->start_date   = $this->model->start_date?->format('Y-m-d');
            $this->end_date     = $this->model->end_date?->format('Y-m-d');
            $this->tags         = $this->model->tags()->pluck('name')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|max:255|min:2',
            'description'  => 'required|string|max:255',
            'body'         => 'required|string',
            'published'    => 'required|boolean',
            'published_at' => 'nullable|date',
            'category_id'  => 'required|exists:categories,id',
            'teacher_id'   => 'required|exists:users,id',
            'price'        => 'required|numeric|min:0',
            'type'         => 'required|string',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after:start_date',
            'image'        => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags'         => 'nullable|array',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateCourseAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('course.model')]),
                redirectTo: route('admin.course.index')
            );
        } else {
            $payload['slug']          = StringHelper::slug($this->title);
            $payload['user_id']       = 1; // Default user ID
            $payload['view_count']    = 0;
            $payload['comment_count'] = 0;
            $payload['wish_count']    = 0;
            $payload['languages']     = [app()->getLocale()];
            StoreCourseAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('course.model')]),
                redirectTo: route('admin.course.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.course.course-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.course.index'), 'label' => trans('general.page.index.title', ['model' => trans('course.model')])],
                ['label' => $this->model->id ? trans('general.page.edit.title', ['model' => trans('course.model')]) : trans('general.page.create.title', ['model' => trans('course.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
