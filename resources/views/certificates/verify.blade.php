<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('certificateTemplate.verification') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-base-200">
    <div class="card bg-base-100 shadow-xl max-w-md w-full mx-4">
        <div class="card-body">
            <h1 class="card-title justify-center text-xl">{{ __('certificateTemplate.verification') }}</h1>
            @if($valid)
                <p class="text-success text-center">{{ __('certificateTemplate.verification_valid') }}</p>
                <div class="text-sm text-base-content/80 space-y-1 mt-4">
                    <p><strong>{{ __('certificateTemplate.placeholders.certificate_number') }}:</strong> {{ $certificate->certificate_number }}</p>
                    <p><strong>{{ __('certificateTemplate.placeholders.student_name') }}:</strong> {{ $certificate->student_name }}</p>
                    <p><strong>{{ __('certificateTemplate.placeholders.course_title') }}:</strong> {{ $certificate->course_title }}</p>
                    <p><strong>{{ __('certificateTemplate.placeholders.issue_date') }}:</strong> {{ $certificate->formatted_issue_date }}</p>
                </div>
                <div class="card-actions justify-center mt-6">
                    <a href="{{ route('certificates.download', ['id' => $certificate->id, 'hash' => $certificate->signature_hash]) }}"
                       class="btn btn-primary">{{ __('general.download') ?? 'Download' }}</a>
                </div>
            @else
                <p class="text-error text-center">{{ __('certificateTemplate.verification_invalid') }}</p>
            @endif
        </div>
    </div>
</body>
</html>
