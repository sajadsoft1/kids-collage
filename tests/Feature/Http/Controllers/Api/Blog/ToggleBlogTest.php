<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Services\Permissions\Models\BlogPermissions;
use App\Services\Permissions\Models\SharedPermissions;
use function Pest\Laravel\assertDatabaseHas;

uses()->group('blog');

it('toggle blog', function (array $payload = [], array $expected = []) {
    Blog::factory()->create([
        'id'        => 1,
        'published' => false
    ]);

    $response = login(permissions: Arr::get($payload, 'permissions', [SharedPermissions::Admin]))->getJson('api/blog/toggle/' . Arr::get($payload, 'id', 1));
    $response->assertStatus(Arr::get($expected, 'status', 200))
             ->when(Arr::has($expected, 'assertJsonStructure'))
             ->assertJsonStructure([
                 'message',
                 'data' => Arr::get($expected, 'assertJsonStructure', []),
             ])
             ->when(Arr::has($expected, 'assertJson'))
             ->assertJson(Arr::get($expected, 'assertJson'));

    if (Arr::get($expected, 'status') === 200) {
        assertDatabaseHas('blogs', [
            'id'        => 1,
            'published' => true
        ]);
    } else {
        assertDatabaseHas('blogs', [
            'id'        => 1,
            'published' => false
        ]);
    }

})->with([
    'exist item'             => [
        'payload'  => ['id' => 1],
        'expected' => ['status' => 200, 'assertJsonStructure' => ['id'], 'assertJson' => ['data' => ['id' => 1]]],
    ],
    'not exist item'         => [
        'payload'  => ['id' => 2],
        'expected' => ['status' => 404],
    ],
    'have permission'        => [
        'payload'  => ['permissions' => [BlogPermissions::Update]],
        'expected' => ['status' => 200, 'assertJsonStructure' => ['id'], 'assertJson' => ['data' => ['id' => 1]]],
    ],
    'doesnt have permission' => [
        'payload'  => ['permissions' => [], 'assertJsonStructure' => [], 'assertJson' => ['message']],
        'expected' => ['status' => 403],
    ]
])->skip();
