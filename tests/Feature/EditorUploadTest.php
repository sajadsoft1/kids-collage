<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditorUploadTest extends TestCase
{
    public function test_mary_editor_upload_returns_location_when_authenticated(): void
    {
        $disk = config('filesystems.editor_disk', 'tinymce');
        Storage::fake($disk);

        $user = User::query()->first();
        if ( ! $user) {
            $this->markTestSkipped('No user in database.');
        }

        $file = UploadedFile::fake()->image('editor-image.png', 100, 100);

        $response = $this->actingAs($user)
            ->post(route('mary.upload'), [
                'file' => $file,
                'disk' => $disk,
                'folder' => 'lilingo/editor',
            ]);

        $response->assertOk();
        $response->assertJsonStructure(['location']);
        $this->assertNotEmpty($response->json('location'));

        // At least one file should exist on the disk (under lilingo/editor/)
        $files = Storage::disk($disk)->allFiles('lilingo/editor');
        $this->assertGreaterThanOrEqual(1, count($files));
    }
}
