<?php

declare(strict_types=1);

namespace App\Actions\CertificateTemplate;

use App\Models\CertificateTemplate;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Update an existing certificate template.
 *
 * @param array{
 *     title?: string,
 *     slug?: string,
 *     is_default?: bool,
 *     layout?: string,
 *     header_text?: string|null,
 *     body_text?: string|null,
 *     footer_text?: string|null,
 *     institute_name?: string|null,
 *     logo?: mixed,
 *     background?: mixed,
 *     signature?: mixed
 * } $payload
 */
class UpdateCertificateTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly FileService $fileService,
    ) {}

    public function handle(CertificateTemplate $certificateTemplate, array $payload): CertificateTemplate
    {
        $fillable = [
            'title', 'slug', 'is_default', 'layout',
            'header_text', 'body_text', 'footer_text', 'institute_name',
        ];

        $certificateTemplate->update(Arr::only($payload, $fillable));

        if (array_key_exists('is_default', $payload) && ! empty($payload['is_default'])) {
            CertificateTemplate::where('id', '!=', $certificateTemplate->id)->update(['is_default' => false]);
        }

        $this->fileService->addMedia($certificateTemplate, Arr::get($payload, 'logo'), 'logo');
        $this->fileService->addMedia($certificateTemplate, Arr::get($payload, 'background'), 'background');
        $this->fileService->addMedia($certificateTemplate, Arr::get($payload, 'signature'), 'signature');

        return $certificateTemplate->refresh();
    }
}
