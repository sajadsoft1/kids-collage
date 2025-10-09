<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Models\User;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class FindUserPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $userId = $dto->getFromPayload('user_id');

        abort_if( ! $userId, 400, trans('order.exceptions.user_id_required'));

        $user = User::find($userId);

        abort_if( ! $user, 404, trans('order.exceptions.user_not_found'));

        $dto->setUser($user);

        return $next($dto);
    }
}
