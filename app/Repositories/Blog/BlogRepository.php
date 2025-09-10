<?php

declare(strict_types=1);

namespace App\Repositories\Blog;

use App\Filters\FuzzyFilter;
use App\Models\Blog;
use App\Repositories\BaseRepository;
use App\Repositories\SelectableContract;
use App\Services\AdvancedSearchFields\AdvanceFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface,SelectableContract
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }


    public function query(array $payload = []): Builder|QueryBuilder
    {
        return QueryBuilder::for(Blog::query())
                           ->with(Arr::get($payload, 'with', []))
                           ->when(Arr::get($payload, 'limit'), fn ($q) => $q->limit($payload['limit']))
                           ->when(Arr::get($payload, 'select'), fn ($query) => $query->select($payload['select']))
                           ->defaultSort(Arr::get($payload, 'sort', '-id'))
                           ->allowedSorts(['id', 'created_at', 'updated_at'])
                           ->allowedFilters([
                               AllowedFilter::custom('search', new FuzzyFilter(['name']))->default(Arr::get($payload, 'filter.search'))->nullable(false),
                               AllowedFilter::custom('a_search', new AdvanceFilter())->default(Arr::get($payload, 'filter.a_search', []))->nullable(false),
                           ]);
    }

    public function extra(array $payload = []): array
    {
        return [
            'default_sort' => '-id',
            'sorts'        => ['id', 'created_at', 'updated_at'],
        ];
    }

    public function select(array $payload = []): Collection
    {
        $query = $this->query(array_merge($payload, [
            'select' => ['id','title'],
        ]));

        $results = $query->get();
        if ( ! empty($payload['selected'])) {
            $selected=$payload['selected'];
            if (is_string($payload['selected'])){
                $selected = explode(',',$selected);
            }
            $selectedQuery = $this->model->whereIn('id', $selected);
            $results = $results->merge($selectedQuery->get())->unique('id');
        }

        return $results->map(function ($model) {
            return [
                'value' => $model->id,
                'label' => $model->title,
            ];
        });
    }

}
