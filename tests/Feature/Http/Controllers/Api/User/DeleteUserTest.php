<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\Permissions\Models\SharedPermissions;
use App\Services\Permissions\Models\UserPermissions;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function PHPUnit\Framework\assertNotNull;

uses()->group('user');

it('delete user', function (array $payload = [], array $expected = []) {
    $model = User::factory()->create([
        'id' => 1,
    ]);

    $response = login(permissions: Arr::get($payload, 'permissions', [SharedPermissions::Admin]))->deleteJson('api/user/' . Arr::get($payload, 'id', 1));

    $response->assertStatus(Arr::get($expected, 'status', 200))
        ->when(Arr::has($expected, 'assertJsonStructure'))
        ->assertJsonStructure([
            'message',
            'data' => Arr::get($expected, 'assertJsonStructure', []),
        ])
        ->when(Arr::has($expected, 'assertJson'))
        ->assertJson(Arr::get($expected, 'assertJson'));

    if (Arr::get($expected, 'status') === 200 && Schema::hasColumn('users', 'deleted_at')) {
        assertDatabaseHas('users', [
            'id' => 1,
        ]);
        assertNotNull($model->deleted_at, 'user field deleted_at is null');
    } elseif (Arr::get($expected, 'status') === 200) {
        assertDatabaseMissing('users', [
            'id' => 1,
        ]);
    } else {
        assertDatabaseHas('users', [
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
        'payload'  => ['permissions' => [UserPermissions::Delete]],
        'expected' => ['status' => 200, 'assertJsonStructure' => [], 'assertJson' => ['data' => true]],
    ],
    'doesnt have permission' => [
        'payload'  => ['permissions' => [], 'assertJsonStructure' => [], 'assertJson' => ['message']],
        'expected' => ['status' => 403],
    ],
])->skip();
