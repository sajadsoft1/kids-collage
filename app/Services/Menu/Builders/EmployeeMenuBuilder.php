<?php

declare(strict_types=1);

namespace App\Services\Menu\Builders;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\FlashCard;
use App\Models\Resource;
use App\Models\Room;
use App\Models\Term;
use App\Models\User;
use App\Services\Permissions\Models\SharedPermissions;

class EmployeeMenuBuilder extends BaseMenuBuilder
{
    /**
     * Build menu array for employee user
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
            // Education Management
            [
                'icon' => 'o-academic-cap',
                'params' => [],
                'title' => trans('_menu.education'),
                'sub_menu' => $this->getEducationSubMenu($user),
            ],

            // Exam Management
            [
                'icon' => 'o-document-text',
                'title' => trans('_menu.exam_management'),
                'access' => config('app.env') === 'local',
                'sub_menu' => $this->getExamSubMenu($user),
            ],

            // HRM - Human Resource Management
            [
                'icon' => 'o-users',
                'params' => [],
                'title' => trans('_menu.hrm'),
                'route_name' => 'admin.user.index',
                'access' => $this->checkPermission([
                    User::class => 'Index',
                ]),
                'sub_menu' => $this->getHrmSubMenu($user),
            ],

            // Accounting Management
            [
                'icon' => 'o-banknotes',
                'params' => [],
                'title' => trans('_menu.accounting'),
                'sub_menu' => $this->getAccountingSubMenu($user),
            ],

            // CRM - Customer Relationship Management
            [
                'icon' => 'o-chart-bar',
                'params' => [],
                'title' => trans('_menu.crm'),
                'access' => true,
                'sub_menu' => $this->getCrmSubMenu($user),
            ],

            // Content Management
            [
                'icon' => 'o-document-text',
                'params' => [],
                'title' => trans('_menu.content'),
                'sub_menu' => $this->getContentSubMenu($user),
            ],

            // Settings & Base Information
            [
                'icon' => 'o-cog-6-tooth',
                'params' => [],
                'title' => trans('_menu.base_management'),
                'sub_menu' => $this->getBaseManagementSubMenu($user),
            ],
        ];
    }

    /**
     * Get Education sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getEducationSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-book-open',
                'params' => [],
                'title' => trans('_menu.course_management'),
                'route_name' => 'admin.course-template.index',
                'access' => $this->checkPermission([
                    Course::class => 'Index',
                    function (User $user): bool {
                        return false;
                    },
                ]),
            ],
            [
                'icon' => 'o-building-office-2',
                'params' => [],
                'title' => trans('_menu.room_management'),
                'route_name' => 'admin.room.index',
                'access' => $this->checkPermission([
                    Room::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-calendar-days',
                'params' => [],
                'title' => trans('_menu.term_management'),
                'route_name' => 'admin.term.index',
                'access' => $this->checkPermission([
                    Term::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-user-plus',
                'params' => [],
                'title' => trans('_menu.enrollment_management'),
                'route_name' => 'admin.enrollment.index',
                'access' => $this->checkPermission([
                    Enrollment::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-clipboard-document-check',
                'params' => [],
                'title' => trans('_menu.attendance_management'),
                'route_name' => 'admin.attendance.index',
                'access' => $this->checkPermission([
                    Attendance::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-rectangle-stack', // resource_management: stack or archive icon
                'params' => [],
                'title' => trans('_menu.resource_management'),
                'route_name' => 'admin.resource.index',
                'access' => $this->checkPermission([
                    Resource::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-sparkles', // flashcard: sparkles/idea/lightbulb
                'params' => [],
                'title' => trans('_menu.flashcard'),
                'route_name' => 'admin.flash-card.index',
                'access' => $this->checkPermission([
                    FlashCard::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-book-open', // my_notebook: open book/notebook icon
                'params' => [],
                'title' => trans('_menu.my_notebook'),
                'route_name' => 'admin.notebook.index',
                'access' => $this->checkPermission([
                    FlashCard::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-book-open',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.gradebook_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'gradebook'],
                'access' => $this->hasAccessToModule('education.gradebook'),
            ],
            [
                'icon' => 'o-clipboard-document',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.assignments_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'assignments'],
                'access' => $this->hasAccessToModule('education.assignments'),
            ],
            [
                'icon' => 'o-document-chart-bar',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.report_cards'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'report-cards'],
                'access' => $this->hasAccessToModule('education.report_cards'),
            ],
            [
                'icon' => 'o-academic-cap',
                'title' => trans('_menu.certificate_builder'),
                'route_name' => 'admin.certificate-template.index',
                'params' => [],
                'access' => true,
            ],
            [
                'icon' => 'o-document-check',
                'title' => trans('_menu.issued_certificates'),
                'route_name' => 'admin.certificate.index',
                'params' => [],
                'exact' => true,
                'access' => true,
            ],
            [
                'icon' => 'o-rectangle-stack',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.curriculum_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'curriculum'],
                'access' => $this->hasAccessToModule('education.curriculum'),
            ],
            [
                'icon' => 'o-user-group',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.parent_portal'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'parent-portal'],
                'access' => $this->hasAccessToModule('education.parent_portal'),
            ],
            [
                'icon' => 'o-users',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.student_portal'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'student-portal'],
                'access' => $this->hasAccessToModule('education.student_portal'),
            ],
            [
                'icon' => 'o-calendar-days',
                'title' => trans('_menu.events_management'),
                'route_name' => 'admin.event.index',
                'access' => $this->checkPermission([
                    Event::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-calendar',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.academic_calendar'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'academic-calendar'],
                'access' => $this->hasAccessToModule('education.academic_calendar'),
            ],
        ];
    }

    /**
     * Get Exam sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getExamSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-document-text',
                'params' => [],
                'title' => trans('_menu.exams'),
                'route_name' => 'admin.exam.index',
                'access' => $this->checkPermission([
                    \App\Models\Exam::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-question-mark-circle',
                'params' => [],
                'title' => trans('_menu.questions'),
                'route_name' => 'admin.question.index',
                'access' => $this->checkPermission([
                    \App\Models\Question::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-book-open',
                'params' => [],
                'exact' => true,
                'title' => trans('_menu.question_subjects'),
                'route_name' => 'admin.question-subject.index',
                'access' => $this->checkPermission([
                    \App\Models\Question::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-star',
                'params' => [],
                'exact' => true,
                'title' => trans('_menu.question_competencies'),
                'route_name' => 'admin.question-competency.index',
                'access' => $this->checkPermission([
                    \App\Models\Question::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-star',
                'params' => [],
                'exact' => true,
                'title' => trans('_menu.question_system'),
                'route_name' => 'admin.question-system.index',
                'access' => $this->checkPermission([
                    \App\Models\QuestionSystem::class => 'Index',
                ]),
            ],
        ];
    }

    /**
     * Get HRM sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getHrmSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-user-group',
                'params' => [],
                'title' => trans('_menu.user_management'),
                'route_name' => 'admin.user.index',
                'access' => $this->checkPermission([
                    User::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-briefcase',
                'params' => [],
                'title' => trans('_menu.employee_management'),
                'route_name' => 'admin.employee.index',
                'access' => $this->checkPermission([
                    User::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-academic-cap',
                'params' => [],
                'title' => trans('_menu.teacher_management'),
                'route_name' => 'admin.teacher.index',
                'access' => $this->checkPermission([
                    User::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-heart',
                'params' => [],
                'title' => trans('_menu.parent_management'),
                'route_name' => 'admin.parent.index',
                'access' => $this->checkPermission([
                    User::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-key',
                'params' => [],
                'title' => trans('_menu.role_management'),
                'route_name' => 'admin.role.index',
                'access' => $this->checkPermission([
                    \App\Models\Role::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-banknotes',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.payroll_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'payroll'],
                'access' => $this->hasAccessToModule('hrm.payroll'),
            ],
            [
                'icon' => 'o-calendar-days',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.leave_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'leave'],
                'access' => $this->hasAccessToModule('hrm.leave'),
            ],
            [
                'icon' => 'o-chart-bar-square',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.performance_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'performance'],
                'access' => $this->hasAccessToModule('hrm.performance'),
            ],
            [
                'icon' => 'o-clock',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.time_tracking'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'time-tracking'],
                'access' => $this->hasAccessToModule('hrm.time_tracking'),
            ],
            [
                'icon' => 'o-document-text',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.contracts_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'contracts'],
                'access' => $this->hasAccessToModule('hrm.contracts'),
            ],
            [
                'icon' => 'o-folder-open',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.documents_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'documents'],
                'access' => $this->hasAccessToModule('hrm.documents'),
            ],
            [
                'icon' => 'o-user-circle',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.recruitment_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'recruitment'],
                'access' => $this->hasAccessToModule('hrm.recruitment'),
            ],
            [
                'icon' => 'o-light-bulb',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.training_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'training'],
                'access' => $this->hasAccessToModule('hrm.training'),
            ],
            [
                'icon' => 'o-calendar',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.scheduling_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'scheduling'],
                'access' => $this->hasAccessToModule('hrm.scheduling'),
            ],
        ];
    }

    /**
     * Get Accounting sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getAccountingSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-shopping-cart',
                'params' => [],
                'title' => trans('_menu.order_management'),
                'route_name' => 'admin.order.index',
                'access' => $this->checkPermission([
                    \App\Models\Order::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-credit-card',
                'params' => [],
                'title' => trans('_menu.payment_management'),
                'route_name' => 'admin.payment.index',
                'access' => $this->checkPermission([
                    \App\Models\Payment::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-document-duplicate',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.invoices_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'invoices'],
                'access' => $this->hasAccessToModule('accounting.invoices'),
            ],
            [
                'icon' => 'o-calendar-days',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.installments_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'installments'],
                'access' => $this->hasAccessToModule('accounting.installments'),
            ],
            [
                'icon' => 'o-arrow-trending-down',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.expenses_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'expenses'],
                'access' => $this->hasAccessToModule('accounting.expenses'),
            ],
            [
                'icon' => 'o-arrow-trending-up',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.income_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'income'],
                'access' => $this->hasAccessToModule('accounting.income'),
            ],
            [
                'icon' => 'o-presentation-chart-line',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.financial_reports'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'financial-reports'],
                'access' => $this->hasAccessToModule('accounting.financial_reports'),
            ],
            [
                'icon' => 'o-calculator',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.budget_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'budget'],
                'access' => $this->hasAccessToModule('accounting.budget'),
            ],
            [
                'icon' => 'o-arrows-right-left',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.cash_flow'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'cash-flow'],
                'access' => $this->hasAccessToModule('accounting.cash_flow'),
            ],
            [
                'icon' => 'o-clipboard-document-list',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.accounting_documents'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'accounting-documents'],
                'access' => $this->hasAccessToModule('accounting.accounting_documents'),
            ],
            [
                'icon' => 'o-receipt-percent',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.tax_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'tax'],
                'access' => $this->hasAccessToModule('accounting.tax'),
            ],
            [
                'icon' => 'o-inbox-stack',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.cashbox_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'cashbox'],
                'access' => $this->hasAccessToModule('accounting.cashbox'),
            ],
            [
                'icon' => 'o-building-library',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.banks_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'banks'],
                'access' => $this->hasAccessToModule('accounting.banks'),
            ],
        ];
    }

    /**
     * Get CRM sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getCrmSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-view-columns',
                'params' => [],
                'title' => trans('_menu.board_management'),
                'route_name' => 'admin.app.boards',
                'access' => false,
            ],
            [
                'icon' => 'o-ticket',
                'params' => [],
                'title' => trans('_menu.ticket_management'),
                'route_name' => 'admin.ticket-chat.index',
                'access' => true,
            ],
            [
                'icon' => 'o-chat-bubble-left-right',
                'params' => [],
                'title' => trans('_menu.comment_management'),
                'route_name' => 'admin.comment.index',
                'access' => $this->checkPermission([
                    \App\Models\Comment::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-envelope',
                'params' => [],
                'title' => trans('_menu.contact_us_management'),
                'route_name' => 'admin.contact-us.index',
                'access' => $this->checkPermission([
                    \App\Models\ContactUs::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-receipt-percent',
                'params' => [],
                'title' => trans('_menu.discount_management'),
                'route_name' => 'admin.discount.index',
                'access' => $this->checkPermission([
                    \App\Models\Discount::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-clipboard-document-list',
                'params' => [],
                'title' => trans('_menu.survey_management'),
                'route_name' => 'admin.survey.index',
                'access' => $this->checkPermission([
                    \App\Models\Exam::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-user-plus',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.leads_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'leads'],
                'access' => $this->hasAccessToModule('crm.leads'),
            ],
            [
                'icon' => 'o-megaphone',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.campaigns_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'campaigns'],
                'access' => $this->hasAccessToModule('crm.campaigns'),
            ],
            [
                'icon' => 'o-envelope-open',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.email_marketing'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'email-marketing'],
                'access' => $this->hasAccessToModule('crm.email_marketing'),
            ],
            [
                'icon' => 'o-device-phone-mobile',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.sms_marketing'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'sms-marketing'],
                'access' => $this->hasAccessToModule('crm.sms_marketing'),
            ],
            [
                'icon' => 'o-funnel',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.sales_pipeline'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'sales-pipeline'],
                'access' => $this->hasAccessToModule('crm.sales_pipeline'),
            ],
            [
                'icon' => 'o-clock',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.followups_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'followups'],
                'access' => $this->hasAccessToModule('crm.followups'),
            ],
            [
                'icon' => 'o-squares-2x2',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.segments_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'segments'],
                'access' => $this->hasAccessToModule('crm.segments'),
            ],
            [
                'icon' => 'o-chart-pie',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.crm_reports'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'crm-reports'],
                'access' => $this->hasAccessToModule('crm.crm_reports'),
            ],
        ];
    }

    /**
     * Get Content sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getContentSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-newspaper',
                'params' => [],
                'title' => trans('_menu.blog_management'),
                'route_name' => 'admin.blog.index',
                'access' => $this->checkPermission([
                    \App\Models\Blog::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-photo',
                'params' => [],
                'title' => trans('_menu.portfolio_management'),
                'route_name' => 'admin.portFolio.index',
                'access' => false,
            ],
            [
                'icon' => 'o-document-duplicate',
                'params' => [],
                'title' => trans('_menu.page_management'),
                'route_name' => 'admin.page.index',
                'access' => $this->checkPermission([
                    \App\Models\Page::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-folder',
                'params' => [],
                'title' => trans('_menu.category_management'),
                'route_name' => 'admin.category.index',
                'access' => $this->checkPermission([
                    \App\Models\Category::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-tag',
                'params' => [],
                'title' => trans('_menu.tag_management'),
                'route_name' => 'admin.tag.index',
                'access' => $this->checkPermission([
                    \App\Models\Tag::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-megaphone',
                'params' => [],
                'title' => trans('_menu.bulletin_management'),
                'route_name' => 'admin.bulletin.index',
                'access' => $this->checkPermission([
                    \App\Models\Bulletin::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-shield-check',
                'params' => [],
                'title' => trans('_menu.license_management'),
                'route_name' => 'admin.license.index',
                'access' => $this->checkPermission([
                    \App\Models\License::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-film',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.media_library'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'media-library'],
                'access' => $this->hasAccessToModule('content.media_library'),
            ],
            [
                'icon' => 'o-play',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.video_gallery'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'video-gallery'],
                'access' => $this->hasAccessToModule('content.video_gallery'),
            ],
            [
                'icon' => 'o-microphone',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.podcasts_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'podcasts'],
                'access' => $this->hasAccessToModule('content.podcasts'),
            ],
            [
                'icon' => 'o-book-open',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.ebooks_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'ebooks'],
                'access' => $this->hasAccessToModule('content.ebooks'),
            ],
            [
                'icon' => 'o-arrow-down-tray',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.downloads_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'downloads'],
                'access' => $this->hasAccessToModule('content.downloads'),
            ],
            [
                'icon' => 'o-document-duplicate',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.documentation'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'documentation'],
                'access' => $this->hasAccessToModule('content.documentation'),
            ],
            [
                'icon' => 'o-newspaper',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.newsletter_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'newsletter'],
                'access' => $this->hasAccessToModule('content.newsletter'),
            ],
        ];
    }

    /**
     * Get Base Management sub menu items
     *
     * @return array<int, array<string, mixed>>
     */
    private function getBaseManagementSubMenu(User $user): array
    {
        return [
            [
                'icon' => 'o-rectangle-stack',
                'params' => [],
                'title' => trans('_menu.slider_management'),
                'route_name' => 'admin.slider.index',
                'access' => $this->checkPermission([
                    \App\Models\Slider::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-question-mark-circle',
                'params' => [],
                'title' => trans('_menu.faq_management'),
                'route_name' => 'admin.faq.index',
                'access' => $this->checkPermission([
                    \App\Models\Faq::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-photo',
                'params' => [],
                'title' => trans('_menu.banner_management'),
                'route_name' => 'admin.banner.index',
                'access' => $this->checkPermission([
                    \App\Models\Banner::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-building-storefront',
                'params' => [],
                'title' => trans('_menu.client_management'),
                'route_name' => 'admin.client.index',
                'access' => $this->checkPermission([
                    \App\Models\Client::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-user-group',
                'params' => [],
                'title' => trans('_menu.teammate_management'),
                'route_name' => 'admin.teammate.index',
                'access' => $this->checkPermission([
                    \App\Models\Teammate::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-share',
                'params' => [],
                'title' => trans('_menu.social_media_management'),
                'route_name' => 'admin.social-media.index',
                'access' => $this->checkPermission([
                    \App\Models\SocialMedia::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-star',
                'params' => [],
                'title' => trans('_menu.opinion_management'),
                'route_name' => 'admin.opinion.index',
                'access' => $this->checkPermission([
                    \App\Models\Opinion::class => 'Index',
                ]),
            ],
            [
                'icon' => 'o-cog-8-tooth',
                'title' => trans('_menu.system_settings'),
                'route_name' => 'admin.setting',
                'params' => [],
                'access' => auth()->user()->hasPermissionTo(SharedPermissions::Admin),
            ],
            [
                'icon' => 'o-bell-alert',
                'title' => trans('_menu.notification_templates'),
                'route_name' => 'admin.notification-template.index',
                'params' => ['channel' => 'database', 'locale' => app()->getLocale()],
                'access' => $this->checkPermission([
                    fn (User $user) => $this->hasAccessToModule('base_settings.notification_templates'),
                ]),
            ],
            [
                'icon' => 'o-envelope',
                'title' => trans('_menu.email_templates'),
                'route_name' => 'admin.notification-template.index',
                'params' => ['channel' => 'email', 'locale' => app()->getLocale()],
                'access' => $this->checkPermission([
                    fn (User $user) => $this->hasAccessToModule('base_settings.email_templates'),
                ]),
            ],
            [
                'icon' => 'o-chat-bubble-bottom-center-text',
                'title' => trans('_menu.sms_templates'),
                'route_name' => 'admin.notification-template.index',
                'params' => ['channel' => 'sms', 'locale' => app()->getLocale()],
                'access' => $this->checkPermission([
                    fn (User $user) => $this->hasAccessToModule('base_settings.sms_templates'),
                ]),
            ],
            [
                'icon' => 'o-clipboard-document-list',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.activity_logs'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'activity-logs'],
                'access' => $this->hasAccessToModule('base_settings.activity_logs'),
            ],
            [
                'icon' => 'o-archive-box',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.backup_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'backup'],
                'access' => $this->hasAccessToModule('base_settings.backup'),
            ],
            [
                'icon' => 'o-language',
                'badge' => trans('_menu.future_module'),
                'title' => trans('_menu.languages_management'),
                'route_name' => 'admin.feature-module',
                'params' => ['module' => 'languages'],
                'access' => $this->hasAccessToModule('base_settings.languages'),
            ],
        ];
    }
}
