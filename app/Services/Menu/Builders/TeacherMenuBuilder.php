<?php

declare(strict_types=1);

namespace App\Services\Menu\Builders;

use App\Models\User;
use App\Services\Menu\MenuPermissionChecker;

class TeacherMenuBuilder extends BaseMenuBuilder
{
    public function __construct(
        MenuPermissionChecker $permissionChecker
    ) {
        parent::__construct($permissionChecker);
    }

    /**
     * Build menu array for teacher user
     *
     * @return array<int, array<string, mixed>>
     */
    public function build(User $user): array
    {
        return [
            [
                'icon' => 'o-home',
                'params' => [],
                'exact' => true,
                'title' => trans('_menu.dashboard'),
                'route_name' => 'admin.dashboard',
            ],
            [
                'icon' => 'o-user-circle',
                'exact' => true,
                'title' => trans('_menu.profile'),
                'route_name' => 'admin.app.profile',
                'params' => ['user' => $user->id],
            ],
            [
                'icon' => 'o-calendar',
                'params' => [],
                'title' => trans('_menu.calendar'),
                'route_name' => 'admin.app.calendar',
            ],
            [
                'icon' => 'o-document-text',
                'params' => [],
                'title' => trans('_menu.exams'),
                'route_name' => 'admin.exam.list',
            ],
            [
                'icon' => 'o-document-text',
                'params' => [],
                'title' => trans('_menu.courses'),
                'route_name' => 'admin.course.list',
            ],
            [
                'icon' => 'o-shopping-cart',
                'params' => [],
                'title' => trans('_menu.order_management'),
                'route_name' => 'admin.order.index',
            ],
            [
                'icon' => 'o-credit-card',
                'params' => [],
                'title' => trans('_menu.payment_management'),
                'route_name' => 'admin.payment.index',
            ],
            [
                'icon' => 'o-clipboard-document-list',
                'params' => [],
                'title' => trans('_menu.survey_management'),
                'route_name' => 'admin.survey.index',
            ],
        ];
    }
}
