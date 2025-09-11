<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCommentRequest",
 *     title="Update Comment request",
 *     type="object",
 *     required={"title", "description", "published", "user_id", "comment", "morphable_type", "morphable_id"},
 *
 *     @OA\Property(property="title", type="string", default="Updated comment title", description="Comment title"),
 *     @OA\Property(property="description", type="string", default="Updated comment description", description="Comment description"),
 *     @OA\Property(property="published", type="boolean", default=true, description="Publication status"),
 *     @OA\Property(property="user_id", type="integer", default=1, description="User ID who made the comment"),
 *     @OA\Property(property="admin_id", type="integer", description="Admin ID who manages the comment"),
 *     @OA\Property(property="parent_id", type="integer", description="Parent comment ID for replies"),
 *     @OA\Property(property="comment", type="string", default="This is a great post!", description="Comment content"),
 *     @OA\Property(property="admin_note", type="string", description="Admin note for the comment"),
 *     @OA\Property(property="morphable_type", type="string", default="App\\Models\\Blog", description="Type of model being commented on"),
 *     @OA\Property(property="morphable_id", type="integer", default=1, description="ID of model being commented on"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date"),
 * )
 */
class UpdateCommentRequest extends StoreCommentRequest {}
