<div class="w-full space-y-2">
    <!-- Header -->
    <div class="text-center">
        <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
            {{ trans('auth.forgot_password') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ trans('auth.enter_email_to_reset') }}
        </p>
    </div>

    <!-- Reset Form -->
    @if (!$emailSent)
        <form wire:submit="sendResetLink" class="space-y-6">
            <!-- Email Input -->
            <div>
                <x-input wire:model="email" type="email" :label="trans('auth.email')"
                         placeholder="Enter your email address" icon="o-envelope" required/>
            </div>

            <!-- Send Reset Link Button -->
            <x-button type="submit" class="btn-warning w-full" :label="trans('auth.send_reset_link')" icon="o-paper-airplane"
                      spinner="sendResetLink"/>
        </form>
    @else
        <!-- Success Message -->
        <div class="text-center space-y-4">
            <div class="mx-auto h-16 w-16 bg-success rounded-full flex items-center justify-center">
                <x-icon name="o-check" class="w-8 h-8 text-white"/>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ trans('auth.reset_link_sent') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ trans('auth.check_your_email') }}
            </p>
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    {{ trans('auth.reset_link_instructions') }}
                </p>
            </div>
        </div>
    @endif

    <!-- Navigation Links -->
    <div class="mt-6 space-y-3">
        <x-button wire:click="goToLogin" class="btn-outline w-full" icon="o-arrow-left" :label="trans('auth.back_to_login')"/>

        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ trans('auth.dont_have_account') }}
                <x-button wire:click="goToRegister" class="btn-link btn-sm" :label="trans('auth.create_account')"/>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
        <p>{{ trans('auth.need_help') }}</p>
        <div class="mt-2">
            <x-button class="btn-link btn-xs" :label="trans('auth.contact_support')"/>
        </div>
    </div>
</div>
