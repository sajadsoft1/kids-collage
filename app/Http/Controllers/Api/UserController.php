<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /** Display a listing of the resource. */
    public function index() {}

    /** Store a newly created resource in storage. */
    public function store(Request $request) {}

    /** Display the specified resource. */
    public function show(string $id) {}

    /** Update the specified resource in storage. */
    public function update(Request $request, string $id) {}

    /** Remove the specified resource from storage. */
    public function destroy(string $id) {}

    /**
     * @OA\Get(
     *     path="/teachers",
     *     operationId="getTeachers",
     *     tags={"User"},
     *     summary="Get Teachers",
     *     description="Returns list of teachers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="teachers",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     )
     * )
     */
    public function teachers(): JsonResponse
    {
        $teachers = User::teachers();
        _dds($teachers);

        return Response::data(
            [
                'teachers' => UserResource::collection($teachers),
            ]
        );
    }
}
