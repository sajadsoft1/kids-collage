<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Trait for learning/help modal – use like Mary Toast: add the trait and optionally set sections.
 * Modal is in the layout; triggers are added via withLearningModalActions() or any wire:click="openLearningModal".
 * Multiple triggers: add the action to breadcrumbs and/or add buttons elsewhere with wire:click="openLearningModal".
 */
trait HasLearningModal
{
    /**
     * Sections for the modal. Set in mount() or override getLearningSections().
     * Each item: ['title' => string, 'content' => string, 'icon' => string (optional)].
     *
     * @var array<int|string, array{title: string, content: string, icon?: string}>
     */
    public array $learningSections = [];

    /**
     * Sections to display in the modal. Default: returns $learningSections. Override or set in mount().
     *
     * @return array<int|string, array{title: string, content: string, icon?: string}>
     */
    public function getLearningSections(): array
    {
        return $this->learningSections;
    }

    /** Open the learning modal (dispatches to global component in layout). */
    public function openLearningModal(): void
    {
        $this->dispatch('open-learning-modal', sections: $this->getLearningSections());
    }

    /**
     * Single learning trigger for breadcrumb/actions. Use withLearningModalActions() or merge this multiple times.
     *
     * @return array{label: string, icon: string, action: string}|array{}
     */
    public function getLearningModalAction(): array
    {
        if ($this->getLearningSections() === []) {
            return [];
        }

        return [
            'label' => trans('general.learning'),
            'icon' => 'o-academic-cap',
            'action' => 'openLearningModal',
        ];
    }

    /**
     * Merge learning trigger into breadcrumb actions. For multiple triggers: array_merge(getLearningModalAction() ? [getLearningModalAction()] : [], ...).
     *
     * @param  array<int, array<string, mixed>> $actions
     * @return array<int, array<string, mixed>>
     */
    public function withLearningModalActions(array $actions): array
    {
        $learning = $this->getLearningModalAction();

        return $learning !== [] ? array_merge($actions, [$learning]) : $actions;
    }
}
