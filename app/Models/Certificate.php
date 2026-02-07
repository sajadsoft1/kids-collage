<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Certificate Model
 *
 * Issued on course completion to certify student achievement.
 * Includes verification features and grade tracking.
 *
 * @property int                 $id
 * @property int                 $enrollment_id
 * @property int|null            $certificate_template_id
 * @property \Carbon\Carbon      $issue_date
 * @property string              $grade
 * @property string              $certificate_path
 * @property string              $signature_hash
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read Enrollment              $enrollment
 * @property-read CertificateTemplate|null $certificateTemplate
 */
class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'certificate_template_id',
        'issue_date',
        'grade',
        'certificate_path',
        'signature_hash',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /** Get the enrollment for this certificate. */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /** Get the certificate template used for this certificate. */
    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    /** Get the student (user) for this certificate. */
    public function getStudentAttribute()
    {
        return $this->enrollment->user;
    }

    /** Get the course for this certificate. */
    public function getCourseAttribute()
    {
        return $this->enrollment->course;
    }

    /** Get the course template for this certificate. */
    public function getCourseTemplateAttribute()
    {
        return $this->course->template;
    }

    /** Get the grade description. */
    public function getGradeDescriptionAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'Excellent (90-100%)',
            'B' => 'Good (80-89%)',
            'C' => 'Satisfactory (70-79%)',
            'D' => 'Passing (60-69%)',
            'F' => 'Failing (Below 60%)',
            default => 'Unknown Grade',
        };
    }

    /** Get the grade color for UI display. */
    public function getGradeColorAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'green',
            'B' => 'blue',
            'C' => 'yellow',
            'D' => 'orange',
            'F' => 'red',
            default => 'gray',
        };
    }

    /** Check if this certificate is valid. */
    public function isValid(): bool
    {
        return $this->verifySignature();
    }

    /** Verify the certificate signature (recomputed hash matches stored hash). */
    public function verifySignature(): bool
    {
        return hash_equals($this->signature_hash, $this->generateExpectedSignatureHash());
    }

    /** Generate the expected signature hash for verification (date-only, matches issuance). */
    protected function generateExpectedSignatureHash(): string
    {
        $data = [
            'enrollment_id' => $this->enrollment_id,
            'user_id' => $this->enrollment->user_id,
            'course_id' => $this->enrollment->course_id,
            'issue_date' => $this->issue_date->toDateString(),
        ];

        return hash('sha256', json_encode($data) . config('app.key'));
    }

    /** Get the certificate verification URL. */
    public function getVerificationUrlAttribute(): string
    {
        return route('certificates.verify', [
            'id' => $this->id,
            'hash' => $this->signature_hash,
        ]);
    }

    /** Get the certificate download URL. */
    public function getDownloadUrlAttribute(): string
    {
        return route('certificates.download', [
            'id' => $this->id,
            'hash' => $this->signature_hash,
        ]);
    }

    /** Check if this certificate is recent (issued within last 30 days). */
    public function isRecent(): bool
    {
        return $this->issue_date->gt(now()->subDays(30));
    }

    /** Get the age of this certificate in days. */
    public function getAgeInDaysAttribute(): int
    {
        return now()->diffInDays($this->issue_date);
    }

    /** Get the formatted issue date. */
    public function getFormattedIssueDateAttribute(): string
    {
        return jdate($this->issue_date)->format('%A, %d %B %Y');
    }

    /** Get the certificate number (for display purposes). */
    public function getCertificateNumberAttribute(): string
    {
        return 'CERT-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    /** Get the student's full name. */
    public function getStudentNameAttribute(): string
    {
        return $this->student->full_name ?? 'Unknown Student';
    }

    /** Get the course title. */
    public function getCourseTitleAttribute(): string
    {
        return $this->courseTemplate->title ?? 'Unknown Course';
    }

    /** Get the course level. */
    public function getCourseLevelAttribute(): ?string
    {
        return $this->courseTemplate->level?->title;
    }

    /** Get the course duration. */
    public function getCourseDurationAttribute(): string
    {
        $totalMinutes = $this->courseTemplate->total_duration;
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours} hours {$minutes} minutes" : "{$hours} hours";
        }

        return "{$minutes} minutes";
    }

    /** Get the student's attendance percentage. */
    public function getAttendancePercentageAttribute(): float
    {
        return $this->enrollment->attendance_percentage;
    }

    /** Get the student's final progress percentage. */
    public function getProgressPercentageAttribute(): float
    {
        return $this->enrollment->progress_percent;
    }

    /** Check if the certificate file exists. */
    public function fileExists(): bool
    {
        return file_exists(storage_path('app/' . $this->certificate_path));
    }

    /** Get the certificate file size. */
    public function getFileSizeAttribute(): ?int
    {
        if ( ! $this->fileExists()) {
            return null;
        }

        return filesize(storage_path('app/' . $this->certificate_path));
    }

    /** Get the formatted file size. */
    public function getFormattedFileSizeAttribute(): ?string
    {
        $size = $this->file_size;

        if ( ! $size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /** Regenerate the certificate PDF using the certificate template. */
    public function regenerateFile(): bool
    {
        $newPath = "certificates/certificate_{$this->id}_{$this->enrollment_id}_" . now()->format('YmdHis') . '.pdf';

        $this->update(['certificate_path' => $newPath]);

        $service = app(\App\Services\Certificate\CertificatePdfService::class);
        $service->generateForCertificate($this->refresh());

        return true;
    }

    /** Scope for certificates by grade. */
    public function scopeByGrade($query, string $grade)
    {
        return $query->where('grade', $grade);
    }

    /** Scope for certificates issued in date range. */
    public function scopeIssuedBetween($query, \Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        return $query->whereBetween('issue_date', [$startDate, $endDate]);
    }

    /** Scope for recent certificates. */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('issue_date', '>=', now()->subDays($days));
    }

    /** Scope for certificates by enrollment. */
    public function scopeByEnrollment($query, int $enrollmentId)
    {
        return $query->where('enrollment_id', $enrollmentId);
    }

    /** Scope for certificates by course. */
    public function scopeByCourse($query, int $courseId)
    {
        return $query->whereHas('enrollment', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }

    /** Scope for certificates by student. */
    public function scopeByStudent($query, int $userId)
    {
        return $query->whereHas('enrollment', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /** Get certificate statistics. */
    public static function getStatistics(): array
    {
        return [
            'total' => static::count(),
            'recent' => static::recent()->count(),
            'by_grade' => static::selectRaw('grade, COUNT(*) as count')
                ->groupBy('grade')
                ->pluck('count', 'grade')
                ->toArray(),
            'this_month' => static::issuedBetween(now()->startOfMonth(), now()->endOfMonth())->count(),
            'this_year' => static::issuedBetween(now()->startOfYear(), now()->endOfYear())->count(),
        ];
    }
}
