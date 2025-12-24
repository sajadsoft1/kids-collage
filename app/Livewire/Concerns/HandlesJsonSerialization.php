<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

/**
 * Handles JSON Serialization for Livewire Components.
 *
 * This trait provides a workaround for a known issue where Livewire's
 * JavaScript Proxy (used by @entangle) can trigger toJSON method calls
 * on the backend component during serialization.
 *
 * When Alpine.js or JavaScript calls JSON.stringify() on an entangled
 * property, the Proxy intercepts this and may forward a "toJSON" method
 * call to the Livewire component. Without this trait, you'll see:
 * "Livewire Error: Unable to call component method. Public method [toJSON] not found"
 *
 * @see https://github.com/livewire/livewire/discussions/5765
 */
trait HandlesJsonSerialization
{
    /**
     * Handle toJSON calls from Livewire/Alpine integration.
     *
     * Returns null to indicate the component shouldn't be directly serialized.
     * This prevents errors when JavaScript attempts to stringify entangled properties.
     */
    public function toJSON(): ?array
    {
        return null;
    }
}
