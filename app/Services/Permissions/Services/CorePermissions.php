<?php

declare(strict_types=1);

namespace App\Services\Permissions\Services;

use App\Services\Permissions\Models\BannerPermissions;
use App\Services\Permissions\Models\BlogPermissions;
use App\Services\Permissions\Models\CategoryPermissions;
use App\Services\Permissions\Models\ClientPermissions;
use App\Services\Permissions\Models\CommentPermissions;
use App\Services\Permissions\Models\ContactUsPermissions;
use App\Services\Permissions\Models\FaqPermissions;
use App\Services\Permissions\Models\OpinionPermissions;
use App\Services\Permissions\Models\PagePermissions;
use App\Services\Permissions\Models\PortFolioPermissions;
use App\Services\Permissions\Models\RolePermissions;
use App\Services\Permissions\Models\SliderPermissions;
use App\Services\Permissions\Models\TagPermissions;
use App\Services\Permissions\Models\TeammatePermissions;
use App\Services\Permissions\Models\UserPermissions;

class CorePermissions
{
    public static function all(): array
    {
        return [
            resolve(UserPermissions::class)->all(),
            resolve(TeammatePermissions::class)->all(),
            resolve(TagPermissions::class)->all(),
            resolve(SliderPermissions::class)->all(),
            resolve(RolePermissions::class)->all(),
            resolve(PortFolioPermissions::class)->all(),
            resolve(PagePermissions::class)->all(),
            resolve(OpinionPermissions::class)->all(),
            resolve(FaqPermissions::class)->all(),
            resolve(ContactUsPermissions::class)->all(),
            resolve(CommentPermissions::class)->all(),
            resolve(ClientPermissions::class)->all(),
            resolve(CategoryPermissions::class)->all(),
            resolve(BlogPermissions::class)->all(),
            resolve(BannerPermissions::class)->all(),
        ];
    }
}
