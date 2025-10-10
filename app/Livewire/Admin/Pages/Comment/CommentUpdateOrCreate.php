<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Comment;

use App\Actions\Comment\StoreCommentAction;
use App\Actions\Comment\UpdateCommentAction;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class CommentUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Comment $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;
    public int $user_id        =1;
    public ?int $admin_id;
    public ?int $parent_id;
    public string $comment       = '';
    public ?string $admin_note   = '';
    public array $admins         = [];
    public array $objects        = [];
    public string $morphable_type=Blog::class;
    public array $object_values  =[];
    public ?int $morphable_id    =1;

    public function mount(Comment $comment): void
    {
        $this->model  = $comment;
        $this->admins = User::where(function ($query) {
            $query->whereHas('permissions')
                ->orWhereHas('roles.permissions');
        })
            ->get()
            ->map(function ($admin) {
                return [
                    'id'   => $admin->id,
                    'name' => $admin->name,
                ];
            })
            ->toArray();
        $this->object_values=Blog::query()->get()->map(function ($object) {
            return [
                'id'    => $object->id,
                'title' => $object->title,
            ];
        })->toArray();
        $this->morphable_id=$this->object_values[0]['id'];
        $this->objects     =[[
            'key'   => Blog::class,
            'value' => trans('blog.model'),
        ],
        ];
        if ($this->model->id) {
            $this->published      = (bool) $this->model->published->value;
            $this->user_id        = $this->model->user_id;
            $this->admin_id       = $this->model->admin_id;
            $this->parent_id      = $this->model->parent_id;
            $this->comment        = $this->model->comment;
            $this->admin_note     = $this->model->admin_note;
            $this->morphable_id   = $this->model->morphable_id;
            $this->morphable_type = $this->model->morphable_type;
            $this->object_values  = $this->morphable_type::query()->get()->map(function ($object) {
                return [
                    'id'    => $object->id,
                    'title' => $object->title ?? $object->name,
                ];
            })->toArray();
        } else {
            // For new comments, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return [
            'published'      => ['required', 'boolean'],
            'user_id'        => ['required', 'exists:users,id'],
            'admin_id'       => ['nullable', 'exists:users,id'],
            'parent_id'      => ['nullable'],
            'comment'        => ['required', 'string', 'min:3'],
            'admin_note'     => ['nullable', 'string'],
            'morphable_type' => ['required'],
            'morphable_id'   => ['required'],
        ];
    }

    public function updatedmorphableType($value)
    {
        $this->object_values=$this->morphable_type::query()->get()->map(function ($object) {
            return [
                'id'    => $object->id,
                'title' => $object->title ?? $object->name,
            ];
        })->toArray();
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateCommentAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('comment.model')]),
                    redirectTo: route('admin.comment.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreCommentAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('comment.model')]),
                    redirectTo: route('admin.comment.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.comment.comment-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.comment.index'), 'label' => trans('general.page.index.title', ['model' => trans('comment.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('comment.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.comment.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
