<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
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

    private function buildPayload(NotificationEventEnum $event, NotificationChannelEnum $channel, string $locale): ?array
    {
        $title = $this->title($event, $locale);
        $subtitle = $this->subtitle($event, $locale);
        $body = $this->body($event, $channel, $locale);
        $placeholders = $this->placeholders($event);

        if ($title === null || $body === null) {
            return null;
        }

        return [
            'name' => sprintf('%s - %s (%s)', $title, $this->channelTitle($channel, $locale), strtoupper($locale)),
            'icon' => $this->icon($event),
            'subject' => $channel === NotificationChannelEnum::EMAIL ? $this->emailSubject($event, $locale) : null,
            'title' => in_array($channel, [NotificationChannelEnum::DATABASE, NotificationChannelEnum::EMAIL], true) ? $title : null,
            'subtitle' => $channel === NotificationChannelEnum::DATABASE ? $subtitle : null,
            'body' => $body,
            'cta' => $this->cta($channel, $locale),
            'placeholders' => $placeholders,
            'is_active' => true,
        ];
    }

    private function title(NotificationEventEnum $event, string $locale): ?string
    {
        return match ($event) {
            NotificationEventEnum::AUTH_REGISTER => $locale           === 'fa' ? 'ثبت‌نام موفق' : 'Registration Completed',
            NotificationEventEnum::AUTH_CONFIRM => $locale            === 'fa' ? 'تایید حساب کاربری' : 'Account Verification',
            NotificationEventEnum::AUTH_WELCOME => $locale            === 'fa' ? 'خوش آمدید' : 'Welcome Aboard',
            NotificationEventEnum::COURSE_SESSION_REMINDER => $locale === 'fa' ? 'یادآوری جلسه دوره' : 'Course Session Reminder',
            NotificationEventEnum::COURSE_SESSION_STARTED => $locale  === 'fa' ? 'شروع جلسه دوره' : 'Course Session Started',
            NotificationEventEnum::COURSE_SESSION_ENDED => $locale    === 'fa' ? 'پایان جلسه دوره' : 'Course Session Ended',
            NotificationEventEnum::SYSTEM_ALERT => $locale            === 'fa' ? 'هشدار سیستم' : 'System Alert',
            NotificationEventEnum::ANNOUNCEMENT => $locale            === 'fa' ? 'اطلاعیه جدید' : 'New Announcement',
            default => $event->value,
        };
    }

    private function subtitle(NotificationEventEnum $event, string $locale): ?string
    {
        return match ($event) {
            NotificationEventEnum::AUTH_REGISTER => $locale           === 'fa' ? 'ثبت‌نام شما با موفقیت انجام شد.' : 'Your registration is complete.',
            NotificationEventEnum::AUTH_CONFIRM => $locale            === 'fa' ? 'کد تایید را وارد کنید تا حساب شما فعال شود.' : 'Use the verification code to activate your account.',
            NotificationEventEnum::AUTH_WELCOME => $locale            === 'fa' ? 'برای شروع وارد حساب کاربری خود شوید.' : 'Sign in to start exploring.',
            NotificationEventEnum::COURSE_SESSION_REMINDER => $locale === 'fa' ? 'یادآوری جلسه پیش رو برای دوره شما.' : 'Upcoming session reminder for your course.',
            NotificationEventEnum::COURSE_SESSION_STARTED => $locale  === 'fa' ? 'جلسه آغاز شده است.' : 'Session is now live.',
            NotificationEventEnum::COURSE_SESSION_ENDED => $locale    === 'fa' ? 'جلسه به پایان رسید.' : 'Session has finished.',
            NotificationEventEnum::SYSTEM_ALERT => $locale            === 'fa' ? 'لطفاً این هشدار را بررسی کنید.' : 'Please review this alert.',
            NotificationEventEnum::ANNOUNCEMENT => $locale            === 'fa' ? 'اطلاعیه جدید منتشر شد.' : 'A new announcement has been published.',
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

    private function body(NotificationEventEnum $event, NotificationChannelEnum $channel, string $locale): ?string
    {
        if ($channel === NotificationChannelEnum::SMS) {
            return $this->smsBody($event, $locale);
        }

        return match ($event) {
            NotificationEventEnum::COURSE_SESSION_REMINDER => $locale === 'fa'
                ? 'سلام {{user_name}} عزیز، این یک یادآوری برای جلسه {{course_title}} در تاریخ {{session_date}} ساعت {{session_time}} است.'
                : 'Hi {{user_name}}, this is a reminder for {{course_title}} on {{session_date}} at {{session_time}}.',
            NotificationEventEnum::COURSE_SESSION_STARTED => $locale === 'fa'
                ? 'جلسه {{course_title}} اکنون آغاز شده است. برای حضور از طریق لینک اقدام کنید: {{action_url}}'
                : '{{course_title}} has just started. Join via {{action_url}}.',
            NotificationEventEnum::COURSE_SESSION_ENDED => $locale === 'fa'
                ? 'جلسه {{course_title}} به پایان رسید. می‌توانید جزئیات را در لینک زیر مشاهده کنید: {{action_url}}'
                : '{{course_title}} has ended. Review the details here: {{action_url}}.',
            NotificationEventEnum::SYSTEM_ALERT => $locale === 'fa'
                ? 'هشدار سیستم: {{alert_message}}. برای مشاهده جزئیات از لینک زیر استفاده کنید: {{action_url}}'
                : 'System alert: {{alert_message}}. See more information here: {{action_url}}.',
            NotificationEventEnum::ANNOUNCEMENT => $locale === 'fa'
                ? 'اعلان جدید: {{announcement_title}}. متن کامل: {{announcement_body}}'
                : 'New announcement: {{announcement_title}}. Full text: {{announcement_body}}.',
            NotificationEventEnum::AUTH_REGISTER => $locale === 'fa'
                ? 'سلام {{user_name}} عزیز، ثبت‌نام شما تکمیل شد. برای ورود از لینک زیر استفاده کنید: {{action_url}}'
                : 'Hi {{user_name}}, your registration is complete. Sign in here: {{action_url}}.',
            NotificationEventEnum::AUTH_CONFIRM => $locale === 'fa'
                ? 'سلام {{user_name}} عزیز، کد تایید شما {{verification_code}} است. در صورت نیاز می‌توانید از لینک زیر اقدام کنید: {{action_url}}'
                : 'Hi {{user_name}}, your verification code is {{verification_code}}. Need help? Visit: {{action_url}}.',
            NotificationEventEnum::AUTH_WELCOME => $locale === 'fa'
                ? 'سلام {{user_name}} عزیز، به خانواده کیدز کالج خوش آمدید. برای شروع از لینک زیر استفاده کنید: {{action_url}}'
                : 'Hi {{user_name}}, welcome to Kids Collage! Start here: {{action_url}}.',
            default => $locale === 'fa' ? '{{message}}' : '{{message}}',
        };
    }

    private function smsBody(NotificationEventEnum $event, string $locale): string
    {
        return match ($event) {
            NotificationEventEnum::COURSE_SESSION_REMINDER => $locale === 'fa'
                ? 'یادآوری جلسه {{course_title}} در تاریخ {{session_date}} ساعت {{session_time}}.'
                : 'Reminder: {{course_title}} on {{session_date}} at {{session_time}}.',
            NotificationEventEnum::SYSTEM_ALERT => $locale === 'fa'
                ? 'هشدار سیستم: {{alert_message}}.'
                : 'System alert: {{alert_message}}.',
            NotificationEventEnum::AUTH_REGISTER => $locale === 'fa'
                ? 'ثبت‌نام شما تکمیل شد. ورود: {{action_url}}'
                : 'Registration done. Login: {{action_url}}',
            NotificationEventEnum::AUTH_CONFIRM => $locale === 'fa'
                ? 'کد تایید: {{verification_code}}'
                : 'Verify code: {{verification_code}}',
            NotificationEventEnum::AUTH_WELCOME => $locale === 'fa'
                ? 'به کیدز کالج خوش آمدید!'
                : 'Welcome to Kids Collage!',
            default => $locale === 'fa' ? '{{message}}' : '{{message}}',
        };
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
            NotificationEventEnum::AUTH_WELCOME => [
                'user_name',
                'action_url',
                'message',
            ],
            NotificationEventEnum::COURSE_SESSION_REMINDER => [
                'user_name',
                'course_title',
                'session_date',
                'session_time',
                'action_url',
            ],
            NotificationEventEnum::COURSE_SESSION_STARTED,
            NotificationEventEnum::COURSE_SESSION_ENDED => [
                'course_title',
                'action_url',
            ],
            NotificationEventEnum::SYSTEM_ALERT => [
                'alert_message',
                'action_url',
            ],
            NotificationEventEnum::ANNOUNCEMENT => [
                'announcement_title',
                'announcement_body',
                'action_url',
            ],
            default => [
                'message',
                'action_url',
            ],
        };
    }

    private function icon(NotificationEventEnum $event): ?string
    {
        return match ($event) {
            NotificationEventEnum::AUTH_REGISTER => 'o-user-plus',
            NotificationEventEnum::AUTH_CONFIRM => 'o-lock-closed',
            NotificationEventEnum::AUTH_WELCOME => 'o-sparkles',
            NotificationEventEnum::COURSE_SESSION_REMINDER => 'o-calendar-days',
            NotificationEventEnum::COURSE_SESSION_STARTED => 'o-play-circle',
            NotificationEventEnum::COURSE_SESSION_ENDED => 'o-check-circle',
            NotificationEventEnum::SYSTEM_ALERT => 'o-exclamation-triangle',
            NotificationEventEnum::ANNOUNCEMENT => 'o-megaphone',
            default => 'o-bell',
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

    private function channelTitle(NotificationChannelEnum $channel, string $locale): string
    {
        return match ($channel) {
            NotificationChannelEnum::SMS => $locale      === 'fa' ? 'پیامک' : 'SMS',
            NotificationChannelEnum::EMAIL => $locale    === 'fa' ? 'ایمیل' : 'Email',
            NotificationChannelEnum::DATABASE => $locale === 'fa' ? 'داخلی' : 'In-App',
            default => ucfirst($channel->value),
        };
    }
}
