<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Public certificate verification and download.
 */
class CertificateController extends Controller
{
    /** Resolve certificate by id and validate hash; abort 404 if not found or hash mismatch. */
    protected function findAndValidate(int $id, string $hash): Certificate
    {
        $certificate = Certificate::find($id);

        if ( ! $certificate || ! hash_equals($certificate->signature_hash, $hash)) {
            abort(404);
        }

        return $certificate;
    }

    /** Show certificate verification page (public, no auth). */
    public function verify(int $id, string $hash): View
    {
        $certificate = $this->findAndValidate($id, $hash);

        return view('certificates.verify', [
            'certificate' => $certificate,
            'valid' => true,
        ]);
    }

    /** Download certificate PDF (public, validated by hash). Regenerates file if missing. */
    public function download(int $id, string $hash): BinaryFileResponse|Response
    {
        $certificate = $this->findAndValidate($id, $hash);

        $path = Storage::path($certificate->certificate_path);

        if ( ! file_exists($path)) {
            $certificate->regenerateFile();
            $path = Storage::path($certificate->certificate_path);
            if ( ! file_exists($path)) {
                throw new NotFoundHttpException(__('certificateTemplate.download_not_found'));
            }
        }

        return response()->download($path, 'certificate-' . $certificate->certificate_number . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
