<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 40px; box-sizing: border-box; }
        .certificate { position: relative; max-width: 800px; margin: 0 auto; padding: 40px; min-height: 500px; }
        .certificate-bg { position: absolute; inset: 0; z-index: 0; object-fit: cover; width: 100%; height: 100%; }
        .certificate-content { position: relative; z-index: 1; }
        .logo { max-width: 120px; max-height: 120px; margin-bottom: 20px; }
        .header { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 24px; }
        .body { text-align: center; font-size: 16px; line-height: 1.6; margin-bottom: 24px; white-space: pre-wrap; }
        .footer { text-align: center; font-size: 12px; margin-top: 32px; }
        .institute { font-size: 14px; margin-bottom: 8px; }
        .signature-img { max-width: 180px; max-height: 80px; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="certificate">
        @if($backgroundPath && file_exists($backgroundPath))
            <img src="{{ $backgroundPath }}" alt="" class="certificate-bg" />
        @endif
        <div class="certificate-content">
            @if($logoPath && file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Logo" class="logo" />
            @endif
            @if($instituteName)
                <div class="institute">{{ $instituteName }}</div>
            @endif
            @if($headerText)
                <div class="header">{{ $headerText }}</div>
            @endif
            @if($bodyText)
                <div class="body">{{ $bodyText }}</div>
            @endif
            @if($signaturePath && file_exists($signaturePath))
                <div class="footer">
                    <img src="{{ $signaturePath }}" alt="Signature" class="signature-img" />
                </div>
            @endif
            @if($footerText)
                <div class="footer">{{ $footerText }}</div>
            @endif
        </div>
    </div>
</body>
</html>
