<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Services\Permissions\Models\BlogPermissions;
use App\Services\Permissions\Models\SharedPermissions;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function PHPUnit\Framework\assertNotNull;

uses()->group('blog');

it('delete blog', function (array $payload = [], array $expected = []) {
    $model = Blog::factory()->create([
        'id' => 1,
    ]);

    $response = login(permissions: Arr::get($payload, 'permissions', [SharedPermissions::Admin]))->deleteJson('api/blog/' . Arr::get($payload, 'id', 1));

    $response->assertStatus(Arr::get($expected, 'status', 200))
        ->when(Arr::has($expected, 'assertJsonStructure'))
        ->assertJsonStructure([
            'message',
            'data' => Arr::get($expected, 'assertJsonStructure', []),
        ])
        ->when(Arr::has($expected, 'assertJson'))
        ->assertJson(Arr::get($expected, 'assertJson'));

    if (Arr::get($expected, 'status') === 200 && Schema::hasColumn('blogs', 'deleted_at')) {
        assertDatabaseHas('blogs', [
            'id' => 1,
        ]);
        assertNotNull($model->deleted_at, 'blog field deleted_at is null');
    } elseif (Arr::get($expected, 'status') === 200) {
        assertDatabaseMissing('blogs', [
            'id' => 1,
        ]);
    } else {
        assertDatabaseHas('blogs', [
            'id' => 1,
        ]);
    }
})->with([
    'exist item'             => [
        'payload'  => ['id' => 1],
        'expected' => ['status' => 200, 'assertJsonStructure' => [], 'assertJson' => ['data' => true]],
    ],
    'not exist item'         => [
        'payload'  => ['id' => 2],
        'expected' => ['status' => 404],
    ],
    'have permission'        => [
        'payload'  => ['permissions' => [BlogPermissions::Delete]],
        'expected' => ['status' => 200, 'assertJsonStructure' => [], 'assertJson' => ['data' => true]],
    ],
    'doesnt have permission' => [
        'payload'  => ['permissions' => [], 'assertJsonStructure' => [], 'assertJson' => ['message']],
        'expected' => ['status' => 403],
    ],
])->skip();
