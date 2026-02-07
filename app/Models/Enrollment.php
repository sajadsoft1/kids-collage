<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EnrollmentStatusEnum;
use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Enrollment Model
 *
 * Student registration in a course instance.
 * Tracks student progress, attendance, and completion status.
 *
 * @property int                  $id
 * @property int                  $user_id
 * @property int                  $course_id
 * @property int|null             $order_item_id
 * @property EnrollmentStatusEnum $status
 * @property \Carbon\Carbon       $enrolled_at
 * @property float                $progress_percent
 * @property \Carbon\Carbon|null  $created_at
 * @property \Carbon\Carbon|null  $updated_at
 *
 * @property-read User                                                      $user
 * @property-read Course                                                    $course
 * @property-read OrderItem|null                                            $orderItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance> $attendances
 * @property-read Certificate|null                                          $certificate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ActivityLog> $activityLogs
 */
class Enrollment extends Model
{
    use HasBranch;
    use HasBranchScope;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'order_item_id',
        'status',
        'branch_id',
        'enrolled_at',
        'progress_percent',
    ];

    protected $casts = [
        'status' => EnrollmentStatusEnum::class,
        'enrolled_at' => 'datetime',
        'progress_percent' => 'decimal:2',
    ];

    /** Get the user (student) for this enrollment. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Get the course for this enrollment. */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** Get the order item for this enrollment. */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /** Get the attendances for this enrollment. */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /** Get the certificate for this enrollment. */
    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    /** Get the activity logs for this enrollment. */
    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /** Get present attendances. */
    public function presentAttendances(): HasMany
    {
        return $this->attendances()->where('present', true);
    }

    /** Get absent attendances. */
    public function absentAttendances(): HasMany
    {
        return $this->attendances()->where('present', false);
    }

    /** Get the total attendance count. */
    public function getTotalAttendanceCountAttribute(): int
    {
        return $this->attendances()->count();
    }

    /** Get the present attendance count. */
    public function getPresentAttendanceCountAttribute(): int
    {
        return $this->presentAttendances()->count();
    }

    /** Get the attendance percentage. */
    public function getAttendancePercentageAttribute(): float
    {
        if ($this->total_attendance_count === 0) {
            return 0.0;
        }

        return ($this->present_attendance_count / $this->total_attendance_count) * 100;
    }

    /** Check if this enrollment is active. */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /** Check if this enrollment is completed. */
    public function isCompleted(): bool
    {
        return $this->progress_percent >= 100.0;
    }

    /** Check if this enrollment has a certificate. */
    public function hasCertificate(): bool
    {
        return $this->certificate !== null;
    }

    /** Check if a certificate can be issued for this enrollment (eligible and not yet issued). */
    public function canIssueCertificate(): bool
    {
        if ($this->hasCertificate()) {
            return false;
        }

        $courseTemplate = $this->course->template;

        return $courseTemplate->is_self_paced || $this->attendance_percentage >= 80;
    }

    /** Get the days since enrollment. */
    public function getDaysSinceEnrollmentAttribute(): int
    {
        return (int) now()->diffInDays($this->enrolled_at);
    }

    /** Get the estimated completion date. */
    public function getEstimatedCompletionDateAttribute(): ?\Carbon\Carbon
    {
        if ($this->progress_percent <= 0) {
            return null;
        }

        $daysPerPercent = $this->days_since_enrollment / $this->progress_percent;
        $remainingPercent = 100 - $this->progress_percent;
        $estimatedDaysRemaining = $daysPerPercent * $remainingPercent;

        return now()->addDays((int) $estimatedDaysRemaining);
    }

    /** Update progress for this enrollment. */
    public function updateProgress(float $newProgress): bool
    {
        $newProgress = max(0.0, min(100.0, $newProgress));

        $updated = $this->update(['progress_percent' => $newProgress]);

        if ($updated) {
            $this->logActivity('progress.updated', [
                'old_progress' => $this->getOriginal('progress_percent'),
                'new_progress' => $newProgress,
            ]);
        }

        return $updated;
    }

    /** Increment progress by a percentage. */
    public function incrementProgress(float $increment): bool
    {
        $newProgress = $this->progress_percent + $increment;

        return $this->updateProgress($newProgress);
    }

    /** Mark this enrollment as completed. */
    public function markAsCompleted(): bool
    {
        return DB::transaction(function () {
            $updated = $this->update(['progress_percent' => 100.0]);

            if ($updated) {
                $this->logActivity('enrollment.completed');

                // Issue certificate if course is eligible
                if ($this->course->template->is_self_paced || $this->attendance_percentage >= 80) {
                    $this->issueCertificate();
                }
            }

            return $updated;
        });
    }

    /** Resolve the certificate template for this enrollment (from course template or default). */
    public function resolveCertificateTemplate(): ?CertificateTemplate
    {
        $courseTemplate = $this->course->template;

        if ($courseTemplate->certificate_template_id) {
            return $courseTemplate->certificateTemplate;
        }

        return CertificateTemplate::default()->first();
    }

    /**
     * Get the suggested grade (auto-calculated); admin may override when issuing certificate.
     * When course-linked exam attempts are provided, their average percentage is included in the suggestion.
     */
    public function getSuggestedGrade(?Collection $courseExamAttempts = null): string
    {
        $attendanceGrade = (float) $this->attendance_percentage;
        $progressGrade = (float) $this->progress_percent;

        $overallGrade = $this->computeOverallGrade($attendanceGrade, $progressGrade, $courseExamAttempts);

        return $this->overallToLetterGrade($overallGrade);
    }

    /** Compute overall numeric grade (0–100) from attendance, progress, and optional exam average. */
    protected function computeOverallGrade(float $attendance, float $progress, ?Collection $courseExamAttempts = null): float
    {
        if ($courseExamAttempts !== null && $courseExamAttempts->isNotEmpty()) {
            $examAvg = $courseExamAttempts->avg('percentage');
            if ($examAvg !== null) {
                return ($attendance + $progress + (float) $examAvg) / 3.0;
            }
        }

        return ($attendance + $progress) / 2.0;
    }

    /** Map overall numeric grade (0–100) to letter grade. */
    protected function overallToLetterGrade(float $overall): string
    {
        return match (true) {
            $overall >= 90 => 'A',
            $overall >= 80 => 'B',
            $overall >= 70 => 'C',
            $overall >= 60 => 'D',
            default => 'F',
        };
    }

    /** Issue a certificate for this enrollment. */
    public function issueCertificate(?string $grade = null): Certificate
    {
        if ($this->hasCertificate()) {
            return $this->certificate;
        }

        $template = $this->resolveCertificateTemplate();
        $finalGrade = $grade !== null && $grade !== '' ? $grade : $this->calculateGrade();

        $certificate = $this->certificate()->create([
            'certificate_template_id' => $template?->id,
            'issue_date' => now(),
            'grade' => $finalGrade,
            'certificate_path' => $this->generateCertificatePath(),
            'signature_hash' => $this->generateSignatureHash(),
        ]);

        if ($template) {
            app(\App\Services\Certificate\CertificatePdfService::class)->generateForCertificate($certificate);
        }

        $this->logActivity('certificate.issued', [
            'certificate_id' => $certificate->id,
        ]);

        return $certificate;
    }

    /** Calculate the grade for this enrollment (no exam data; used when issuing without modal). */
    protected function calculateGrade(): string
    {
        $overallGrade = $this->computeOverallGrade(
            (float) $this->attendance_percentage,
            (float) $this->progress_percent,
            null
        );

        return $this->overallToLetterGrade($overallGrade);
    }

    /** Generate the certificate file path. */
    protected function generateCertificatePath(): string
    {
        $filename = "certificate_{$this->id}_{$this->course_id}_" . now()->format('YmdHis') . '.pdf';

        return "certificates/{$filename}";
    }

    /** Generate a signature hash for certificate verification. */
    protected function generateSignatureHash(): string
    {
        $data = [
            'enrollment_id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'issue_date' => now()->toDateString(),
        ];

        return hash('sha256', json_encode($data) . config('app.key'));
    }

    /** Log an activity for this enrollment. */
    public function logActivity(string $event, array $properties = []): ActivityLog
    {
        return $this->activityLogs()->create([
            'event' => $event,
            'description' => $event,
            'properties' => $properties,
            'causer_type' => User::class,
            'causer_id' => \Illuminate\Support\Facades\Auth::id(),
            'created_at' => now(),
        ]);
    }

    /** Drop this enrollment. */
    public function drop(?string $reason = null): bool
    {
        return DB::transaction(function () use ($reason) {
            $updated = $this->update(['status' => EnrollmentStatusEnum::DROPPED]);

            if ($updated) {
                $this->logActivity('enrollment.dropped', [
                    'reason' => $reason,
                ]);
            }

            return $updated;
        });
    }

    /** Reactivate this enrollment. */
    public function reactivate(): bool
    {
        return DB::transaction(function () {
            $updated = $this->update(['status' => EnrollmentStatusEnum::ACTIVE]);

            if ($updated) {
                $this->logActivity('enrollment.reactivated');
            }

            return $updated;
        });
    }

    /** Scope for active enrollments. */
    public function scopeActive($query)
    {
        return $query->where('status', EnrollmentStatusEnum::ACTIVE);
    }

    /** Scope for completed enrollments. */
    public function scopeCompleted($query)
    {
        return $query->where('progress_percent', '>=', 100.0);
    }

    /** Scope for enrollments with certificates. */
    public function scopeWithCertificates($query)
    {
        return $query->whereHas('certificate');
    }

    /** Scope for enrollments by user. */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /** Scope for enrollments by course. */
    public function scopeByCourse($query, int $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /** Scope for enrollments by status. */
    public function scopeByStatus($query, EnrollmentStatusEnum $status)
    {
        return $query->where('status', $status);
    }

    /** Scope for enrollments with minimum progress. */
    public function scopeWithMinimumProgress($query, float $progress)
    {
        return $query->where('progress_percent', '>=', $progress);
    }

    /** Scope for recent enrollments. */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('enrolled_at', '>=', now()->subDays($days));
    }

    public function order(): Order
    {
        return $this->orderItem->order;
    }
}
