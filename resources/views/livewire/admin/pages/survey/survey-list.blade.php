<div class="">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
        @foreach ($surveys as $survey)
            <div
                class="flex flex-col justify-center bg-base-100 rounded-xl border border-base-300 p-6 shadow-sm min-h-[300px] w-full">

                <div class="flex flex-col items-center justify-between mt-9">
                    <h3 class="mb-1 text-lg font-semibold text-content">
                        {{ $survey->title }}
                    </h3>

                    <p class="mb-4 text-sm text-content-muted">
                        تعداد سوالات: {{ $survey->getQuestionsCount() }}
                    </p>
                </div>

                <div class="flex gap-3 pt-2 mt-auto">
                    <button wire:click="edit({{ $survey->id }})"
                        class="flex-1 px-3 py-2 text-sm transition-all duration-150 border border-gray-300 rounded-md cursor-pointer text-content bg-base-100 hover:bg-primary/10 hover:text-primary hover:border-primary active:bg-primary/10 active:shadow-inner">
                        ویرایش
                    </button>
                    <button wire:click="results({{ $survey->id }})"
                        class="flex-1 px-3 py-2 text-sm transition-all duration-150 border border-gray-300 rounded-md cursor-pointer text-content bg-base-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-400 active:bg-blue-100 active:shadow-inner">
                        نتایج
                    </button>
                </div>
            </div>
        @endforeach
        <div wire:click="edit"
            class="bg-base-100 rounded-xl border border-gray-200 p-6 shadow-sm flex flex-col items-center justify-center
                        cursor-pointer transition-all duration-150 text-content
                        hover:shadow-md hover:border-blue-400 hover:bg-blue-50/20
                        active:shadow-none active:scale-[0.98]
                        min-h-[300px] w-full text-center">
            <x-icon name="o-plus" class="w-35 h-35 text-primary" />
            <h3 class="text-base font-medium text-content-muted">
                نظر سنجی جدید
            </h3>
        </div>
    </div>
    <div class="mt-6">
        {{ $surveys->links() }}
    </div>
</div>
