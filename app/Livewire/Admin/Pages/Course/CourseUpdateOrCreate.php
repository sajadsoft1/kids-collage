<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Actions\Course\StoreCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Enums\BooleanEnum;
use App\Enums\CourseTypeEnum;
use App\Models\Category;
use App\Models\Course;
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

    public Course $model;
    public ?string $title        = '';
    public ?string $description  = '';
    public ?string $body         = '';
    public array $categories     = [];
    public array $teachers       = [];
    public array $rooms          = [];
    public array $daysOptions    = [];
    public array $tags           = [];
    public int $category_id      = 1;
    public int $teacher_id       = 1;
    public ?int $capacity        = null;
    public float $price          = 0;
    public string $type          = '';
    public array $days_of_week   = [];
    public ?string $start_time   = null; // H:i
    public ?string $end_time     = null; // H:i
    public ?int $room_id         = null;
    public ?string $meeting_link = null;
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

        $this->rooms = Room::query()->get()
            ->map(fn ($item) => ['name' => $item->name, 'id' => $item->id])->toArray();

        $this->daysOptions = [
            ['id' => 0, 'name' => 'Sunday'],
            ['id' => 1, 'name' => 'Monday'],
            ['id' => 2, 'name' => 'Tuesday'],
            ['id' => 3, 'name' => 'Wednesday'],
            ['id' => 4, 'name' => 'Thursday'],
            ['id' => 5, 'name' => 'Friday'],
            ['id' => 6, 'name' => 'Saturday'],
        ];

        $this->type = CourseTypeEnum::IN_PERSON->value;

        if ($this->model->id) {
            $this->title         = $this->model->title;
            $this->description   = $this->model->description;
            $this->body          = $this->model->body;
            $this->category_id   = $this->model->category_id ?? $this->category_id;
            $this->teacher_id    = $this->model->teacher_id;
            $this->capacity      = $this->model->capacity;
            $this->price         = (float) $this->model->price;
            $this->type          = $this->model->type->value;
            $this->days_of_week  = $this->model->days_of_week ?? [];
            $this->start_time    = $this->model->start_time?->format('H:i');
            $this->end_time      = $this->model->end_time?->format('H:i');
            $this->room_id       = $this->model->room_id;
            $this->meeting_link  = $this->model->meeting_link;
            $this->tags          = $this->model->tags()->pluck('name')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title'          => 'required|string|max:255|min:2',
            'description'    => 'required|string|max:255',
            'body'           => 'required|string',
            'category_id'    => 'required|exists:categories,id',
            'teacher_id'     => 'required|exists:users,id',
            'capacity'       => 'nullable|integer|min:1',
            'price'          => 'required|numeric|min:0',
            'type'           => 'required|string',
            'days_of_week'   => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'start_time'     => 'nullable|date_format:H:i',
            'end_time'       => 'nullable|date_format:H:i',
            'room_id'        => 'nullable|exists:rooms,id',
            'meeting_link'   => 'nullable|string|max:255',
            'image'          => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags'           => 'nullable|array',
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
