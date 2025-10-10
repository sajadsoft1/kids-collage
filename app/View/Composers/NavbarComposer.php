<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Attendance;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Bulletin;
use App\Models\Category;
use App\Models\Client;
use App\Models\Comment;
use App\Models\ContactUs;
use App\Models\Course;
use App\Models\Discount;
use App\Models\Enrollment;
use App\Models\Faq;
use App\Models\License;
use App\Models\Opinion;
use App\Models\Order;
use App\Models\Page;
use App\Models\Payment;
use App\Models\PortFolio;
use App\Models\Role;
use App\Models\Room;
use App\Models\Slider;
use App\Models\SocialMedia;
use App\Models\Tag;
use App\Models\Teammate;
use App\Models\Term;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavbarComposer
{
    /**
     * Check if user has access to a specific module
     * Module name should be in format: "section.module" (e.g., "crm.leads")
     */
    private function hasAccessToModule(string $moduleName): bool
    {
        return config('custom-modules.show_future_modules', false);
    }

    public function compose(View $view): void
    {
        $user = Auth::user();
        $view->with('navbarMenu', [
            [
                'icon'       => 'o-home',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.dashboard'),
                'route_name' => 'admin.dashboard',
            ],
            [
                'icon'       => 'o-bell',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.notifications'),
                'route_name' => 'admin.notification.index',
            ],
            [
                'icon'       => 'o-user-circle',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.profile'),
                'route_name' => 'admin.app.profile',
                'params'     => ['user' => $user->id],
            ],
            [
                'icon'       => 'o-calendar',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.calendar'),
                'route_name' => 'admin.app.calendar',
            ],

            // Education Management
            [
                'icon'     => 'o-academic-cap',
                'params'   => [],
                'exact'    => true,
                'title'    => trans('_menu.education'),
                'sub_menu' => [
                    [
                        'icon'       => 'o-book-open',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.course_management'),
                        'route_name' => 'admin.course-template.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-building-office-2',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.room_management'),
                        'route_name' => 'admin.room.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Room::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-calendar-days',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.term_management'),
                        'route_name' => 'admin.term.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Term::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-user-plus',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.enrollment_management'),
                        'route_name' => 'admin.enrollment.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-clipboard-document-check',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.attendance_management'),
                        'route_name' => 'admin.attendance.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Index')),
                    ],

                    // Future Education Modules
                    [
                        'icon'       => 'o-book-open',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.gradebook_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'gradebook'],
                        'access'     => $this->hasAccessToModule('education.gradebook'),
                    ],
                    [
                        'icon'       => 'o-clipboard-document',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.assignments_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'assignments'],
                        'access'     => $this->hasAccessToModule('education.assignments'),
                    ],
                    [
                        'icon'       => 'o-document-text',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.exams_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'exams'],
                        'access'     => $this->hasAccessToModule('education.exams'),
                    ],
                    [
                        'icon'       => 'o-document-chart-bar',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.report_cards'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'report-cards'],
                        'access'     => $this->hasAccessToModule('education.report_cards'),
                    ],
                    [
                        'icon'       => 'o-academic-cap',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.certificates_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'certificates'],
                        'access'     => $this->hasAccessToModule('education.certificates'),
                    ],
                    [
                        'icon'       => 'o-rectangle-stack',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.curriculum_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'curriculum'],
                        'access'     => $this->hasAccessToModule('education.curriculum'),
                    ],
                    [
                        'icon'       => 'o-user-group',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.parent_portal'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'parent-portal'],
                        'access'     => $this->hasAccessToModule('education.parent_portal'),
                    ],
                    [
                        'icon'       => 'o-users',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.student_portal'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'student-portal'],
                        'access'     => $this->hasAccessToModule('education.student_portal'),
                    ],
                    [
                        'icon'       => 'o-calendar-days',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.events_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'events'],
                        'access'     => $this->hasAccessToModule('education.events'),
                    ],
                    [
                        'icon'       => 'o-calendar',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.academic_calendar'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'academic-calendar'],
                        'access'     => $this->hasAccessToModule('education.academic_calendar'),
                    ],
                ],
            ],

            // HRM - Human Resource Management
            [
                'icon'       => 'o-users',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.hrm'),
                'route_name' => 'admin.user.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                'sub_menu'   => [
                    [
                        'icon'       => 'o-user-group',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.user_management'),
                        'route_name' => 'admin.user.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-briefcase',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.employee_management'),
                        'route_name' => 'admin.employee.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-academic-cap',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.teacher_management'),
                        'route_name' => 'admin.teacher.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-heart',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.parent_management'),
                        'route_name' => 'admin.parent.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-key',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.role_management'),
                        'route_name' => 'admin.role.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Index')),
                    ],

                    // Future HRM Modules
                    [
                        'icon'       => 'o-banknotes',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.payroll_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'payroll'],
                        'access'     => $this->hasAccessToModule('hrm.payroll'),
                    ],
                    [
                        'icon'       => 'o-calendar-days',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.leave_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'leave'],
                        'access'     => $this->hasAccessToModule('hrm.leave'),
                    ],
                    [
                        'icon'       => 'o-chart-bar-square',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.performance_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'performance'],
                        'access'     => $this->hasAccessToModule('hrm.performance'),
                    ],
                    [
                        'icon'       => 'o-clock',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.time_tracking'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'time-tracking'],
                        'access'     => $this->hasAccessToModule('hrm.time_tracking'),
                    ],
                    [
                        'icon'       => 'o-document-text',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.contracts_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'contracts'],
                        'access'     => $this->hasAccessToModule('hrm.contracts'),
                    ],
                    [
                        'icon'       => 'o-folder-open',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.documents_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'documents'],
                        'access'     => $this->hasAccessToModule('hrm.documents'),
                    ],
                    [
                        'icon'       => 'o-user-circle',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.recruitment_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'recruitment'],
                        'access'     => $this->hasAccessToModule('hrm.recruitment'),
                    ],
                    [
                        'icon'       => 'o-light-bulb',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.training_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'training'],
                        'access'     => $this->hasAccessToModule('hrm.training'),
                    ],
                    [
                        'icon'       => 'o-calendar',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.scheduling_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'scheduling'],
                        'access'     => $this->hasAccessToModule('hrm.scheduling'),
                    ],
                ],
            ],

            // Accounting Management
            [
                'icon'     => 'o-banknotes',
                'params'   => [],
                'exact'    => true,
                'title'    => trans('_menu.accounting'),
                'sub_menu' => [
                    [
                        'icon'       => 'o-shopping-cart',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.order_management'),
                        'route_name' => 'admin.order.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Order::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-credit-card',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.payment_management'),
                        'route_name' => 'admin.payment.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Index')),
                    ],

                    // Future Accounting Modules
                    [
                        'icon'       => 'o-document-duplicate',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.invoices_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'invoices'],
                        'access'     => $this->hasAccessToModule('accounting.invoices'),
                    ],
                    [
                        'icon'       => 'o-calendar-days',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.installments_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'installments'],
                        'access'     => $this->hasAccessToModule('accounting.installments'),
                    ],
                    [
                        'icon'       => 'o-arrow-trending-down',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.expenses_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'expenses'],
                        'access'     => $this->hasAccessToModule('accounting.expenses'),
                    ],
                    [
                        'icon'       => 'o-arrow-trending-up',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.income_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'income'],
                        'access'     => $this->hasAccessToModule('accounting.income'),
                    ],
                    [
                        'icon'       => 'o-presentation-chart-line',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.financial_reports'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'financial-reports'],
                        'access'     => $this->hasAccessToModule('accounting.financial_reports'),
                    ],
                    [
                        'icon'       => 'o-calculator',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.budget_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'budget'],
                        'access'     => $this->hasAccessToModule('accounting.budget'),
                    ],
                    [
                        'icon'       => 'o-arrows-right-left',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.cash_flow'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'cash-flow'],
                        'access'     => $this->hasAccessToModule('accounting.cash_flow'),
                    ],
                    [
                        'icon'       => 'o-clipboard-document-list',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.accounting_documents'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'accounting-documents'],
                        'access'     => $this->hasAccessToModule('accounting.accounting_documents'),
                    ],
                    [
                        'icon'       => 'o-receipt-percent',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.tax_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'tax'],
                        'access'     => $this->hasAccessToModule('accounting.tax'),
                    ],
                    [
                        'icon'       => 'o-inbox-stack',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.cashbox_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'cashbox'],
                        'access'     => $this->hasAccessToModule('accounting.cashbox'),
                    ],
                    [
                        'icon'       => 'o-building-library',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.banks_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'banks'],
                        'access'     => $this->hasAccessToModule('accounting.banks'),
                    ],
                ],
            ],

            // CRM - Customer Relationship Management
            [
                'icon'     => 'o-chart-bar',
                'params'   => [],
                'exact'    => true,
                'title'    => trans('_menu.crm'),
                'access'   => true,
                'sub_menu' => [
                    [
                        'icon'       => 'o-view-columns',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.board_management'),
                        'route_name' => 'admin.app.boards',
                    ],
                    [
                        'icon'       => 'o-ticket',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.ticket_management'),
                        'route_name' => 'admin.ticket.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-chat-bubble-left-right',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.comment_management'),
                        'route_name' => 'admin.comment.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-envelope',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.contact_us_management'),
                        'route_name' => 'admin.contact-us.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(ContactUs::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-receipt-percent',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.discount_management'),
                        'route_name' => 'admin.discount.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Discount::class, 'Index')),
                    ],

                    // Future CRM Modules
                    [
                        'icon'       => 'o-user-plus',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.leads_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'leads'],
                        'access'     => $this->hasAccessToModule('crm.leads'),
                    ],
                    [
                        'icon'       => 'o-megaphone',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.campaigns_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'campaigns'],
                        'access'     => $this->hasAccessToModule('crm.campaigns'),
                    ],
                    [
                        'icon'       => 'o-envelope-open',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.email_marketing'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'email-marketing'],
                        'access'     => $this->hasAccessToModule('crm.email_marketing'),
                    ],
                    [
                        'icon'       => 'o-device-phone-mobile',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.sms_marketing'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'sms-marketing'],
                        'access'     => $this->hasAccessToModule('crm.sms_marketing'),
                    ],
                    [
                        'icon'       => 'o-funnel',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.sales_pipeline'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'sales-pipeline'],
                        'access'     => $this->hasAccessToModule('crm.sales_pipeline'),
                    ],
                    [
                        'icon'       => 'o-clock',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.followups_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'followups'],
                        'access'     => $this->hasAccessToModule('crm.followups'),
                    ],
                    [
                        'icon'       => 'o-squares-2x2',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.segments_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'segments'],
                        'access'     => $this->hasAccessToModule('crm.segments'),
                    ],
                    [
                        'icon'       => 'o-chart-pie',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.crm_reports'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'crm-reports'],
                        'access'     => $this->hasAccessToModule('crm.crm_reports'),
                    ],
                ],
            ],

            // Content Management
            [
                'icon'     => 'o-document-text',
                'params'   => [],
                'exact'    => true,
                'title'    => trans('_menu.content'),
                'sub_menu' => [
                    [
                        'icon'       => 'o-newspaper',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.blog_management'),
                        'route_name' => 'admin.blog.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-photo',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.portfolio_management'),
                        'route_name' => 'admin.portFolio.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-document-duplicate',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.page_management'),
                        'route_name' => 'admin.page.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-folder',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.category_management'),
                        'route_name' => 'admin.category.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-tag',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.tag_management'),
                        'route_name' => 'admin.tag.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-megaphone',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.bulletin_management'),
                        'route_name' => 'admin.bulletin.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-shield-check',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.license_management'),
                        'route_name' => 'admin.license.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Index')),
                    ],

                    // Future Content Modules
                    [
                        'icon'       => 'o-film',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.media_library'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'media-library'],
                        'access'     => $this->hasAccessToModule('content.media_library'),
                    ],
                    [
                        'icon'       => 'o-play',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.video_gallery'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'video-gallery'],
                        'access'     => $this->hasAccessToModule('content.video_gallery'),
                    ],
                    [
                        'icon'       => 'o-microphone',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.podcasts_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'podcasts'],
                        'access'     => $this->hasAccessToModule('content.podcasts'),
                    ],
                    [
                        'icon'       => 'o-book-open',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.ebooks_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'ebooks'],
                        'access'     => $this->hasAccessToModule('content.ebooks'),
                    ],
                    [
                        'icon'       => 'o-arrow-down-tray',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.downloads_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'downloads'],
                        'access'     => $this->hasAccessToModule('content.downloads'),
                    ],
                    [
                        'icon'       => 'o-document-duplicate',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.documentation'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'documentation'],
                        'access'     => $this->hasAccessToModule('content.documentation'),
                    ],
                    [
                        'icon'       => 'o-newspaper',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.newsletter_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'newsletter'],
                        'access'     => $this->hasAccessToModule('content.newsletter'),
                    ],
                ],
            ],

            // Settings & Base Information
            [
                'icon'       => 'o-cog-6-tooth',
                'params'     => [],
                'exact'      => true,
                'title'      => trans('_menu.base_management'),
                'route_name' => 'admin.slider.index',
                'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Index')),
                'sub_menu'   => [
                    [
                        'icon'       => 'o-rectangle-stack',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.slider_management'),
                        'route_name' => 'admin.slider.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-question-mark-circle',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.faq_management'),
                        'route_name' => 'admin.faq.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Faq::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-photo',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.banner_management'),
                        'route_name' => 'admin.banner.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-building-storefront',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.client_management'),
                        'route_name' => 'admin.client.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Client::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-user-group',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.teammate_management'),
                        'route_name' => 'admin.teammate.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-share',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.social_media_management'),
                        'route_name' => 'admin.social-media.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Index')),
                    ],
                    [
                        'icon'       => 'o-star',
                        'params'     => [],
                        'exact'      => true,
                        'title'      => trans('_menu.opinion_management'),
                        'route_name' => 'admin.opinion.index',
                        'access'     => $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Index')),
                    ],

                    // Future Base Settings Modules
                    [
                        'icon'       => 'o-cog-8-tooth',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.system_settings'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'system-settings'],
                        'access'     => $this->hasAccessToModule('base_settings.system_settings'),
                    ],
                    [
                        'icon'       => 'o-bell-alert',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.notification_templates'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'notification-templates'],
                        'access'     => $this->hasAccessToModule('base_settings.notification_templates'),
                    ],
                    [
                        'icon'       => 'o-envelope',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.email_templates'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'email-templates'],
                        'access'     => $this->hasAccessToModule('base_settings.email_templates'),
                    ],
                    [
                        'icon'       => 'o-chat-bubble-bottom-center-text',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.sms_templates'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'sms-templates'],
                        'access'     => $this->hasAccessToModule('base_settings.sms_templates'),
                    ],
                    [
                        'icon'       => 'o-clipboard-document-list',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.activity_logs'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'activity-logs'],
                        'access'     => $this->hasAccessToModule('base_settings.activity_logs'),
                    ],
                    [
                        'icon'       => 'o-archive-box',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.backup_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'backup'],
                        'access'     => $this->hasAccessToModule('base_settings.backup'),
                    ],
                    [
                        'icon'       => 'o-language',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.languages_management'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'languages'],
                        'access'     => $this->hasAccessToModule('base_settings.languages'),
                    ],
                    [
                        'icon'       => 'o-magnifying-glass-circle',
                        'exact'      => true,
                        'badge'      => trans('_menu.future_module'),
                        'title'      => trans('_menu.seo_settings'),
                        'route_name' => 'admin.feature-module',
                        'params'     => ['module' => 'seo-settings'],
                        'access'     => $this->hasAccessToModule('base_settings.seo_settings'),
                    ],
                ],
            ],
        ]);
    }
}
