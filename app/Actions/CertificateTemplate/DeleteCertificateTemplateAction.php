<?php

declare(strict_types=1);

namespace App\Actions\CertificateTemplate;

use App\Models\CertificateTemplate;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCertificateTemplateAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(CertificateTemplate $certificateTemplate): bool
    {
        if ($certificateTemplate->is_default) {
            abort(422, __('certificateTemplate.cannot_delete_default'));
        }

        return $certificateTemplate->deleteOrFail();
    }
}
