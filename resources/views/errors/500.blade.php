@php
    $title = __('error.500.title');
    $message = __('error.500.message');
    $description = __('error.500.description');
    $isAuthenticated = auth()->check();
@endphp

<x-layouts.error>
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body items-center text-center">
            {{-- Error Icon --}}
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-6xl text-error"></i>
            </div>

            {{-- Error Code --}}
            <h1 class="text-8xl font-bold text-error mb-4">500</h1>

            {{-- Error Title --}}
            <h2 class="card-title text-3xl mb-2">{{ $title }}</h2>

            {{-- Error Message --}}
            <p class="text-lg text-base-content/70 mb-4">{{ $message }}</p>

            {{-- Error Description --}}
            <p class="text-base-content/60 mb-8 max-w-md">{{ $description }}</p>

            {{-- Action Buttons --}}
            <div class="card-actions justify-center gap-4 flex-wrap">
                <button onclick="window.location.reload()" class="btn btn-primary">
                    <i class="fas fa-redo me-2"></i>
                    {{ __('error.try_again') }}
                </button>

                @if ($isAuthenticated)
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                        <i class="fas fa-home me-2"></i>
                        {{ __('error.back_to_dashboard') }}
                    </a>
                @endif

                <button onclick="window.history.back()" class="btn btn-ghost">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'fa' ? 'right' : 'left' }} me-2"></i>
                    {{ __('error.go_back') }}
                </button>
            </div>
        </div>
    </div>
</x-layouts.error>
