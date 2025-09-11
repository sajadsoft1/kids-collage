<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTicketMessageRequest",
 *     title="Store TicketMessage request",
 *     type="object",
 *     required={"ticket_id", "user_id", "message"},
 *
 *     @OA\Property(property="ticket_id", type="integer", default=1, description="Ticket ID"),
 *     @OA\Property(property="user_id", type="integer", default=1, description="User ID who sends the message"),
 *     @OA\Property(property="message", type="string", default="Here is my response...", description="Message content"),
 *     @OA\Property(property="file", type="string", format="binary", description="Attachment file"),
 * )
 */
class StoreTicketMessageRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'ticket_id' => 'required|exists:tickets,id',
            'user_id'   => 'required|exists:users,id',
            'message'   => ['required', 'string'],
            'file'      => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt|max:5120',
        ];
    }

    protected function prepareForValidation(): void
    {
        // TicketMessage doesn't use published field based on action
    }
}
