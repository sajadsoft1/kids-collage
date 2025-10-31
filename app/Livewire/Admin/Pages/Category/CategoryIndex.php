<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Category;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Services\Permissions\PermissionsService;
use Livewire\Attributes\Url;
use Livewire\Component;

class CategoryIndex extends Component
{
    #[Url(keep: true, as: 'type')]
    public string $type = CategoryTypeEnum::BLOG->value;
    
    public function render()
    {
        return view('livewire.admin.pages.category.category-index', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => trans('general.page.index.title', ['model' => trans('category.model')])],
            ],
            'breadcrumbsActions' => [
                [
                    'link'   => route('admin.category.create', ['type' => $this->type]),
                    'icon'   => 's-plus',
                    'label'  => trans(
                        'general.page.create.title',
                        ['model' => trans('category.model')]
                    ),
                    'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Store')),
                ],
            ],
        ]);
    }
}
