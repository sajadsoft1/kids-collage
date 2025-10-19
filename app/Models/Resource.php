<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ResourceType;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Resource Model
 *
 * Educational material (PDF, Video, Image, Link) that can be attached
 * to any resourceable entity (CourseTemplate, SessionTemplate, Session).
 *
 * @property int                                               $id
 * @property string                                            $resourceable_type
 * @property int                                               $resourceable_id
 * @property ResourceType                                      $type
 * @property string                                            $path
 * @property string                                            $title
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 * @property bool                                              $is_public
 * @property \Carbon\Carbon|null                               $created_at
 * @property \Carbon\Carbon|null                               $updated_at
 * @property \Carbon\Carbon|null                               $deleted_at
 *
 * @property-read Model $resourceable
 */
class Resource extends Model implements HasMedia
{
    use HasFactory;
    use HasSchemalessAttributes;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'resourceable_type',
        'resourceable_id',
        'type',
        'path',
        'title',
        'extra_attributes',
        'is_public',
    ];

    protected $casts = [
        'type'      => ResourceType::class,
        'is_public' => 'boolean',
    ];

    /** Model Configuration -------------------------------------------------------------------------- */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file');
    }

    /** Get the parent resourceable model. */
    public function resourceable(): MorphTo
    {
        return $this->morphTo();
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
            'resource'  => $this->id,
            'expires'   => now()->addMinutes($expirationMinutes)->timestamp,
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

    /** Scope for resources attached to a specific model. */
    public function scopeForModel($query, string $modelType, int $modelId)
    {
        return $query->where('resourceable_type', $modelType)
            ->where('resourceable_id', $modelId);
    }
}
