<div class="">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div
                class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm flex flex-col justify-center min-h-[300px] w-full">

                <div class="flex flex-col items-center justify-between mt-9">
                    <!-- Role Title -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                        {{ $role->name }}
                    </h3>

                    <!-- Role User Count -->
                    <p class="text-sm text-gray-500 mb-4">
                        تعداد کاربرانی که این نقش را دارند: {{ $role->users->count() }}
                    </p>
                </div>

                <!-- Buttons -->
                <div class="mt-auto flex gap-3 pt-2">
                    <!-- Show Details -->
                    {{--                    <button wire:click="show({{ $role->id }})"--}}
                    {{--                            class="flex-1 text-sm py-2 px-3 rounded-md border border-gray-300--}}
                    {{--                                    text-gray-700 bg-white transition-all duration-150--}}
                    {{--                                    hover:bg-blue-50 hover:text-blue-700 hover:border-blue-400--}}
                    {{--                                    active:bg-blue-100 active:shadow-inner cursor-pointer">--}}
                    {{--                        نمایش جزئیات--}}
                    {{--                    </button>--}}

                    <!-- Edit -->
                    <button wire:click="edit({{ $role->id }})"
                            class="flex-1 text-sm py-2 px-3 rounded-md border border-gray-300
                                    text-gray-700 bg-white transition-all duration-150
                                    hover:bg-green-50 hover:text-green-700 hover:border-green-400
                                    active:bg-green-100 active:shadow-inner cursor-pointer">
                        ویرایش
                    </button>
                </div>

            </div>
        @endforeach
        <div wire:click="edit"
             class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm flex flex-col items-center justify-center
                        cursor-pointer transition-all duration-150
                        hover:shadow-md hover:border-blue-400 hover:bg-blue-50/20
                        active:shadow-none active:scale-[0.98]
                        min-h-[300px] w-full text-center">

            <!-- Illustration Image -->
            <img src="{{ asset('assets/web/img/add-role') }}" alt="Add Role"
                 class="w-35 h-35 mb-6"/>

            <!-- Card Title -->
            <h3 class="text-base font-medium text-gray-500">
                نقش جدید
            </h3>
        </div>
    </div>
</div>
