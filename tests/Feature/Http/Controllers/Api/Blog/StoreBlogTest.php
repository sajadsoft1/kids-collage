<?php

declare(strict_types=1);

use App\Services\Permissions\Models\BlogPermissions;
use App\Services\Permissions\Models\SharedPermissions;
use function Pest\Laravel\assertDatabaseHas;

uses()->group('blog');

it('create blog', function (array $payload = [], array $expected = []) {
    $data = array_merge([
        'title'       => 'test',
        'description' => 'test',
    ], $payload);

    $response = login(permissions: Arr::get($payload, 'permissions', [SharedPermissions::Admin]))->postJson('api/blog', $data);
    $result = $response->json();
    $status = Arr::get($expected, 'status', 201);

    $response
        ->assertStatus($status)
        ->when(Arr::has($expected, 'assertJsonStructure'))
        ->assertJsonStructure(Arr::get($expected, 'assertJsonStructure', []))
        ->when(Arr::has($expected, 'assertJson'))
        ->assertJson(Arr::get($expected, 'assertJson'));

    if ($status === 201) {
        assertDatabaseHas('blogs', [
            'id' => $result['data']['id']
        ]);

        // You have to watch the tables change
    }

})->with([
    'default data'                    => [
        'payload'  => [],
        'expected' => [
            'status'              => 201,
            'assertJsonStructure' => ['data' => [
                'id',
                'title',
                'description'
            ]],
            'assertJson'          => ['data' => [
                'id'          => 1,
                'title'       => 'test',
                'description' => 'test',
            ]
            ]
        ]
    ],
    'default data with permission'    => [
        'payload'  => ['permissions' => [BlogPermissions::Store]],
        'expected' => ['status' => 201]
    ],
    'default data without permission' => [
        'payload'  => ['permissions' => []],
        'expected' => ['status' => 403]
    ],
    'validation errors'               => [
        'payload'  => ['title' => '', 'description' => ''],
        'expected' => [
            'status'              => 422,
            'assertJsonStructure' => ['errors' => ['title', 'description']]
        ]
    ],
])->skip();
