<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 p-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-info rounded-full flex items-center justify-center">
                <x-icon name="o-envelope" class="w-6 h-6 text-white" />
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
                {{ trans('auth.verify_your_email') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ trans('auth.enter_verification_code') }}
            </p>
        </div>

        <!-- Verification Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            @if (!$codeVerified)
                <form wire:submit="verifyCode" class="space-y-6">
                    <!-- Email Input -->
                    <div>
                        <x-input wire:model="email" type="email" :label="trans('auth.email')"
                            placeholder="Enter your email address" icon="o-envelope" required />
                    </div>

                    <!-- Verification Code Input -->
                    <div>
                        <x-input wire:model="code" type="text" :label="trans('auth.verification_code')" placeholder="Enter 6-digit code"
                            icon="o-key" maxlength="6" required />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ trans('auth.code_sent_to_email') }}
                        </p>
                    </div>

                    <!-- Verify Button -->
                    <x-button type="submit" class="btn-info w-full" :label="trans('auth.verify_email')" icon="o-check"
                        spinner="verifyCode" />

                    <!-- Resend Code -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ trans('auth.didnt_receive_code') }}
                        </p>
                        <x-button wire:click="resendCode" class="btn-link btn-sm" :label="trans('auth.resend_code')"
                            spinner="resendCode" />
                    </div>
                </form>
            @else
                <!-- Success Message -->
                <div class="text-center space-y-4">
                    <div class="mx-auto h-16 w-16 bg-success rounded-full flex items-center justify-center">
                        <x-icon name="o-check-circle" class="w-8 h-8 text-white" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ trans('auth.email_verified_successfully') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ trans('auth.account_activated') }}
                    </p>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <p class="text-sm text-green-800 dark:text-green-200">
                            {{ trans('auth.you_can_now_login') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Navigation Links -->
            <div class="mt-6 space-y-3">
                <x-button wire:click="goToLogin" class="btn-outline w-full" icon="o-arrow-left" :label="trans('auth.back_to_login')" />

                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ trans('auth.dont_have_account') }}
                        <x-button wire:click="goToRegister" class="btn-link btn-sm" :label="trans('auth.create_account')" />
                    </p>
                </div>
            </div>
        </div>

        <!-- Demo Code Notice -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
            <div class="flex items-center">
                <x-icon name="o-information-circle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" />
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    {{ trans('auth.demo_code_notice') }}: <strong>123456</strong>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            <p>{{ trans('auth.need_help_verifying') }}</p>
            <div class="mt-2">
                <x-button class="btn-link btn-xs" :label="trans('auth.contact_support')" />
            </div>
        </div>
    </div>
</div>
