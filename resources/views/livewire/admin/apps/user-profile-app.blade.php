  <div class="mx-auto p-8">
      <div class="text-center mb-8">
          <div class="relative inline-block">
              <div
                  class="w-24 h-24 rounded-full ring-2 ring-blue-500 ring-offset-4 ring-offset-white overflow-hidden mx-auto mb-4">
                  <img src="{{ $user->getFirstMediaUrl('avatar', \App\Helpers\Constants::RESOLUTION_512_SQUARE) }}"
                      alt="Jenny Klabber" class="w-full h-full object-cover">
              </div>
              <div class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 rounded-full ring-2 ring-white"></div>
          </div>
          <div class="flex items-center justify-center space-x-2">
              <h1 class="text-3xl font-bold text-base-content">{{ $user->full_name }}</h1>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd" />
              </svg>
          </div>
          <div class="flex items-center justify-center mt-2 text-gray-500 text-sm space-x-4">
              <div class="flex items-center space-x-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd"
                          d="M12.707 3.293a1 1 0 00-1.414 0L10 4.586l-1.293-1.293a1 1 0 00-1.414 1.414l1.293 1.293-1.293 1.293a1 1 0 001.414 1.414l1.293-1.293 1.293 1.293a1 1 0 001.414-1.414l-1.293-1.293 1.293-1.293a1 1 0 000-1.414z"
                          clip-rule="evenodd" />
                      <path
                          d="M10 2a8 8 0 100 16 8 8 0 000-16zm-5 8a5 5 0 0110 0v1a1 1 0 11-2 0v-1a3 3 0 00-6 0v1a1 1 0 11-2 0v-1z" />
                  </svg>
                  <span>KeenThemes</span>
              </div>
              <div class="flex items-center space-x-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd"
                          d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                          clip-rule="evenodd" />
                  </svg>
                  <span>SF, Bay Area</span>
              </div>
              <div class="flex items-center space-x-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                      <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                  <span>{{ $user->email }}</span>
              </div>
          </div>
      </div>
      <div class="flex items-center justify-between mb-8 pb-2">
          <x-tabs wire:model="selectedTab">
              <x-tab name="users-tab" label="Users" icon="o-users">
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                      <div class="md:col-span-1 space-y-6">
                          <div class="bg-base-100 shadow-sm p-6 rounded-xl">
                              <div class="text-base-content font-bold text-lg mb-4">Community Badges</div>
                              <div class="flex space-x-2">
                                  <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path
                                              d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                      </svg>
                                  </div>
                                  <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-8V9h2v1a2 2 0 11-4 0v-1h2z"
                                              clip-rule="evenodd" />
                                      </svg>
                                  </div>
                                  <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500"
                                          viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-8V9h2v1a2 2 0 11-4 0v-1h2z"
                                              clip-rule="evenodd" />
                                      </svg>
                                  </div>
                              </div>
                          </div>
                          <div class="bg-base-100 shadow-sm p-6 rounded-xl">
                              <div class="text-base-content font-bold text-lg mb-4">About</div>
                              <div class="space-y-2 text-sm text-base-content/50">
                                  <div><span class="font-semibold text-base-content">Age:</span> 32</div>
                                  <div><span class="font-semibold text-base-content">Occupation:</span> UI/UX Designer
                                  </div>
                                  <div><span class="font-semibold text-base-content">Experience:</span> 8 years</div>
                              </div>
                          </div>
                      </div>
                      <div class="md:col-span-2">
                          <div
                              class="bg-base-100 shadow-sm p-6 rounded-xl flex md:flex-row flex-col justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
                              <div class="flex-1 text-center md:text-left">
                                  <div class="text-base-content font-bold text-xl mb-2">Unlock Creative Partnerships on
                                      Our Blog</div>
                                  <p class="text-base-content/50 mb-4">Explore exciting collaboration opportunities with
                                      our blog. We're
                                      open to partnerships, guest posts, and more. Join us to share your insights
                                      and grow your
                                      audience.</p>
                                  <a href="#"
                                      class="inline-block text-blue-500 hover:text-blue-600 font-medium">Get
                                      Started</a>
                              </div>
                              <div class="flex-shrink-0">
                                  <img src="https://placehold.co/200x150/FFFFFF/2C3E50?text=Illustration"
                                      alt="Illustration" class="rounded-lg">
                              </div>
                          </div>
                      </div>
                  </div>
              </x-tab>
              <x-tab name="tricks-tab" label="Tricks" icon="o-sparkles">
                  <div>Tricks</div>
              </x-tab>
              <x-tab name="musics-tab" label="Musics" icon="o-musical-note">
                  <div>Musics</div>
              </x-tab>
          </x-tabs>
      </div>

  </div>
