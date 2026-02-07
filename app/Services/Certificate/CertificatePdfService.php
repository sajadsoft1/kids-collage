<?php

declare(strict_types=1);

namespace App\Services\Certificate;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Generates certificate PDFs from a template and certificate data.
 */
class CertificatePdfService
{
    /**
     * Build placeholder data from a Certificate for use in template text.
     *
     * @return array<string, string>
     */
    public function buildDataFromCertificate(Certificate $certificate): array
    {
        return [
            'student_name' => $certificate->student_name,
            'course_title' => $certificate->course_title,
            'course_level' => $certificate->course_level ?? '',
            'issue_date' => $certificate->formatted_issue_date,
            'grade' => $certificate->grade,
            'certificate_number' => $certificate->certificate_number,
            'duration' => $certificate->course_duration,
            'institute_name' => $certificate->certificateTemplate?->institute_name ?? config('app.name'),
        ];
    }

    /**
     * Replace placeholders in text with data.
     *
     * @param array<string, string> $data
     */
    public function replacePlaceholders(?string $text, array $data): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        foreach ($data as $key => $value) {
            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }

        return $text;
    }

    /**
     * Generate PDF for a certificate and save to its certificate_path.
     * Uses the certificate's template (or default) and fills placeholders from certificate data.
     */
    public function generateForCertificate(Certificate $certificate): void
    {
        $template = $certificate->certificateTemplate ?? CertificateTemplate::default()->first();

        if ( ! $template) {
            return;
        }

        $data = $this->buildDataFromCertificate($certificate);

        $headerText = $this->replacePlaceholders($template->header_text, $data);
        $bodyText = $this->replacePlaceholders($template->body_text, $data);
        $footerText = $this->replacePlaceholders($template->footer_text, $data);

        $logoPath = $template->getFirstMedia('logo')?->getPath();
        $backgroundPath = $template->getFirstMedia('background')?->getPath();
        $signaturePath = $template->getFirstMedia('signature')?->getPath();

        $pdf = Pdf::loadView('certificates.template', [
            'template' => $template,
            'headerText' => $headerText,
            'bodyText' => $bodyText,
            'footerText' => $footerText,
            'instituteName' => $template->institute_name ?? config('app.name'),
            'logoPath' => $logoPath,
            'backgroundPath' => $backgroundPath,
            'signaturePath' => $signaturePath,
        ]);

        $fullPath = Storage::path($certificate->certificate_path);
        $directory = dirname($fullPath);

        if ( ! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf->save($fullPath);
    }
}
