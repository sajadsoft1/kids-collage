<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SmartCache;
use App\View\Composers\NavbarComposer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PowerComponents\LivewirePowerGrid\Button;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->singleton('smartcache', function () {
            return new class {
                /** Forward static call to SmartCache::for($class) */
                public function for(string $class): SmartCache
                {
                    return SmartCache::for($class);
                }
            };
        });
    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        Response::macro('data', function ($data = null, string $message = '', int $status = 200) {
            return response()->json(compact('message', 'data'), $status);
        });

        Response::macro('error', function (string $message = '', int $status = 400) {
            return response()->json(compact('message'), $status);
        });

        Response::macro('dataWithAdditional', function (AnonymousResourceCollection|Collection|ResourceCollection $data, array $additional = [], string $message = '', int $status = 200) {
            return $data->additional(array_merge(compact('message'), $additional))->response()->setStatusCode($status);
        });

        View::composer([
            'admin.layouts.navbar',
            'admin.layouts.navbar-mobile',
            'components.layouts.app',
            'components.layouts.dashboard',
            'components.layouts.compact',
            'components.layouts.modern',
            'components.layouts.minimal',
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
