<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTicketRequest",
 *     title="Store Ticket request",
 *     type="object",
 *     required={"subject", "body", "user_id", "department", "priority"},
 *
 *     @OA\Property(property="subject", type="string", default="Support Request", description="Ticket subject"),
 *     @OA\Property(property="body", type="string", default="Please help me with...", description="Ticket message body"),
 *     @OA\Property(property="user_id", type="integer", default=1, description="User ID who creates the ticket"),
 *     @OA\Property(property="department", ref="#/components/schemas/TicketDepartmentEnum"),
 *     @OA\Property(property="priority", tref="#/components/schemas/TicketPriorityEnum"),
 *     @OA\Property(property="image", type="string", format="binary", description="Attachment image file"),
 * )
 */
class StoreTicketRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return [
            'subject'    => ['required', 'string', 'max:255'],
            'body'       => ['required', 'string'],
            'user_id'    => 'required|exists:users,id',
            'department' => 'required|in:' . implode(',', TicketDepartmentEnum::values()),
            'priority'   => 'required|in:' . implode(',', TicketPriorityEnum::values()),
            'image'      => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
        ];
    }

    protected function prepareForValidation(): void
    {
        // No boolean conversion needed for tickets
    }
}
