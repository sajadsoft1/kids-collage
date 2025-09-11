<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateTicketMessageRequest",
 *      title="Update TicketMessage request",
 *      type="object",
 *      required={"ticket_id", "user_id", "message"},
 *
 *     @OA\Property(property="ticket_id", type="integer", default=1, description="Ticket ID"),
 *     @OA\Property(property="user_id", type="integer", default=1, description="User ID who sends the message"),
 *     @OA\Property(property="message", type="string", default="Updated response message...", description="Message content"),
 *     @OA\Property(property="file", type="string", format="binary", description="Attachment file"),
 * )
 */
class UpdateTicketMessageRequest extends StoreTicketMessageRequest {}
