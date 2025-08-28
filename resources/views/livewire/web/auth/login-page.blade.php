<div class="w-full space-y-2">
    <!-- Header -->
    <div class="text-center">
        <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
            {{ trans('auth.sign_in_to_account') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ trans('auth.enter_your_credentials') }}
        </p>
    </div>

    <!-- Login Form -->
    <form wire:submit="login" class="space-y-2">
        <!-- Email Input -->
        <div>
            <x-input wire:model="email" type="email" :label="trans('auth.email')" placeholder="Enter your email"
                     icon="o-envelope" required/>
        </div>

        <!-- Password Input -->
        <div>
            <x-input wire:model="password" type="password" :label="trans('auth.password')" placeholder="Enter your password"
                     icon="o-lock-closed" required/>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <x-checkbox wire:model="remember" :label="trans('auth.remember_me')"/>
            <x-button wire:click="goToForgetPassword" class="btn-link btn-sm" :label="trans('auth.forgot_password')"/>
        </div>

        <!-- Login Button -->
        <x-button type="submit" class="btn-primary w-full" :label="trans('auth.login')" icon="o-arrow-right"
                  spinner="login"/>
    </form>

    <!-- Divider -->
    <div class="mt-6">
        <div class="relative">
            <div class="absolute top-0 right-0 bottom-0 left-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">
                            {{ trans('auth.or_continue_with') }}
                        </span>
            </div>
        </div>
    </div>

    <!-- Social Login -->
    <div class="mt-6">
        <x-button wire:click="loginWithGoogle" class="btn-outline w-full" icon="o-globe-alt"
                  :label="trans('auth.continue_with_google')"/>
    </div>

    <!-- Register Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ trans('auth.dont_have_account') }}
            <x-button wire:click="goToRegister" class="btn-link btn-sm" :label="trans('auth.create_account')"/>
        </p>
    </div>
</div>
