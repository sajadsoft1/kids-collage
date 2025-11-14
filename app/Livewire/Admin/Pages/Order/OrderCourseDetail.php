<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use Illuminate\View\View;
use Livewire\Component;

class OrderCourseDetail extends Component
{
    public function render(): View
    {
        return view('livewire.admin.pages.order.order-course-detail', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => trans('general.page.show.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
