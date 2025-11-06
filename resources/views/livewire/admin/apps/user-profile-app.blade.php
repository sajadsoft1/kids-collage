  <div class="w-full p-8 mx-auto">
      <div class="mb-8 text-center">
          <div class="relative inline-block">
              <div
                  class="w-24 h-24 mx-auto mb-4 overflow-hidden rounded-full ring-2 ring-blue-500 ring-offset-4 ring-offset-white">
                  <img src="{{ $user->getFirstMediaUrl('avatar', \App\Helpers\Constants::RESOLUTION_512_SQUARE) }}"
                      alt="Jenny Klabber" class="object-cover w-full h-full">
              </div>
              <div class="absolute w-4 h-4 bg-green-500 rounded-full bottom-2 right-2 ring-2 ring-white"></div>
          </div>
          <div class="flex items-center justify-center space-x-2">
              <h1 class="text-3xl font-bold text-base-content">{{ $user->full_name }}</h1>
          </div>
          <div class="flex items-center justify-center mt-2 space-x-4 text-sm text-gray-500">
              <div class="flex items-center space-x-1">
                  <x-icon name="lucide.phone" class="w-4 h-4" />
                  <span>{{ $user->mobile ?? 'N/A' }}</span>
              </div>
              <div class="flex items-center space-x-1">
                  <x-icon name="lucide.mail" class="w-4 h-4" />
                  <span>{{ $user->email }}</span>
              </div>
              <div class="flex items-center space-x-1">
                  <x-icon name="lucide.map-pin" class="w-4 h-4" />
                  <span>{{ $user->city ?? 'N/A' }}</span>
              </div>
          </div>
      </div>
      <div class="flex items-center justify-center w-full pb-2 mb-8">
          <x-tabs wire:model="selectedTab" active-class="bg-primary rounded !text-white" label-class="px-4 py-2 font-semibold"
              label-div-class="p-2 mx-auto rounded bg-primary/5 w-fit">
              <x-tab name="information-tab" label="{{ __('user.profile.tabs.information-tab') }}" icon="lucide.user">
                  <livewire:admin.pages.profile.update-information-section />
              </x-tab>
              <x-tab name="settings-tab" label="{{ __('user.profile.tabs.settings-tab') }}" icon="lucide.settings">
                  <livewire:admin.pages.profile.setting-section />
              </x-tab>
              <x-tab name="security-tab" label="{{ __('user.profile.tabs.security-tab') }}" icon="lucide.shield">
                  <livewire:admin.pages.profile.update-password-section />
              </x-tab>
              <x-tab name="notifications-tab" label="{{ __('user.profile.tabs.notifications-tab') }}"
                  icon="lucide.bell">
                  <livewire:admin.widget.notification-list-widget />
              </x-tab>
              <x-tab name="tickets-tab" label="{{ __('user.profile.tabs.tickets-tab') }}" icon="lucide.ticket">
                  <livewire:admin.widget.latest-tickets-widget :userId="$user->id" />
              </x-tab>
              <x-tab name="payments-tab" label="{{ __('user.profile.tabs.payments-tab') }}" icon="lucide.credit-card">
                  <livewire:admin.widget.payment-list-widget :userId="$user->id" />
              </x-tab>
              <x-tab name="logout-tab" label="{{ __('user.profile.tabs.logout-tab') }}" icon="lucide.log-out">
                  <livewire:admin.pages.profile.logout-section />
              </x-tab>
          </x-tabs>
      </div>

  </div>
