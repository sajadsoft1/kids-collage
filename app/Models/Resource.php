<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ResourceType;
use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Resource Model
 *
 * Educational material (PDF, Video, Image, Link) that can be attached
 * to multiple CourseSessionTemplates via pivot table.
 *
 * @property int                                               $id
 * @property ResourceType                                      $type
 * @property string                                            $path
 * @property string                                            $title
 * @property int                                               $order
 * @property string|null                                       $description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 * @property bool                                              $is_public
 * @property \Carbon\Carbon|null                               $created_at
 * @property \Carbon\Carbon|null                               $updated_at
 * @property \Carbon\Carbon|null                               $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSessionTemplate> $courseSessionTemplates
 */
class Resource extends Model implements HasMedia
{
    use HasBranch;
    use HasBranchScope;
    use HasFactory;
    use HasSchemalessAttributes;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'path',
        'title',
        'order',
        'description',
        'extra_attributes',
        'is_public',
        'branch_id',
    ];

    protected $casts = [
        'type' => ResourceType::class,
        'is_public' => 'bool',
        'order' => 'integer',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('video')->singleFile();
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('pdf')->singleFile();
        $this->addMediaCollection('audio')->singleFile();
        $this->addMediaCollection('file')->singleFile();
    }

    /** Get the course session templates this resource is attached to. */
    public function courseSessionTemplates(): BelongsToMany
    {
        return $this->belongsToMany(CourseSessionTemplate::class, 'course_session_template_resource')
            ->withTimestamps();
    }

    /** Get the file size from extra_attributes. */
    public function getFileSizeAttribute(): ?int
    {
        return $this->extra_attributes->get('file_size', null);
    }

    /** Get the file size in human readable format. */
    public function getFormattedFileSizeAttribute(): ?string
    {
        $size = $this->file_size;

        if ( ! $size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /** Get the MIME type from extra_attributes. */
    public function getMimeTypeAttribute(): ?string
    {
        return $this->extra_attributes->get('mime_type', null);
    }

    /** Get the duration for video resources. */
    public function getDurationAttribute(): ?int
    {
        return $this->type === ResourceType::VIDEO
            ? ($this->extra_attributes->get('duration', null))
            : null;
    }

    /** Get the thumbnail path for video/image resources. */
    public function getThumbnailPathAttribute(): ?string
    {
        return $this->extra_attributes->get('thumbnail_path', null);
    }

    /** Check if this resource requires authentication. */
    public function requiresAuth(): bool
    {
        return ! $this->is_public;
    }

    /** Check if this resource requires signed URL. */
    public function requiresSignedUrl(): bool
    {
        return $this->type->requiresSignedUrl();
    }

    /** Generate a signed URL for secure access. */
    public function getSignedUrl(int $expirationMinutes = 60): string
    {
        if ( ! $this->requiresSignedUrl()) {
            return $this->path;
        }

        // Implementation would depend on your storage driver
        // This is a placeholder for the actual signed URL generation
        return route('resources.signed', [
            'resource' => $this->id,
            'expires' => now()->addMinutes($expirationMinutes)->timestamp,
            'signature' => $this->generateSignature($expirationMinutes),
        ]);
    }

    /** Generate a signature for the resource. */
    protected function generateSignature(int $expirationMinutes): string
    {
        // This is a placeholder implementation
        // In production, use proper cryptographic signing
        return hash('sha256', $this->id . $this->path . now()->addMinutes($expirationMinutes)->timestamp);
    }

    /** Scope for public resources. */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /** Scope for private resources. */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /** Scope for resources by type. */
    public function scopeByType($query, ResourceType $type)
    {
        return $query->where('type', $type);
    }

    /** Scope for media resources (videos and images). */
    public function scopeMedia($query)
    {
        return $query->whereIn('type', [ResourceType::VIDEO, ResourceType::IMAGE]);
    }

    /** Scope for document resources. */
    public function scopeDocuments($query)
    {
        return $query->whereIn('type', [ResourceType::PDF, ResourceType::LINK]);
    }

    /** Scope for resources attached to a specific course session template. */
    public function scopeForCourseSessionTemplate($query, int $courseSessionTemplateId)
    {
        return $query->whereHas('courseSessionTemplates', function ($q) use ($courseSessionTemplateId) {
            $q->where('course_session_template_id', $courseSessionTemplateId);
        });
    }

    /** Scope for ordered resources. */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /** Check if this resource is an uploaded file. */
    public function isUploadedFile(): bool
    {
        return $this->type !== ResourceType::LINK;
    }

    /** Check if this resource is an external link. */
    public function isExternalLink(): bool
    {
        return $this->type === ResourceType::LINK;
    }

    /** Get the URL for this resource. */
    public function getUrlAttribute(): string
    {
        if ($this->isExternalLink()) {
            return $this->path;
        }

        // For uploaded files, return the media URL
        $media = $this->getFirstMedia($this->type->value);

        return $media ? $media->getUrl() : $this->path;
    }
}
