<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CollapsibleHeader extends Component
{
    /** Create a new component instance. */
    public function __construct(
        public string $expandedTitle = '',
        public string $expandedSubtitle = '',
        public string $collapsedText = '',
        public string $collapsedSubtext = '',
        public string $collapsedIcon = '',
        public string $variant = 'text' // 'text', 'icon', 'abbreviation'
    ) {}

    /** Get the view / contents that represent the component. */
    public function render(): View|Closure|string
    {
        return view('components.collapsible-header');
    }
}
