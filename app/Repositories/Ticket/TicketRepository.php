<?php

declare(strict_types=1);

namespace App\Repositories\Ticket;

use App\Filters\FuzzyFilter;
use App\Models\Ticket;
use App\Repositories\BaseRepository;
use App\Repositories\SelectableContract;
use App\Services\AdvancedSearchFields\AdvanceFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface,SelectableContract
{
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }


    public function query(array $payload = []): Builder|QueryBuilder
    {
        return QueryBuilder::for(Ticket::query())
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
        $payload['select'] = ['id','title'];
        $results = $this->query($payload)->get();

        if ( ! empty($payload['selected'])) {
            $selected=$payload['selected'];
            if (is_string($selected)){
                $selected = explode(',',$selected);
            }
            $selectedQuery = Ticket::whereIn('id', $selected);
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
