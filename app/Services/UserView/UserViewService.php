<?php

declare(strict_types=1);

namespace App\Services\UserView;

use App\Models\UserView;
use Illuminate\Support\Facades\Auth;

class UserViewService
{
    public static function record($eloquent, string $collection = 'api'): void
    {
        if ( ! is_null($eloquent)) {
            if (self::seen($eloquent)) {
                return;
            }
            $visitor = null;
            
            if (Auth::guard('api')->check()) {
                $visitor = auth()->id();
            }
            UserView::query()->create([
                'morphable_id'   => $eloquent->id,
                'morphable_type' => $eloquent::class,
                'user_id'        => auth()->id(),
                'collection'     => $collection,
                'visitor'        => $visitor,
                'ip'             => request()->ip(),
            ]);
            
            if (array_key_exists('view_count', $eloquent->toArray())) {
                $eloquent->increment('view_count');
            }
            
            if (array_key_exists('hits', $eloquent->toArray())) {
                $eloquent->increment('hits');
            }
        }
    }
    
    public static function seen($eloquent, string $collection = 'api'): bool
    {
        if (Auth::guard('api')->check()) {
            return UserView::query()->where([
                'morphable_id'   => $eloquent->id,
                'morphable_type' => $eloquent::class,
                'collection'     => $collection,
                'user_id'        => auth()->id(),
            ])->exists();
        }
        
        return UserView::query()->where([
            'morphable_id'   => $eloquent->id,
            'morphable_type' => $eloquent::class,
            'collection'     => $collection,
            'ip'             => request()->ip(),
        ])->exists();
    }
}
