<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-card :title="trans('user.page.resume_section')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-textarea :label="trans('user.page.resume_description')" wire:model="resume_description" />
            </div>
            <div>
                <x-file wire:model="resume_image" :label="trans('user.page.resume_image')" hint="Only Image"
                    accept="image/jpeg,image/png,image/gif,image/webp" />
            </div>
            <div class="flex items-center justify-center rounded-lg bg-base-200 min-h-[200px] p-4">
                @php
                    $resumePreviewUrl = $this->getResumePreviewUrl();
                    $resumeImages = $resumePreviewUrl ? [$resumePreviewUrl] : [];
                @endphp
                @if (count($resumeImages) > 0)
                    <x-image-gallery :images="$resumeImages"
                        class="h-64 rounded-lg w-full cursor-pointer [&_.carousel-item]:max-h-64 [&_img]:object-contain" />
                @else
                    <span class="text-base-content/50">{{ trans('user.page.no_image') }}</span>
                @endif
            </div>
        </div>
    </x-card>

    <x-card :title="trans('user.page.education_section')" shadow separator progress-indicator="submit" class="mt-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-textarea :label="trans('user.page.education_description')" wire:model="education_description" />
            </div>
            <div>
                <x-file wire:model="education_image" :label="trans('user.page.education_image')" hint="Only Image"
                    accept="image/jpeg,image/png,image/gif,image/webp" />
            </div>
            <div class="flex items-center justify-center rounded-lg bg-base-200 min-h-[200px] p-4">
                @php
                    $educationPreviewUrl = $this->getEducationPreviewUrl();
                    $educationImages = $educationPreviewUrl ? [$educationPreviewUrl] : [];
                @endphp
                @if (count($educationImages) > 0)
                    <x-image-gallery :images="$educationImages"
                        class="h-64 rounded-lg w-full cursor-pointer [&_.carousel-item]:max-h-64 [&_img]:object-contain" />
                @else
                    <span class="text-base-content/50">{{ trans('user.page.no_image') }}</span>
                @endif
            </div>
        </div>
    </x-card>

    <x-card :title="trans('user.page.courses_section')" shadow separator progress-indicator="submit" class="mt-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-textarea :label="trans('user.page.courses_description')" wire:model="courses_description" />
            </div>
            <div>
                <x-file wire:model="courses_image" :label="trans('user.page.courses_image')" hint="Only Image"
                    accept="image/jpeg,image/png,image/gif,image/webp" />
            </div>
            <div class="flex items-center justify-center rounded-lg bg-base-200 min-h-[200px] p-4">
                @php
                    $coursesPreviewUrl = $this->getCoursesPreviewUrl();
                    $coursesImages = $coursesPreviewUrl ? [$coursesPreviewUrl] : [];
                @endphp
                @if (count($coursesImages) > 0)
                    <x-image-gallery :images="$coursesImages"
                        class="h-64 rounded-lg w-full cursor-pointer [&_.carousel-item]:max-h-64 [&_img]:object-contain" />
                @else
                    <span class="text-base-content/50">{{ trans('user.page.no_image') }}</span>
                @endif
            </div>
        </div>
    </x-card>
    <x-admin.shared.form-actions />
</form>
