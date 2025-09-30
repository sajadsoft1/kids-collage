<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Client;
use App\Models\Comment;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Opinion;
use App\Models\Page;
use App\Models\PortFolio;
use App\Models\Role;
use App\Models\Slider;
use App\Models\SocialMedia;
use App\Models\Tag;
use App\Models\Teammate;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavbarComposer
{
    public function compose(View $view): void
    {
        $user = Auth::user();
        $view->with('navbarMenu', [
            [
                'icon'       => 's-home',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.dashboard'),
                'route_name' => 'admin.dashboard',
            ],
            [
                'icon'       => 's-home',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.notifications'),
                'route_name' => 'admin.notification.index',
            ],
            [
                'icon'       => 's-home',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.profile'),
                'route_name' => 'admin.app.profile',
            ],

            [
                'icon'       => 'm-rectangle-stack',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.board_management'),
                'route_name' => 'admin.app.boards',
            ],

            [
                'icon'       => 's-calendar-date-range',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.calendar'),
                'route_name' => 'admin.app.calendar',
            ],

            [
                'icon'     => 's-book-open',
                'params'   => [],
                'exact'    => true,
                'title'    => 'محتوا',
                'sub_menu' => [
                    [
                        'icon'       => 's-book-open',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.blog_management'),
                        'route_name' => 'admin.blog.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Index')),
                    ],
                    [
                        'icon'       => 'lucide.gallery-horizontal-end',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.portfolio_management'),
                        'route_name' => 'admin.portFolio.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-squares-2x2',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.category_management'),
                        'route_name' => 'admin.category.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-tag',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.tag_management'),
                        'route_name' => 'admin.tag.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-megaphone',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.bulletin_management'),
                        'route_name' => 'admin.bulletin.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-shield-check',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.license_management'),
                        'route_name' => 'admin.license.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Index')),
                    ],
                ],
            ],
            [
                'icon'     => 's-academic-cap',
                'params'   => [],
                'exact'    => true,
                'title'    => 'مدیریت آموزشی',
                'sub_menu' => [
                    [
                        'icon'       => 's-academic-cap',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.course_management'),
                        'route_name' => 'admin.course-template.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-building-office-2',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.room_management'),
                        'route_name' => 'admin.room.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-user-plus',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.enrollment_management'),
                        'route_name' => 'admin.enrollment.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-clipboard-document-check',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.attendance_management'),
                        'route_name' => 'admin.attendance.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Index')),
                    ],
                ],
            ],
            [
                'icon'     => 's-currency-dollar',
                'params'   => [],
                'exact'    => true,
                'title'    => 'مدیریت مالی',
                'sub_menu' => [
                    [
                        'icon'       => 's-shopping-cart',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.order_management'),
                        'route_name' => 'admin.order.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-credit-card',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.payment_management'),
                        'route_name' => 'admin.payment.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-calendar-days',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.installment_management'),
                        'route_name' => 'admin.installment.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Installment::class, 'Index')),
                    ],
                ],
            ],
            [
                'icon'       => 's-users',
                'params'     => [],
                'exact'      => true,
                'title'      => 'اعضا',
                'route_name' => 'admin.user.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                'sub_menu'   => [
                    [
                        'icon'       => 's-users',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.user_management'),
                        'route_name' => 'admin.user.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                    ],
                    [
                        'icon'       => 'lucide.key-round',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.role_management'),
                        'route_name' => 'admin.role.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Index')),
                    ],
                ],
            ],
            [
                'icon'       => 's-ticket',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.ticket_management'),
                'route_name' => 'admin.ticket.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Index')),
            ],

            [
                'icon'       => 's-chat-bubble-left-right',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.comment_management'),
                'route_name' => 'admin.comment.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Index')),
            ],

            [
                'icon'       => 'lucide.gallery-horizontal-end',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.contact_us_management'),
                'route_name' => 'admin.contact-us.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Index')),
            ],
            [
                'icon'       => 'lucide.layers',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.page_management'),
                'route_name' => 'admin.page.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Index')),
            ],

            [
                'icon'       => 's-arrow-left-start-on-rectangle',
                'params'     => [],
                'exact'      => true,
                'title'      => 'اطلاعات پایه',
                'route_name' => 'admin.slider.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Index')),
                'sub_menu'   => [
                    [
                        'icon'       => 's-arrow-left-start-on-rectangle',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.slider_management'),
                        'route_name' => 'admin.slider.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-question-mark-circle',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.faq_management'),
                        'route_name' => 'admin.faq.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-photo',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.banner_management'),
                        'route_name' => 'admin.banner.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Index')),
                    ],
                    [
                        'icon'       => 'lucide.square-user-round',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.client_management'),
                        'route_name' => 'admin.client.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Index')),
                    ],
                    [
                        'icon'       => 'lucide.users',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.teammate_management'),
                        'route_name' => 'admin.teammate.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Index')),
                    ],
                    [
                        'icon'       => 'lucide.layers',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.social_media_management'),
                        'route_name' => 'admin.social-media.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Index')),
                    ],
                    [
                        'icon'       => 's-sparkles',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.opinion_management'),
                        'route_name' => 'admin.opinion.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Index')),
                    ],
                ],
            ],
        ]);
    }
}
