<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceAccessController extends Controller
{
    use AuthorizesRequests;

    /**
     * Download or stream a resource file.
     * For video/audio/image: streams inline for browser playback
     * For other files: downloads the file
     */
    public function download(Resource $resource): BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('download', $resource);

        // Handle external links
        if ($resource->isExternalLink()) {
            return redirect($resource->path);
        }

        // Get the media file
        $media = $resource->getFirstMedia($resource->type->value);

        if ( ! $media) {
            throw new NotFoundHttpException('فایل یافت نشد.');
        }

        $filePath = $media->getPath();

        if ( ! file_exists($filePath)) {
            throw new NotFoundHttpException('فایل در سرور یافت نشد.');
        }

        // For media files (video, audio, image), stream inline for browser playback
        if ($resource->type->isMedia()) {
            return Response::file($filePath, [
                'Content-Type' => $media->mime_type ?? 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="' . $resource->title . '"',
            ]);
        }

        // For other files (PDF, etc.), download
        return Response::download($filePath, $resource->title, [
            'Content-Type' => $media->mime_type ?? 'application/octet-stream',
        ]);
    }
}
