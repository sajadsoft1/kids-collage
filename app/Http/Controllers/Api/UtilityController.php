<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\Constants;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;
use Spatie\Permission\Models\Role;

class UtilityController
{
    /**
     * @OA\Get(
     *     path="/utility/select/{model}",
     *     operationId="selectByModel",
     *     tags={"Utility"},
     *     summary="for use in select box",
     *     description="
     *     `locker`: available=available= lockers where dosen't have active entry(can assign to new user) | available=unavailable= lockers where have active entry (cannot assign to new user) </br></br>
     *     `wallet`: type=user= wallets where type is user | type=locker= wallets where type is locker </br></br>",
     *
     *     @OA\Parameter(name="model", required=true, in="path",
     *
     *         @OA\Schema(type="string", default="user",
     *             enum={"user"}
     *         )
     *     ),
     *
     *     @OA\Parameter(name="options", required=false, in="query",
     *         description="you must send as query param param1=1&param2=2 </br>
     *      `category`:shop_id:int </br></br>
     *      `wallet`:type=user|locker|contract|organization,charge:1|0,available=available|unavailable,user_id=int",
     *
     *         @OA\Schema(type="string", default=""),
     *         @OA\Examples(example="Default", summary="", value=""),
     *         @OA\Examples(example="search available locker for enter user", summary="", value="available=available&locker_group_id=1"),
     *         @OA\Examples(example="search unavailable locker for exist user", summary="", value="available=unavailable&locker_group_id=1"),
     *         @OA\Examples(example="list of categories of a shop", summary="", value="shop_id=1"),
     *         @OA\Examples(example="lockers in use with charge", summary="", value="available=unavailable&charge=1"),
     *         @OA\Examples(example="wallet with type:user|locker|contract|organization", summary="", value="type=user"),
     *         @OA\Examples(example="wallet with type locker can use for product or service payments", summary="", value="type=locker&available=unavailable"),
     *         @OA\Examples(example="wallet with type locker for user_id=2", summary="", value="type=locker&available=unavailable&user_id=2"),
     *     ),
     *
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(parameter="sort", name="sort", in="query", required=false,
     *     description="Sort the results by field. Use '-' for descending order. Example:-id"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(type="object",
     *
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(type="object",
     *
     *                     @OA\Property(property="value", type="string"),
     *                     @OA\Property(property="label", type="string")
     *                 )
     *             ),
     *         ),
     *         @OA\Examples(
     *             example="SelectUserExample",
     *             summary="Example for selecting users",
     *             value={
     *                 "message" : "Operation successful",
     *                 "data" : {
     *                     {"value" : "1", "label" : "Ali Ahmadi"},
     *                     {"value" : "2", "label" : "Sara Mohammadi"}
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function select(Request $request, string $model): JsonResponse
    {
        if ($model === 'role') {
            // Role Dont have repository
            return Response::data($this->selectRole($request)->toArray());
        }

        $class      = 'App\Repositories\\' . Str::studly($model) . '\\' . Str::studly($model) . 'Repository';
        $repository = resolve($class);

        try {
            $payload = [];
            if ($options = $request->input('options')) {
                parse_str($options, $payload);
            }
            $payload['limit'] = $request->input('page_limit', Constants::DEFAULT_PAGINATE);
            $data             = $repository->select($payload)->toArray();

            return Response::data($data);
        } catch (Exception $e) {
            return Response::data(null, $e->getMessage(), 400);
        }
    }

    private function selectRole(Request $request): Collection
    {
        $payload = [];
        if ($options = $request->input('options')) {
            parse_str($options, $payload);
        }
        $results = Role::query()
            ->when($search = $request->input('filter.search'), fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->limit($request->input('page_limit', Constants::DEFAULT_PAGINATE))
            ->get();

        if ( ! empty($payload['selected'])) {
            $selected = $payload['selected'];
            if (is_string($payload['selected'])) {
                $selected = explode(',', $selected);
            }
            $selectedResult = Role::whereIn('id', $selected)->get();
            $results        = $results->merge($selectedResult)->unique('id');
        }

        return $results->map(function ($model) {
            return [
                'value' => $model->id,
                'label' => $model->name,
            ];
        });
    }
}
