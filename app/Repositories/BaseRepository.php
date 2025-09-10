<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\BooleanEnum;
use App\Helpers\Constants;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(public Model $model) {}
    
    public function query(array $payload = []): Builder|QueryBuilder
    {
        return $this->getModel()->query();
    }
    
    public function paginate(?int $limit = null, array $payload = []): LengthAwarePaginator|Collection|array
    {
        if ($limit === null) {
            $limit = request()->input('page_limit', Constants::DEFAULT_PAGINATE);
        }
        
        if ((int) $limit === -1) {
            return $this->query($payload)->get();
        }
        
        return $this->query($payload)->paginate($limit);
    }
    
    public function get(array $payload = [], ?int $limit = null): Collection
    {
        $q = $this->query($payload);
        if ($limit) {
            $q->limit($limit);
        }
        
        return $q->get();
    }
    
    public function store(array $payload)
    {
        return $this->getModel()->create($payload);
    }
    
    public function update($eloquent, array $payload)
    {
        $eloquent->update($payload);
        
        return $eloquent;
    }
    
    public function delete($eloquent, bool $force = false): bool
    {
        if (is_int($eloquent)) {
            $eloquent = $this->find($eloquent);
        }
        if ($force) {
            return $eloquent->forceDelete();
        }

        return $eloquent->delete();
    }
    
    public function find(mixed $value, string $field = 'id', array $selected = ['*'], bool $firstOrFail = false, array|string $with = [])
    {
        $model = $this->getModel()->with($with)->select($selected)->where($field, $value);
        
        if ($firstOrFail) {
            return $model->firstOrFail();
        }
        
        return $model->first();
    }

    public function findMany(array $values, string $field = 'id', array $selected = ['*'], array|string $with = []): Collection|array
    {
        return $this->getModel()->with($with)->select($selected)->whereIn($field, $values)->get();
    }

    public function getModel(): Model
    {
        return $this->model;
    }
    
    public function toggle($model, string $field = 'published')
    {
        if (is_int($model)) {
            $model = $this->find($model);
        }
        if ($model[$field] instanceof BooleanEnum) {
            $model[$field] = ! $model[$field]->value;
        } else {
            $model[$field] = ! $model[$field];
        }
        $model->save();
        
        return $model;
    }
    
    public function updateOrCreate(array $conditions = [], array $data=[])
    {
        return $this->getModel()->updateOrCreate($conditions, $data);
    }
    
    public function data(array $payload = []): array
    {
        return [];
    }
}
