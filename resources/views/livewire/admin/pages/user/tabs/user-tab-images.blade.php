@php
    use App\Helpers\Constants;
@endphp
<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-card :title="trans('user.page.images_section')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4">
            <x-admin.shared.single-file-upload :hint="trans('user.page.image.hint')" :label="trans('user.page.image.avatar')" wire_model="avatar" :default_image="$user->getFirstMediaUrl('avatar', Constants::RESOLUTION_480_SQUARE)"
                :crop_after_change="true" />

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <x-file wire:model="national_card" :label="trans('user.page.image.national_card')" hint="Only Image"
                        accept="image/jpeg,image/png,image/gif,image/webp" />
                </div>
                <div class="flex items-center justify-center rounded-lg bg-base-200 min-h-[200px] p-4">
                    @php
                        $nationalCardPreviewUrl = $national_card
                            ? $national_card->temporaryUrl()
                            : $user->getFirstMediaUrl('national_card', Constants::RESOLUTION_480_SQUARE);
                        $nationalCardImages = $nationalCardPreviewUrl ? [$nationalCardPreviewUrl] : [];
                    @endphp
                    @if (count($nationalCardImages) > 0)
                        <x-image-gallery :images="$nationalCardImages"
                            class="h-64 rounded-lg w-full cursor-pointer [&_.carousel-item]:max-h-64 [&_img]:object-contain" />
                    @else
                        <span class="text-base-content/50">{{ trans('user.page.no_image') }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <x-file wire:model="birth_certificate" :label="trans('user.page.image.birth_certificate')" hint="Only Image"
                        accept="image/jpeg,image/png,image/gif,image/webp" />
                </div>
                <div class="flex items-center justify-center rounded-lg bg-base-200 min-h-[200px] p-4">
                    @php
                        $birthCertificatePreviewUrl = $birth_certificate
                            ? $birth_certificate->temporaryUrl()
                            : $user->getFirstMediaUrl('birth_certificate', Constants::RESOLUTION_480_SQUARE);
                        $birthCertificateImages = $birthCertificatePreviewUrl ? [$birthCertificatePreviewUrl] : [];
                    @endphp
                    @if (count($birthCertificateImages) > 0)
                        <x-image-gallery :images="$birthCertificateImages"
                            class="h-64 rounded-lg w-full cursor-pointer [&_.carousel-item]:max-h-64 [&_img]:object-contain" />
                    @else
                        <span class="text-base-content/50">{{ trans('user.page.no_image') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </x-card>
    <x-admin.shared.form-actions />
</form>
