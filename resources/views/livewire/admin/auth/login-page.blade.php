<form class="space-y-6" wire:submit="login" novalidate>
    <x-input
            required
            :label="trans('auth.email')"
            type="email"
            autocomplete="email"
            wire:model="email"/>

    <x-input
            required
            :label="trans('auth.password')"
            type="password"
            wire:model="password"/>

    <x-button
            :label="trans('auth.login')"
            type="submit"
            class="btn-primary w-full"
    />
</form>