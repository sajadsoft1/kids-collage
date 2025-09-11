<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdatePortfolioRequest",
 *     title="Update Portfolio request",
 *     type="object",
 *     required={"title", "published", "execution_date", "category_id"},
 *
 *     @OA\Property(property="title", type="string", default="Updated Project Name", description="Portfolio title"),
 *     @OA\Property(property="description", type="string", default="Updated project description", description="Portfolio description"),
 *     @OA\Property(property="body", type="string", default="<p>Updated detailed project information...</p>", description="Portfolio detailed content"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 *     @OA\Property(property="execution_date", type="string", format="date", default="2024-02-01", description="Project execution date"),
 *     @OA\Property(property="category_id", type="integer", default=1, description="Portfolio category ID"),
 *     @OA\Property(property="creator_id", type="integer", description="Creator user ID (optional, defaults to current user)"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"web", "design", "updated"}, description="Portfolio tags"),
 *     @OA\Property(property="image", type="string", format="binary", description="Portfolio featured image"),
 * )
 */
class UpdatePortfolioRequest extends StorePortfolioRequest {}
