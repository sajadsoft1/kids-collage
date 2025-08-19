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
                'subject'    => 'مشکل در تأخیر اسپری تأخیری',
                'body'       => 'سلام، من اسپری تأخیری شما رو تهیه کردم اما به نظر میاد که اثرگذاریش کمتر از چیزی هست که انتظار داشتم. طبق دستورالعمل ازش استفاده کردم، ولی تغییر زیادی حس نکردم.آیا نیاز هست مقدار بیشتری استفاده کنم یا نکته‌ای هست که باید رعایت کنم؟ ممنون می‌شم راهنمایی کنید.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id'    => 2,
                'closed_by'  => null,
                'status'     => TicketStatusEnum::OPEN,
                'priority'   => TicketPriorityEnum::CRITICAL,
            ],
            [
                'subject'    => 'حساسیت به ژل روان‌کننده',
                'body'       => 'سلام، من از ژل روان‌کننده‌ای که خریدم استفاده کردم ولی بعد از چند دقیقه احساس سوزش و خارش داشتم.آیا ممکنه به یکی از ترکیباتش حساسیت داشته باشم؟ ترکیبات اصلی محصول رو می‌تونید بگید و پیشنهاد بدید از چه نوع ژلی استفاده کنم که ملایم‌تر باشه؟',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id'    => 3,
                'closed_by'  => null,
                'status'     => TicketStatusEnum::OPEN,
                'priority'   => TicketPriorityEnum::HIGH,
            ],
            [
                'subject'    => 'تأخیر در ارسال سفارش محصولات زناشویی',
                'body'       => 'سلام، من چند روز پیش سفارشم رو ثبت کردم ولی هنوز کد رهگیری دریافت نکردم.می‌خواستم بدونم سفارش من در چه مرحله‌ای هست و چه زمانی به دستم می‌رسه؟ لطفاً در مورد ارسال توضیح بدید که آیا امکان ارسال سریع‌تر هم وجود داره؟',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id'    => 2,
                'closed_by'  => null,
                'status'     => TicketStatusEnum::OPEN,
                'priority'   => TicketPriorityEnum::MEDIUM,
            ],
            [
                'subject'    => 'مشاوره برای انتخاب بهترین محصول جنسی',
                'body'       => 'سلام، من دنبال محصولی هستم که بتونه تجربه بهتری در رابطه به من بده ولی نمی‌دونم دقیقاً کدوم گزینه برای من مناسبه.مثلاً بین کاندوم‌های تاخیری و اسپری تأخیری شک دارم یا نمی‌دونم روان‌کننده‌های بر پایه آب بهترن یا سیلیکونی.ممنون می‌شم راهنمایی کنید که بهترین گزینه برای شرایط من چیه.',
                'department' => TicketDepartmentEnum::TECHNICAL,
                'user_id'    => 3,
                'closed_by'  => null,
                'status'     => TicketStatusEnum::OPEN,
                'priority'   => TicketPriorityEnum::LOW,
            ]] as $row) {
            /** @var Ticket $ticket */
            $ticket = StoreTicketAction::run([
                'subject'    => $row['subject'],
                'body'       => $row['body'],
                'department' => $row['department'],
                'user_id'    => $row['user_id'],
                'closed_by'  => $row['closed_by'],
                'status'     => $row['status'],
                'priority'   => $row['priority'],
            ]);

            $message = StoreTicketMessageAction::run([
                'ticket_id' => $ticket->id,
                'user_id'   => 2,
                'message'   => 'به مشکل شما در اسرع وقت رسیدگی خواهد شد',
            ]);

            $message->addMedia(public_path('assets/images/default/user-avatar.png'))
                ->preservingOriginal()
                ->toMediaCollection('gallery');
        }
    }
}
