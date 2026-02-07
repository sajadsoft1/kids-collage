<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * CertificateTemplate Model
 *
 * Defines reusable certificate designs with placeholders for student name,
 * course title, date, grade, etc. Used when issuing a certificate for an enrollment.
 *
 * @property int                 $id
 * @property string              $title
 * @property string              $slug
 * @property bool                $is_default
 * @property string              $layout
 * @property string|null         $header_text
 * @property string|null         $body_text
 * @property string|null         $footer_text
 * @property string|null         $institute_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Certificate> $certificates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseTemplate> $courseTemplates
 */
class CertificateTemplate extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public const LAYOUT_CLASSIC = 'classic';

    public const LAYOUT_MINIMAL = 'minimal';

    public const LAYOUT_CUSTOM = 'custom';

    protected $fillable = [
        'title',
        'slug',
        'is_default',
        'layout',
        'header_text',
        'body_text',
        'footer_text',
        'institute_name',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /** Placeholders supported in template text. */
    public static function placeholders(): array
    {
        return [
            'student_name',
            'course_title',
            'course_level',
            'issue_date',
            'grade',
            'certificate_number',
            'duration',
            'institute_name',
        ];
    }

    /** Get layout options for admin. */
    public static function layoutOptions(): array
    {
        return [
            self::LAYOUT_CLASSIC => __('certificateTemplate.layout.classic'),
            self::LAYOUT_MINIMAL => __('certificateTemplate.layout.minimal'),
            self::LAYOUT_CUSTOM => __('certificateTemplate.layout.custom'),
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_100_SQUARE)->fit(Fit::Crop, 100, 100);
                $this->addMediaConversion(Constants::RESOLUTION_512_SQUARE)->fit(Fit::Crop, 512, 512);
            });

        $this->addMediaCollection('background')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_854_480)->fit(Fit::Crop, 854, 480);
                $this->addMediaConversion(Constants::RESOLUTION_1280_720)->fit(Fit::Crop, 1280, 720);
            });

        $this->addMediaCollection('signature')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion(Constants::RESOLUTION_400_300)->fit(Fit::Crop, 400, 300);
            });
    }

    /** Certificates issued with this template. */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'certificate_template_id');
    }

    /** Course templates that use this certificate template. */
    public function courseTemplates(): HasMany
    {
        return $this->hasMany(CourseTemplate::class, 'certificate_template_id');
    }

    /** Boot: generate slug from title if empty. */
    protected static function booted(): void
    {
        static::creating(function (CertificateTemplate $model) {
            if (empty($model->slug) && ! empty($model->title)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    /** Scope: default template. */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
