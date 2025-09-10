<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Filters\FuzzyFilter;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\SelectableContract;
use App\Services\AdvancedSearchFields\AdvanceFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository implements UserRepositoryInterface,SelectableContract
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }


    public function query(array $payload = []): Builder|QueryBuilder
    {
        return QueryBuilder::for(User::query())
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

    /**
     * @param array{
     *     selected?: string|array,
     *     sort?: string,
     *     filter?: array{search:string},
     *     limit?: int,
     *     } $payload
     */
    public function select(array $payload = []): Collection
    {
        $payload['select'] = ['id','name','family','mobile'];
        $results = $this->query($payload)->get();
        if ( ! empty($payload['selected'])) {
            $selected=$payload['selected'];
            if (is_string($selected)){
                $selected = explode(',',$selected);
            }
            $selectedQuery = User::whereIn('id', $selected);
            $results = $results->merge($selectedQuery->get())->unique('id');
        }

        return $results->map(function ($model) {
            return [
                'value' => $model->id,
                'label' => $model->name. ' '.$model->family .($model->mobile ? ' (' . $model->mobile . ')' : ''),
            ];
        });
    }

}
