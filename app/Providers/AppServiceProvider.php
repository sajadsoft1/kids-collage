<?php

declare(strict_types=1);

namespace App\Providers;

use App\View\Composers\NavbarComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PowerComponents\LivewirePowerGrid\Button;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    /** Bootstrap any application services. */
    public function boot(): void
    {
        View::composer([
            'admin.layouts.navbar',
            'admin.layouts.navbar-mobile',
            'components.layouts.app',
            'components.layouts.dashboard',
            'components.layouts.compact',
            'components.layouts.modern',
            'components.layouts.enterprise',
            'components.layouts.metronic',
        ], NavbarComposer::class);

        Button::macro('navigate', function () {
            $this->attributes([
                'wire:navigate' => true,
            ]);

            return $this;
        });

        Blade::directive('livewireCalendarScripts', function () {
            return <<<'HTML'
                            <script>
                                function onLivewireCalendarEventDragStart(event, eventId) {
                                    event.dataTransfer.setData('id', eventId);
                                }

                                function onLivewireCalendarEventDragEnter(event, componentId, dateString, dragAndDropClasses) {
                                    event.stopPropagation();
                                    event.preventDefault();

                                    let element = document.getElementById(`${componentId}-${dateString}`);
                                    element.className = element.className + ` ${dragAndDropClasses} `;
                                }

                                function onLivewireCalendarEventDragLeave(event, componentId, dateString, dragAndDropClasses) {
                                    event.stopPropagation();
                                    event.preventDefault();

                                    let element = document.getElementById(`${componentId}-${dateString}`);
                                    element.className = element.className.replace(dragAndDropClasses, '');
                                }

                                function onLivewireCalendarEventDragOver(event) {
                                    event.stopPropagation();
                                    event.preventDefault();
                                }

                                function onLivewireCalendarEventDrop(event, componentId, dateString, year, month, day, dragAndDropClasses) {
                                    event.stopPropagation();
                                    event.preventDefault();

                                    let element = document.getElementById(`${componentId}-${dateString}`);
                                    element.className = element.className.replace(dragAndDropClasses, '');

                                    const eventId = event.dataTransfer.getData('id');

                                    window.Livewire.find(componentId).call('onEventDropped', eventId, year, month, day);
                                }
                            </script>
                HTML;
        });
    }
}
