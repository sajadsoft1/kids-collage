<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Room;

use App\Actions\Room\StoreRoomAction;
use App\Actions\Room\UpdateRoomAction;
use App\Models\Room;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class RoomUpdateOrCreate extends Component
{
    use Toast;

    public Room $model;
    public string $name        = '';
    public string $location    = '';
    public int $capacity       = 0;

    public function mount(Room $room): void
    {
        $this->model = $room;
        if ($this->model->id) {
            $this->name        = $this->model->name;
            $this->location    = $this->model->location;
            $this->capacity    = $this->model->capacity;
        }
    }

    protected function rules(): array
    {
        return [
            'name'     => 'required|string',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateRoomAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('room.model')]),
                redirectTo: route('admin.room.index')
            );
        } else {
            StoreRoomAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('room.model')]),
                redirectTo: route('admin.room.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.room.room-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.room.index'), 'label' => trans('general.page.index.title', ['model' => trans('room.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('room.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.room.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
