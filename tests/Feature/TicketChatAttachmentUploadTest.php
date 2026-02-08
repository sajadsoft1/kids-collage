<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Karnoweb\TicketChat\Facades\Ticket;
use Karnoweb\TicketChat\Models\Attachment;
use Karnoweb\TicketChat\Services\AttachmentService;
use Tests\TestCase;

class TicketChatAttachmentUploadTest extends TestCase
{
    public function test_attachment_upload_stores_file_on_configured_disk(): void
    {
        $disk = config('ticket-chat.attachments.disk', 'public');
        Storage::fake($disk);

        $user = User::query()->first();
        if ( ! $user) {
            $this->markTestSkipped('No user in database.');
        }

        $conversation = Ticket::create([
            'title' => 'Test ticket for attachment',
            'created_by' => $user->id,
        ]);

        $message = Ticket::reply($conversation, (int) $user->id, 'Test message');

        $file = UploadedFile::fake()->create('test-document.txt', 1, 'text/plain');

        $attachmentService = app(AttachmentService::class);
        $attachment = $attachmentService->upload($message, $file);

        $this->assertInstanceOf(Attachment::class, $attachment);
        $this->assertSame($message->id, $attachment->message_id);
        $this->assertSame($disk, $attachment->disk);
        $this->assertSame('test-document.txt', $attachment->file_name);
        $this->assertSame('text/plain', $attachment->mime_type);
        $this->assertSame($file->getSize(), $attachment->size);

        Storage::disk($disk)->assertExists($attachment->file_path);
    }

    public function test_attachment_upload_with_image_stores_on_disk(): void
    {
        $disk = config('ticket-chat.attachments.disk', 'public');
        Storage::fake($disk);

        $user = User::query()->first();
        if ( ! $user) {
            $this->markTestSkipped('No user in database.');
        }

        $conversation = Ticket::create([
            'title' => 'Test ticket for image',
            'created_by' => $user->id,
        ]);

        $message = Ticket::reply($conversation, (int) $user->id, 'Message with image');

        $file = UploadedFile::fake()->image('screenshot.png', 200, 200);

        $attachmentService = app(AttachmentService::class);
        $attachment = $attachmentService->upload($message, $file);

        $this->assertInstanceOf(Attachment::class, $attachment);
        $this->assertSame($message->id, $attachment->message_id);
        Storage::disk($disk)->assertExists($attachment->file_path);
        $this->assertDatabaseHas(
            (new Attachment)->getTable(),
            [
                'message_id' => $message->id,
                'file_name' => 'screenshot.png',
                'disk' => $disk,
            ]
        );
    }
}
