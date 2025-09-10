<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;

interface BaseRepositoryInterface
{
    public function query(array $payload = []): Builder|QueryBuilder;

    public function paginate(?int $limit = null, array $payload = []): LengthAwarePaginator|Collection|array;

    public function get(array $payload = [], ?int $limit=null): Collection;

    public function store(array $payload);

    public function update($eloquent, array $payload);

    public function delete($eloquent, bool $force = false): bool;

    public function find(mixed $value, string $field = 'id', array $selected = ['*'], bool $firstOrFail = false, array $with = []);

    public function findMany(array $values, string $field = 'id', array $selected = ['*'], array $with = []): Collection|array;

    public function getModel();

    public function toggle($model, string $field = 'published');

    public function updateOrCreate(array $conditions = [], array $data=[]);

    public function data(array $payload = []): array;
}
