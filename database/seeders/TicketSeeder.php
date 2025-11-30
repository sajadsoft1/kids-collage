<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Ticket\StoreTicketAction;
use App\Actions\TicketMessage\StoreTicketMessageAction;
use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        foreach ([
            [
                'subject' => 'نمی‌توانم در سایت ثبت‌نام کنم',
                'body' => 'سلام، در هنگام ثبت‌نام در سایت با پیغام خطا مواجه می‌شوم. لطفاً راهنمایی فرمایید چگونه ثبت‌نام را با موفقیت انجام دهم.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 2,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::HIGH,
            ],
            [
                'subject' => 'مراحل تکمیل ثبت‌نام در سایت چگونه است؟',
                'body' => 'سلام، می‌خواهم ثبت‌نام کنم اما نمی‌دانم دقیقا چه مراحلی را باید طی کنم. لطفاً توضیح دهید.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 3,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::MEDIUM,
            ],
            [
                'subject' => 'کد تایید ثبت‌نام را دریافت نکرده‌ام',
                'body' => 'سلام، پس از ثبت‌نام در سایت کد تایید برای من ارسال نشد. چه کار باید بکنم؟',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 4,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::CRITICAL,
            ],
            [
                'subject' => 'فراموشی رمز عبور در مرحله ثبت‌نام',
                'body' => 'سلام، در زمان ثبت‌نام رمز عبورم را فراموش کرده‌ام. چگونه می‌توانم آن را بازیابی کنم؟',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 5,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::MEDIUM,
            ],
            [
                'subject' => 'چه روش‌هایی برای پرداخت وجود دارد؟',
                'body' => 'سلام، لطفاً روش‌های پرداخت قابل قبول در سایت را معرفی کنید تا بتوانم سفارش خود را تکمیل کنم.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 2,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::HIGH,
            ],
            [
                'subject' => 'درگاه پرداخت کار نمی‌کند',
                'body' => 'سلام، هنگام پرداخت وجه با درگاه بانکی سایت مشکل دارم و تراکنش انجام نمی‌شود. لطفا پیگیری نمایید.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 3,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::CRITICAL,
            ],
            [
                'subject' => 'آیا امکان پرداخت در محل وجود دارد؟',
                'body' => 'سلام، می‌خواستم بدانم آیا می‌توان هزینه سفارش را هنگام تحویل پرداخت کرد یا فقط به صورت آنلاین امکان‌پذیر است؟',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 4,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::LOW,
            ],
            [
                'subject' => 'مشکل در بازپرداخت مبلغ سفارش',
                'body' => 'سلام، پرداخت من انجام شده اما در سایت به عنوان ناموفق ثبت شده است. لطفاً بررسی و پیگیری نمایید.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 5,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::HIGH,
            ],
            [
                'subject' => 'تغییر روش پرداخت بعد از ثبت سفارش',
                'body' => 'سلام، یک سفارش ثبت کرده‌ام اما می‌خواهم روش پرداخت را تغییر دهم. آیا این امکان وجود دارد؟',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 2,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::MEDIUM,
            ],
            [
                'subject' => 'مدارک مورد نیاز برای پرداخت',
                'body' => 'سلام، آیا برای پرداخت اینترنتی نیاز به ارائه مدارک خاصی است؟ لطفا راهنمایی فرمایید.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id' => 3,
                'closed_by' => null,
                'status' => TicketStatusEnum::OPEN,
                'priority' => TicketPriorityEnum::LOW,
            ],
        ] as $row) {
            /** @var Ticket $ticket */
            $ticket = StoreTicketAction::run([
                'subject' => $row['subject'],
                'body' => $row['body'],
                'department' => $row['department'],
                'user_id' => $row['user_id'],
                'closed_by' => $row['closed_by'],
                'status' => $row['status'],
                'priority' => $row['priority'],
            ]);

            $message = StoreTicketMessageAction::run([
                'ticket_id' => $ticket->id,
                'user_id' => 2,
                'message' => 'پاسخ شما: لطفا پس از بررسی موارد بالا، در صورت داشتن سوال بیشتر با پشتیبانی تماس بگیرید.',
            ]);

            $message->addMedia(public_path('assets/images/default/user-avatar.png'))
                ->preservingOriginal()
                ->toMediaCollection('gallery');
        }
    }
}
