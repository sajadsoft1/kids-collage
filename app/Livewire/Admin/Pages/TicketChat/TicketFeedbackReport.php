<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use Karnoweb\TicketChat\Enums\ConversationType;
use Karnoweb\TicketChat\Models\Feedback;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TicketFeedbackReport extends Component
{
    use WithPagination;

    /** @return array{total: int, average: float, distribution: array<int, int>} */
    #[Computed]
    public function stats(): array
    {
        $query = Feedback::query();

        $total = (clone $query)->count();
        $average = $total > 0 ? (float) (clone $query)->avg('rating') : 0;
        $distribution = [];
        foreach ([1, 2, 3, 4, 5] as $r) {
            $distribution[$r] = (clone $query)->where('rating', $r)->count();
        }

        return [
            'total' => $total,
            'average' => round($average, 1),
            'distribution' => $distribution,
        ];
    }

    /** @return \Illuminate\Contracts\Pagination\LengthAwarePaginator */
    #[Computed]
    public function feedbacks()
    {
        return Feedback::query()
            ->with(['conversation:id,ticket_number,title', 'user:id,name,family'])
            ->whereHas('conversation', fn ($q) => $q->where('type', ConversationType::TICKET))
            ->latest()
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.pages.ticket-chat.ticket-feedback-report', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['label' => __('ticket_chat.feedback_report')],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.ticket-chat.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
            ],
        ]);
    }
}
