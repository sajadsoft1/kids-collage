<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse;

use Closure;

interface OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO;
}
