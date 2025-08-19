<?php

declare(strict_types=1);

namespace App\Exceptions\Trait;

use Illuminate\Http\JsonResponse;

trait JsonRenderTrait
{
    public function render(): JsonResponse
    {
        return response()->json([
            'code'    => str_replace('Exception', '', class_basename(get_class($this))),
            'message' => str_replace('exception.', '', __('exception.' . $this->getMessage())),
        ], $this->getCode());
    }
}
