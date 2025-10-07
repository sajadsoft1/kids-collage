<?php

namespace App\Livewire\Admin\Pages\Order;

use Illuminate\View\View;
use Livewire\Component;

class OrderDetail extends Component
{
    public function render(): View
    {
        return view('livewire.admin.pages.order.order-detail',[
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.order.index'), 'label' => trans('general.page.index.title', ['model' => trans('order.model')])],
                ['label' => trans('general.page.show.title', ['model' => trans('order.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.order.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
