<?php

declare(strict_types=1);

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileService
{
    public static function dropzoneImagePathGenerator(): string
    {
        $year = Carbon::now()->year;

        return "uploads/images/dropzone/{$year}/";
    }

    public function addMedia($model, $file, $collection = 'image'): void
    {
        if ($file) {
            $model->addMedia($file)->toMediaCollection($collection);
        }
    }

    public function deleteAllMedia($model): void
    {
        foreach ($model->getRegisteredMediaCollections() as $collection) {
            $model->clearMediaCollection($collection->name);
        }
    }

    public function addMultipleMedia($model, $count, $requestImageCounter = 'image', $collection = 'slider'): void
    {
        for ($i = 1; $i <= $count; $i++) {
            if (request()->hasFile($requestImageCounter . $i)) {
                $model->addMediaFromRequest($requestImageCounter . $i)->toMediaCollection($collection);
            }
        }
    }

    public function updateMultipleMedia($model, $count, $imageList = [], $requestImageCounter = 'image', $collection = 'slider', $collectionNameChecker = '720'): void
    {
        $sliders = $model->getMedia($collection);
        if (count($sliders) > 0) {
            foreach ($sliders as $slider) {
                if ( ! in_array($slider->getUrl($collectionNameChecker), $imageList, true)) {
                    $slider->delete();
                }
            }
        }

        for ($i = 1; $i <= $count; $i++) {
            if (request()->hasFile($requestImageCounter . $i)) {
                $model->addMediaFromRequest($requestImageCounter . $i)->toMediaCollection($collection);
            }
        }
    }

    public function addFromDropzone($model, $requestImageCounter = 'documentsDropzone', $collection = 'slides'): void
    {
        foreach (request()->input($requestImageCounter, []) as $file) {
            $model->addMedia(self::dropzoneImagePathGenerator() . $file)->toMediaCollection($collection);
        }
    }

    public function updateFromDropzone($model, $requestImageCounter = 'documentsDropzone', $collection = 'slides'): void
    {
        $sliders = $model->getMedia($collection);
        if (count($sliders) > 0) {
            foreach ($sliders as $slider) {
                if ( ! in_array($slider->file_name, request()->input($requestImageCounter, []), true)) {
                    $slider->delete();
                }
            }
        }
        $fileNames = $model->getMedia($collection)->pluck('file_name')->toArray();
        foreach (request()->input($requestImageCounter, []) as $fileName) {
            if (count($fileNames) === 0 || ! in_array($fileName, $fileNames, true)) {
                $model->addMedia(self::dropzoneImagePathGenerator() . $fileName)->toMediaCollection($collection);
            }
        }
    }

    public function uploadMedia($model, UploadedFile $file, string $collection = 'image', ?string $fileName = null): Media
    {
        $disk = config('media-library.disk_name');
        $mediaAdder = $model->addMedia($file);

        if ($fileName) {
            $mediaAdder->usingFileName($fileName);
        }

        return $mediaAdder->toMediaCollection($collection, $disk);
    }

    public function getMediaUrl($model, string $collection, string $conversion = ''): string
    {
        return $model->getFirstMediaUrl($collection, $conversion);
    }

    public function deleteMedia($model, string $collection): void
    {
        $model->clearMediaCollection($collection);
    }

    public function getAllMedia($model, string $collection)
    {
        return $model->getMedia($collection);
    }

    public function getMediaById($model, int $mediaId): ?Media
    {
        return $model->media()->find($mediaId);
    }

    public function deleteMediaById($model, int $mediaId): void
    {
        $media = $this->getMediaById($model, $mediaId);
        $media?->delete();
    }
}
