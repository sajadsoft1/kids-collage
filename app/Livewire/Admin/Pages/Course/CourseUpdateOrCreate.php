<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Actions\Course\StoreCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Enums\CourseTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseTemplate;
use App\Models\Room;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class CourseUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast, WithFileUploads;

    public CourseTemplate $courseTemplate;
    public Course $model;
    public ?string $title        = '';
    public ?string $description  = '';
    public ?string $body         = '';
    public array $categories     = [];
    public array $teachers       = [];
    public array $rooms          = [];
    public array $tags           = [];
    public int $category_id      = 1;
    public int $teacher_id       = 1;
    public ?int $capacity        = null;
    public float $price          = 0;
    public string $type          = '';
    public ?int $room_id         = null;
    public $image;

    public function mount(CourseTemplate $courseTemplate, Course $course): void
    {
        $this->courseTemplate = $courseTemplate;
        $this->model          = $course;
        $this->categories     = Category::where('type', CategoryTypeEnum::COURSE->value)
            ->where('published', BooleanEnum::ENABLE->value)
            ->get()
            ->map(fn ($category) => ['value' => $category->id, 'label' => $category->title])->toArray();

        $this->teachers = User::where('type', UserTypeEnum::TEACHER->value)->get()->map(fn ($teacher) => ['value' => $teacher->id, 'label' => $teacher->name])->toArray();

        $this->rooms = Room::query()->get()->map(fn ($room) => ['value' => $room->id, 'label' => $room->name])->toArray();

        $this->type = CourseTypeEnum::IN_PERSON->value;

        if ($this->model->id) {
            // dd($this->courseTemplate->toArray(), $this->model->toArray());
            $this->title         = $this->courseTemplate->title;
            $this->description   = $this->courseTemplate->description;
            $this->body          = $this->courseTemplate->body;
            $this->category_id   = $this->courseTemplate->category_id ?? $this->category_id;
            $this->teacher_id    = $this->model->teacher_id;
            $this->capacity      = $this->model->capacity;
            $this->price         = (float) $this->model->price;
            $this->type          = $this->courseTemplate->type->value;
            $this->room_id       = $this->model->room_id;
            $this->tags          = $this->courseTemplate->tags()->pluck('name')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:2',
            'description' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'required|exists:users,id',
            'capacity' => 'nullable|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string',
            'room_id' => 'nullable|exists:rooms,id',
            'image' => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags' => 'nullable|array',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateCourseAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('course.model')]),
                    redirectTo: route('admin.course.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            $payload['teacher_id'] = $payload['teacher_id'];
            // category_id stored on course is legacy; keep if needed in actions or remove later
            $payload['languages']  = [app()->getLocale()];

            try {
                StoreCourseAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('course.model')]),
                    redirectTo: route('admin.course.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.course.course-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.course.index', ['courseTemplate' => $this->courseTemplate->id]), 'label' => trans('general.page.index.title', ['model' => trans('course.model')])],
                ['label' => $this->model->id ? trans('general.page.edit.title', ['model' => trans('course.model')]) : trans('general.page.create.title', ['model' => trans('course.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course.index', ['courseTemplate' => $this->courseTemplate->id]), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
