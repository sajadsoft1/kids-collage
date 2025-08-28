<div class="max-w-md w-full space-y-2">
    <!-- Header -->
    <div class="text-center">
        <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
            {{ trans('auth.create_new_account') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ trans('auth.fill_information_below') }}
        </p>
    </div>

    <!-- Register Form -->
    <form wire:submit="register" class="space-y-2">
        <div class="grid lg:grid-cols-2 lg:space-x-2">
            <!-- Name Input -->
            <x-input wire:model="name" type="text" :label="trans('auth.name')" placeholder="Enter your first name"
                     icon="o-user" required/>

            <!-- Family Input -->
            <x-input wire:model="family" type="text" :label="trans('auth.family')" placeholder="Enter your last name"
                     icon="o-user" required/>
        </div>

        <!-- Email Input -->
        <x-input wire:model="email" type="email" :label="trans('auth.email')" placeholder="Enter your email"
                 icon="o-envelope" required/>

        <div class="grid lg:grid-cols-2 lg:space-x-2">
            <!-- Password Input -->
            <x-input wire:model="password" type="password" :label="trans('auth.password')" placeholder="Create a password"
                     icon="o-lock-closed" required/>

            <!-- Password Confirmation Input -->
            <x-input wire:model="password_confirmation" type="password" :label="trans('auth.password_confirmation')"
                     placeholder="Confirm your password" icon="o-lock-closed" required/>
        </div>


        <!-- Terms and Conditions -->
        <x-checkbox wire:model="terms_accepted" :label="trans('auth.i_agree_to_terms')" required/>

        <!-- Register Button -->
        <x-button type="submit" class="btn-success w-full" :label="trans('auth.register')" icon="o-arrow-right"
                  spinner="register"/>
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

    <!-- Social Register -->
    <div class="mt-6">
        <x-button wire:click="registerWithGoogle" class="btn-outline w-full" icon="o-globe-alt"
                  :label="trans('auth.continue_with_google')"/>
    </div>

    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ trans('auth.already_have_account') }}
            <x-button wire:click="goToLogin" class="btn-link btn-sm" :label="trans('auth.sign_in')"/>
        </p>
    </div>

</div>
