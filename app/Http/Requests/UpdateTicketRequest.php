<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTicketRequest",
 *     title="Update Ticket request",
 *     type="object",
 *     required={"subject", "body", "user_id", "department", "priority"},
 *
 *     @OA\Property(property="subject", type="string", default="Support Request", description="Ticket subject"),
 *     @OA\Property(property="body", type="string", default="Please help me with...", description="Ticket message body"),
 *     @OA\Property(property="user_id", type="integer", default=1, description="User ID who creates the ticket"),
 *     @OA\Property(property="department", type="string", enum={"support", "sales", "billing"}, description="Ticket department"),
 *     @OA\Property(property="priority", type="integer", enum={1, 2, 3, 4}, description="Ticket priority level"),
 *     @OA\Property(property="image", type="string", format="binary", description="Attachment image file"),
 * )
 */
class UpdateTicketRequest extends FormRequest
{
    use FillAttributes;

    public function rules(): array
    {
        return (new StoreTicketRequest())->rules();
    }

    protected function prepareForValidation()
    {
        $this->convertOnToBoolean([
            'published' => request('published', false),
        ]);
    }
}
