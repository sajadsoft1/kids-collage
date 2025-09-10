<?php

declare(strict_types=1);

use App\Services\Permissions\Models\SharedPermissions;

uses()->group('blog');

test('data blog', function (array $payload = [], array $expected = []) {
    $response = login(permissions: Arr::get($payload, 'permissions', [SharedPermissions::Admin]))->getJson('api/blog/data');

    $response->assertStatus(Arr::get($expected, 'status', 200))
        ->when(Arr::has($expected, 'assertJsonStructure'))
        ->assertJsonStructure(Arr::get($expected, 'assertJsonStructure', []))
        ->when(Arr::has($expected, 'assertJson'))
        ->assertJson(Arr::get($expected, 'assertJson'));
})->with([
    'have permission'        => [
        'payload'  => ['permissions' => [SharedPermissions::Admin]],
        'expected' => ['status' => 200, 'assertJsonStructure' => ['data' => ['test']]],
    ],
    'doesnt have permission' => [
        'payload'  => ['permissions' => []],
        'expected' => ['status' => 403],
    ],
])->skip();
