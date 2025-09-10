<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Permission;
use Tests\CreatesApplication;

pest()
    ->extend(Tests\TestCase::class)
    ->use(CreatesApplication::class)
    ->use(DatabaseMigrations::class)
    ->in('Feature');

pest()
    ->extend(Tests\TestCase::class)
    ->in('Unit');

expect()->extend('assertJsonItem', function ($data, $expected, $message = '') {
    $checkExist = collect($data)->contains(function ($row) use ($expected) {
        foreach ($expected as $key => $value) {
            if ( ! isset($row[$key]) || $row[$key] !== $value) {
                return false;
            }
        }

        return true;
    });

    expect($checkExist)->toBeTrue($message);
});

function login(?User $user = null, array $permissions = ['Shared.Admin'])
{
    if (is_null($user)) {
        $user = User::factory()->create();
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'api']);
        }
        $adminPermission = Permission::whereIn('name', $permissions)->get();
        $user->syncPermissions($adminPermission);
    }
    // Create a Sanctum token for the user
    $token = $user->createToken('test-token')->plainTextToken;

    // Attach the token to the request headers
    return test()->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->actingAs($user, 'sanctum');
}

function getCommonJsonStructureIndex(array $data = [], array $advancedFilter = [], $extra = [], array $sorts = [], array $other = []): array
{
    $data = [
        'message',
        'data'  => [
            '*' => $data,
        ],
        'meta'  => [
            'current_page',
            'from',
            'last_page',
            'path',
            'per_page',
            'to',
            'total',
        ],
        'links' => [
            'first',
            'last',
            'prev',
            'next',
        ],
    ];

    if (count($advancedFilter)) {
        $data['advanced_search_fields'] = [
            '*' => $advancedFilter,
        ];
    }
    if (count($extra)) {
        $data['extra'] = $extra;
    }
    if (count($sorts)) {
        $data['sorts'] = $sorts;
    }

    return [
        ...$data, ...$other,
    ];
}

function getCommonJsonStructure(array $data = [], array $other = []): array
{
    $data = [
        'message',
        'data' => [
            $data,
        ],
    ];

    return [
        ...$data, ...$other,
    ];
}

function convertAssertJsonClosureToExpectation(array $payload): array
{
    $expectedJson = Arr::get($payload, 'json');
    if ($expectedJson instanceof Closure) {
        return $expectedJson();
    }

    return [];
}

/**
 * @return array{column: string, from: string, operator: string, to: mixed, contain: int}
 */
function advanceSearchRecord(string $column, mixed $from, mixed $to = null, string $operator = '=', int $contain = 1): array
{
    return [
        'column'   => $column,
        'from'     => $from,
        'to'       => $to,
        'operator' => $operator,
        'contain'  => $contain,
    ];
}
