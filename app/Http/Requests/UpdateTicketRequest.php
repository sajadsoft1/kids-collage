<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTicketRequest",
 *     title="Update Ticket request",
 *     type="object",
 *     required={"subject", "body", "user_id", "department", "priority"},
 *
 *     @OA\Property(property="subject", type="string", default="Updated Support Request", description="Ticket subject"),
 *     @OA\Property(property="body", type="string", default="Updated request details...", description="Ticket message body"),
 *     @OA\Property(property="user_id", type="integer", default=1, description="User ID who creates the ticket"),
 *     @OA\Property(property="department", type="string", enum={"finance_and_administration", "Sale", "technical"}, default="technical", description="Ticket department"),
 *     @OA\Property(property="priority", type="integer", enum={1, 2, 3, 4}, default=2, description="Ticket priority (1=Low, 2=Medium, 3=High, 4=Critical)"),
 *     @OA\Property(property="image", type="string", format="binary", description="Attachment image file"),
 * )
 */
class UpdateTicketRequest extends StoreTicketRequest {}
