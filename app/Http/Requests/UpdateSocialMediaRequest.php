<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateSocialMediaRequest",
 *     title="Update SocialMedia request",
 *     type="object",
 *     required={"title", "link", "position", "published"},
 *
 *     @OA\Property(property="title", type="string", default="Twitter", description="Social media platform title"),
 *     @OA\Property(property="link", type="string", default="https://twitter.com/username", description="Social media profile URL"),
 *     @OA\Property(property="ordering", type="integer", default=0, description="Display order"),
 *     @OA\Property(property="position", type="string", enum={"all", "header", "footer"}, default="all", description="Display position"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="image", type="string", format="binary", description="Social media icon/image"),
 * )
 */
class UpdateSocialMediaRequest extends StoreSocialMediaRequest {}
