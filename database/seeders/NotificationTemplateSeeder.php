<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    private array $templates = [];

    public function run(): void
    {
        $this->loadTemplates();

        $blueprint = config('notification_channels');
        $defaultChannels = $blueprint['defaults']['channels'] ?? [];
        $overrides = $blueprint['events'] ?? [];

        $locales = ['fa', 'en'];

        foreach (NotificationEventEnum::cases() as $event) {
            $channels = array_replace(
                $defaultChannels,
                $overrides[$event->value]['channels'] ?? []
            );

            foreach ($channels as $channelKey => $meta) {
                $channelEnum = NotificationChannelEnum::tryFrom($channelKey);

                if ($channelEnum === null || $channelEnum->isFutureChannel()) {
                    continue;
                }

                foreach ($locales as $locale) {
                    $payload = $this->buildPayload($event, $channelEnum, $locale);

                    if ($payload === null) {
                        continue;
                    }

                    NotificationTemplate::query()->updateOrCreate(
                        [
                            'event' => $event->value,
                            'channel' => $channelEnum->value,
                            'locale' => $locale,
                        ],
                        $payload
                    );
                }
            }
        }
    }

    private function loadTemplates(): void
    {
        $templateFiles = [
            'email_fa' => database_path('seeders/data/karno_notification_template_email_fa.php'),
            'email_en' => database_path('seeders/data/karno_notification_template_email_en.php'),
            'sms_fa' => database_path('seeders/data/karno_notification_template_sms_fa.php'),
            'sms_en' => database_path('seeders/data/karno_notification_template_sms_en.php'),
            'database_fa' => database_path('seeders/data/karno_notification_template_database_fa.php'),
            'database_en' => database_path('seeders/data/karno_notification_template_database_en.php'),
        ];

        foreach ($templateFiles as $key => $file) {
            if (file_exists($file)) {
                $data = require $file;
                $this->templates[$key] = $data['data'] ?? [];
            }
        }
    }

    private function getTemplate(NotificationEventEnum $event, NotificationChannelEnum $channel, string $locale): ?array
    {
        $templateKey = match ($channel) {
            NotificationChannelEnum::EMAIL => $locale    === 'fa' ? 'email_fa' : 'email_en',
            NotificationChannelEnum::SMS => $locale      === 'fa' ? 'sms_fa' : 'sms_en',
            NotificationChannelEnum::DATABASE => $locale === 'fa' ? 'database_fa' : 'database_en',
            default => null,
        };

        if ($templateKey === null || ! isset($this->templates[$templateKey])) {
            return null;
        }

        foreach ($this->templates[$templateKey] as $template) {
            if ($template['event'] === $event && $template['channel'] === $channel) {
                return $template['data'][$locale] ?? null;
            }
        }

        return null;
    }

    private function buildPayload(NotificationEventEnum $event, NotificationChannelEnum $channel, string $locale): ?array
    {
        $template = $this->getTemplate($event, $channel, $locale);

        if ($template === null) {
            return null;
        }

        $title = $template['title'] ?? $this->title($event, $locale);
        $subtitle = $template['subtitle'] ?? $this->subtitle($event, $locale);
        $body = $template['body'] ?? null;
        $placeholders = $template['placeholders'] ?? $this->placeholders($event);
        $cta = $template['cta'] ?? $this->cta($channel, $locale);
        $subject = $template['subject'] ?? ($channel === NotificationChannelEnum::EMAIL ? $this->emailSubject($event, $locale) : null);

        if ($body === null) {
            return null;
        }

        return [
            'subject' => $subject,
            'title' => in_array($channel, [NotificationChannelEnum::DATABASE, NotificationChannelEnum::EMAIL], true) ? $title : null,
            'subtitle' => $channel === NotificationChannelEnum::DATABASE ? $subtitle : null,
            'body' => $body,
            'cta' => $cta,
            'placeholders' => $placeholders,
            'is_active' => true,
        ];
    }

    private function title(NotificationEventEnum $event, string $locale): ?string
    {
        return match ($event) {
            NotificationEventEnum::AUTH_REGISTER => $locale           === 'fa' ? 'ثبت‌نام موفق' : 'Registration Completed',
            NotificationEventEnum::AUTH_CONFIRM => $locale            === 'fa' ? 'تایید حساب کاربری' : 'Account Verification',
            NotificationEventEnum::AUTH_FORGOT_PASSWORD => $locale    === 'fa' ? 'بازیابی رمز عبور' : 'Password Reset',
            NotificationEventEnum::AUTH_WELCOME => $locale            === 'fa' ? 'خوش آمدید' : 'Welcome Aboard',
            NotificationEventEnum::ORDER_CREATED => $locale           === 'fa' ? 'سفارش جدید' : 'New Order',
            NotificationEventEnum::ORDER_PAID => $locale              === 'fa' ? 'پرداخت سفارش' : 'Order Paid',
            NotificationEventEnum::ORDER_CANCELLED => $locale         === 'fa' ? 'لغو سفارش' : 'Order Cancelled',
            NotificationEventEnum::PAYMENT_SUCCESS => $locale         === 'fa' ? 'پرداخت موفق' : 'Payment Successful',
            NotificationEventEnum::PAYMENT_FAILED => $locale          === 'fa' ? 'پرداخت ناموفق' : 'Payment Failed',
            NotificationEventEnum::ENROLLMENT_CREATED => $locale      === 'fa' ? 'ثبت‌نام در دوره' : 'Course Enrollment',
            NotificationEventEnum::ENROLLMENT_APPROVED => $locale     === 'fa' ? 'تایید ثبت‌نام' : 'Enrollment Approved',
            NotificationEventEnum::ENROLLMENT_REJECTED => $locale     === 'fa' ? 'رد ثبت‌نام' : 'Enrollment Rejected',
            NotificationEventEnum::COURSE_SESSION_REMINDER => $locale === 'fa' ? 'یادآوری جلسه دوره' : 'Course Session Reminder',
            NotificationEventEnum::COURSE_SESSION_STARTED => $locale  === 'fa' ? 'شروع جلسه دوره' : 'Course Session Started',
            NotificationEventEnum::COURSE_SESSION_ENDED => $locale    === 'fa' ? 'پایان جلسه دوره' : 'Course Session Ended',
            NotificationEventEnum::SYSTEM_ALERT => $locale            === 'fa' ? 'هشدار سیستم' : 'System Alert',
            NotificationEventEnum::ANNOUNCEMENT => $locale            === 'fa' ? 'اطلاعیه جدید' : 'New Announcement',
            NotificationEventEnum::BIRTHDAY_REMINDER => $locale       === 'fa' ? 'تولدت مبارک!' : 'Happy Birthday!',
            default => $event->value,
        };
    }

    private function subtitle(NotificationEventEnum $event, string $locale): ?string
    {
        return match ($event) {
            NotificationEventEnum::AUTH_REGISTER => $locale           === 'fa' ? 'ثبت‌نام شما با موفقیت انجام شد.' : 'Your registration is complete.',
            NotificationEventEnum::AUTH_CONFIRM => $locale            === 'fa' ? 'کد تایید را وارد کنید تا حساب شما فعال شود.' : 'Use the verification code to activate your account.',
            NotificationEventEnum::AUTH_FORGOT_PASSWORD => $locale    === 'fa' ? 'درخواست بازیابی رمز عبور شما' : 'Password reset request',
            NotificationEventEnum::AUTH_WELCOME => $locale            === 'fa' ? 'برای شروع وارد حساب کاربری خود شوید.' : 'Sign in to start exploring.',
            NotificationEventEnum::ORDER_CREATED => $locale           === 'fa' ? 'سفارش شما با موفقیت ثبت شد' : 'Your order has been placed successfully',
            NotificationEventEnum::ORDER_PAID => $locale              === 'fa' ? 'پرداخت سفارش شما با موفقیت انجام شد' : 'Your order payment has been completed',
            NotificationEventEnum::ORDER_CANCELLED => $locale         === 'fa' ? 'سفارش شما لغو شد' : 'Your order has been cancelled',
            NotificationEventEnum::PAYMENT_SUCCESS => $locale         === 'fa' ? 'پرداخت شما با موفقیت انجام شد' : 'Your payment has been completed',
            NotificationEventEnum::PAYMENT_FAILED => $locale          === 'fa' ? 'پرداخت شما انجام نشد' : 'Your payment was not completed',
            NotificationEventEnum::ENROLLMENT_CREATED => $locale      === 'fa' ? 'درخواست ثبت‌نام شما ثبت شد' : 'Your enrollment request has been submitted',
            NotificationEventEnum::ENROLLMENT_APPROVED => $locale     === 'fa' ? 'ثبت‌نام شما در دوره تایید شد' : 'Your course enrollment has been approved',
            NotificationEventEnum::ENROLLMENT_REJECTED => $locale     === 'fa' ? 'ثبت‌نام شما در دوره رد شد' : 'Your course enrollment has been rejected',
            NotificationEventEnum::COURSE_SESSION_REMINDER => $locale === 'fa' ? 'یادآوری جلسه پیش رو برای دوره شما.' : 'Upcoming session reminder for your course.',
            NotificationEventEnum::COURSE_SESSION_STARTED => $locale  === 'fa' ? 'جلسه آغاز شده است.' : 'Session is now live.',
            NotificationEventEnum::COURSE_SESSION_ENDED => $locale    === 'fa' ? 'جلسه به پایان رسید.' : 'Session has finished.',
            NotificationEventEnum::SYSTEM_ALERT => $locale            === 'fa' ? 'لطفاً این هشدار را بررسی کنید.' : 'Please review this alert.',
            NotificationEventEnum::ANNOUNCEMENT => $locale            === 'fa' ? 'اطلاعیه جدید منتشر شد.' : 'A new announcement has been published.',
            NotificationEventEnum::BIRTHDAY_REMINDER => $locale       === 'fa' ? '{{user_name}} عزیز، تولدت مبارک!' : 'Happy Birthday, {{user_name}}!',
            default => null,
        };
    }

    private function emailSubject(NotificationEventEnum $event, string $locale): string
    {
        $title = $this->title($event, $locale);

        return $locale === 'fa'
            ? sprintf('کیدز کالج | %s', $title)
            : sprintf('Kids Collage | %s', $title);
    }

    private function placeholders(NotificationEventEnum $event): array
    {
        return match ($event) {
            NotificationEventEnum::AUTH_REGISTER => [
                'user_name',
                'action_url',
                'message',
            ],
            NotificationEventEnum::AUTH_CONFIRM => [
                'user_name',
                'verification_code',
                'action_url',
                'message',
            ],
            NotificationEventEnum::AUTH_FORGOT_PASSWORD => [
                'user_name',
                'reset_password_url',
            ],
            NotificationEventEnum::AUTH_WELCOME => [
                'user_name',
                'action_url',
                'message',
            ],
            NotificationEventEnum::ORDER_CREATED => [
                'user_name',
                'order_number',
                'order_amount',
                'order_date',
                'action_url',
            ],
            NotificationEventEnum::ORDER_PAID => [
                'user_name',
                'order_number',
                'payment_amount',
                'transaction_id',
                'payment_date',
                'action_url',
            ],
            NotificationEventEnum::ORDER_CANCELLED => [
                'user_name',
                'order_number',
                'cancellation_reason',
                'action_url',
            ],
            NotificationEventEnum::PAYMENT_SUCCESS => [
                'user_name',
                'payment_amount',
                'transaction_id',
                'payment_date',
                'payment_method',
                'action_url',
            ],
            NotificationEventEnum::PAYMENT_FAILED => [
                'user_name',
                'failure_reason',
                'action_url',
            ],
            NotificationEventEnum::ENROLLMENT_CREATED => [
                'user_name',
                'course_title',
                'action_url',
            ],
            NotificationEventEnum::ENROLLMENT_APPROVED => [
                'user_name',
                'course_title',
                'action_url',
            ],
            NotificationEventEnum::ENROLLMENT_REJECTED => [
                'user_name',
                'course_title',
                'rejection_reason',
                'action_url',
            ],
            NotificationEventEnum::COURSE_SESSION_REMINDER => [
                'user_name',
                'course_title',
                'session_date',
                'session_time',
                'session_duration',
                'action_url',
            ],
            NotificationEventEnum::COURSE_SESSION_STARTED,
            NotificationEventEnum::COURSE_SESSION_ENDED => [
                'user_name',
                'course_title',
                'action_url',
            ],
            NotificationEventEnum::SYSTEM_ALERT => [
                'user_name',
                'alert_title',
                'alert_message',
                'action_url',
            ],
            NotificationEventEnum::ANNOUNCEMENT => [
                'user_name',
                'announcement_title',
                'announcement_body',
                'action_url',
            ],
            NotificationEventEnum::BIRTHDAY_REMINDER => [
                'user_name',
                'birthday_gift',
                'action_url',
            ],
            default => [
                'message',
                'action_url',
            ],
        };
    }

    private function cta(NotificationChannelEnum $channel, string $locale): ?array
    {
        if ( ! in_array($channel, [NotificationChannelEnum::DATABASE, NotificationChannelEnum::EMAIL], true)) {
            return null;
        }

        return [
            'label' => $locale === 'fa' ? 'مشاهده جزئیات' : 'View details',
            'url' => '{{action_url}}',
        ];
    }
}
