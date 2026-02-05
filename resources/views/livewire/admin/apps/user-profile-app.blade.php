  <div class="p-8 mx-auto w-full">
      {{-- Profile Card --}}
      <x-card class="mb-8">
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

              {{-- Right Section: User Profile Info --}}
              <div class="flex gap-4 lg:col-span-2">
                  {{-- Profile Image --}}
                  <div class="relative shrink-0">
                      <div
                          class="overflow-hidden w-24 h-24 rounded-lg ring-2 ring-offset-2 ring-primary ring-offset-base-100">
                          <img src="{{ $user->getFirstMediaUrl('avatar', \App\Helpers\Constants::RESOLUTION_512_SQUARE) }}"
                              alt="{{ $user->full_name }}" class="object-cover w-full h-full">
                      </div>
                      <div class="absolute right-0 bottom-0 w-4 h-4 rounded-full border-2 bg-success border-base-100">
                      </div>
                  </div>

                  {{-- User Details --}}
                  <div class="flex-1 min-w-0">
                      <div class="flex gap-2 items-center mb-2">
                          <h2 class="text-xl font-bold text-base-content">{{ $user->full_name }}</h2>
                          <x-icon name="o-check-circle" class="w-5 h-5 text-success shrink-0" />
                      </div>

                      <div class="space-y-2 text-sm text-base-content/70">
                          {{-- Email --}}
                          <div class="flex gap-2 items-center">
                              <x-icon name="o-envelope" class="w-4 h-4 shrink-0" />
                              <span class="truncate">{{ $user->email }}</span>
                          </div>

                          {{-- Job Title --}}
                          <div class="flex gap-2 items-center">
                              <x-icon name="o-user" class="w-4 h-4 shrink-0" />
                              <span>{{ $user->profile?->extra_attributes?->job_title ?? 'کاربر سیستم' }}</span>
                          </div>

                          {{-- Location --}}
                          @if ($user->profile?->address)
                              <div class="flex gap-2 items-center">
                                  <x-icon name="o-map-pin" class="w-4 h-4 shrink-0" />
                                  <span>{{ $user->profile->address }}</span>
                              </div>
                          @endif

                          {{-- Bio/Description --}}
                          @if ($user->profile?->extra_attributes?->bio)
                              <p class="mt-3 text-sm leading-relaxed text-base-content/80">
                                  {{ $user->profile->extra_attributes->bio }}
                              </p>
                          @else
                              <p class="mt-3 text-sm italic leading-relaxed text-base-content/60">
                                  توضیحاتی برای این کاربر ثبت نشده است.
                              </p>
                          @endif
                      </div>
                  </div>
              </div>


              {{-- Left Section: Actions & Progress --}}
              <div class="flex flex-col gap-4 lg:col-span-1">
                  {{-- Action Buttons --}}
                  <div class="flex gap-3 justify-end">
                      <x-button class="btn-sm w-fit btn-secondary" icon="o-phone" disabled>
                          تماس
                      </x-button>
                      <x-button class="btn-sm w-fit btn-secondary" icon="o-chat-bubble-left-right" disabled>
                          پیام
                      </x-button>
                  </div>

                  {{-- Progress Bar --}}
                  {{-- <div class="space-y-2">
                      <div class="flex justify-between items-center text-sm">
                          <span class="font-semibold text-base-content">100%</span>
                          <span class="text-base-content/70">پیشرفت</span>
                      </div>
                      <div class="overflow-hidden w-full h-2 rounded-full bg-base-200">
                          <div class="h-full rounded-full transition-all duration-300 bg-success" style="width: 100%">
                          </div>
                      </div>
                  </div> --}}
              </div>


          </div>

          {{-- Statistics Section --}}
          <div class="pt-6 mt-6 border-t border-base-300" hidden>
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                  <!-- Income -->
                  <div class="flex gap-4 justify-center items-start">
                      <!-- Icon Right -->
                      <div class="shrink-0">
                          <x-icon name="o-banknotes" class="w-10 h-10 text-base-content/40" />
                      </div>
                      <!-- Info Left -->
                      <div class="flex flex-col items-start text-start">
                          <span class="text-lg font-bold text-base-content">موجودی</span>
                          <span class="mt-1 text-2xl font-extrabold text-base-content">
                              {{ number_format(0) }}
                              <span class="text-sm text-base-content/50">ریال</span>
                          </span>
                      </div>
                  </div>
                  <!-- Income -->
                  <div class="flex gap-4 justify-center items-start">
                      <!-- Icon Right -->
                      <div class="shrink-0">
                          <x-icon name="o-banknotes" class="w-10 h-10 text-base-content/40" />
                      </div>
                      <!-- Info Left -->
                      <div class="flex flex-col items-start text-start">
                          <span class="text-lg font-bold text-base-content">موجودی</span>
                          <span class="mt-1 text-2xl font-extrabold text-base-content">
                              {{ number_format(0) }}
                              <span class="text-sm text-base-content/50">ریال</span>
                          </span>
                      </div>
                  </div>
                  <!-- Income -->
                  <div class="flex gap-4 justify-center items-start">
                      <!-- Icon Right -->
                      <div class="shrink-0">
                          <x-icon name="o-banknotes" class="w-10 h-10 text-base-content/40" />
                      </div>
                      <!-- Info Left -->
                      <div class="flex flex-col items-start text-start">
                          <span class="text-lg font-bold text-base-content">موجودی</span>
                          <span class="mt-1 text-2xl font-extrabold text-base-content">
                              {{ number_format(0) }}
                              <span class="text-sm text-base-content/50">ریال</span>
                          </span>
                      </div>
                  </div>
              </div>
          </div>
      </x-card>
      <div class="flex justify-center items-center pb-2 mb-8 w-full">
          <x-tabs wire:model="selectedTab" active-class="bg-primary rounded !text-white"
              label-class="px-4 py-3 font-semibold" label-div-class="p-2 rounded bg-base-100">
              <x-tab name="information-tab" label="{{ __('user.profile.tabs.information-tab') }}" icon="lucide.user">
                  <livewire:admin.pages.profile.update-information-section />
              </x-tab>
              <x-tab name="settings-tab" label="{{ __('user.profile.tabs.settings-tab') }}" icon="lucide.settings"
                  hidden>
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
