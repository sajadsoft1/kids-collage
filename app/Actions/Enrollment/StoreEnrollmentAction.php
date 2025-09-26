<?php

declare(strict_types=1);

namespace App\Actions\Enrollment;

use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreEnrollmentAction
{
    use AsAction;

    /**
     * @param array{
     *     user_id:int,
     *     course_id:int,
     *     enroll_date:string,
     *     status:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Enrollment
    {
        return DB::transaction(function () use ($payload) {
            $model = Enrollment::create($payload);

            return $model->refresh();
        });
    }
}
