<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * ActivityLog Model
 * 
 * Tracks user activities and events throughout the system.
 * Used for progress tracking, auditing, and analytics.
 *
 * @property int                 $id
 * @property string|null         $log_name
 * @property string              $description
 * @property string|null         $subject_type
 * @property int|null            $subject_id
 * @property string|null         $causer_type
 * @property int|null            $causer_id
 * @property string|null         $event
 * @property array|null          $properties
 * @property string|null         $batch_uuid
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Model|null $subject
 * @property-read Model|null $causer
 * @property-read string $color
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\Enrollment|null $enrollment
 * @property-read string $formatted_description
 * @property-read string $icon
 * @property-read \App\Models\CourseSession|null $session
 * @property-read string $time_ago
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byBatch(string $batchUuid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byCauser(string $causerType, int $causerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byEvent(string $event)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog byLogName(string $logName)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog bySubject(string $subjectType, int $subjectId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog courseRelated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog enrollmentRelated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog progressRelated()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog recent(int $days = 7)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * Attendance Model
 * 
 * Tracks student presence in a session (or progress in self-paced).
 * Records attendance data for both scheduled and self-paced courses.
 *
 * @property int                 $id
 * @property int                 $enrollment_id
 * @property int                 $session_id
 * @property bool                $present
 * @property \Carbon\Carbon|null $arrival_time
 * @property \Carbon\Carbon|null $leave_time
 * @property string|null         $excuse_note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Enrollment     $enrollment
 * @property-read CourseSession  $session
 * @property int $course_session_id
 * @property-read int|null $duration
 * @property-read int $early_departure_minutes
 * @property-read string|null $formatted_duration
 * @property-read int $lateness_minutes
 * @property-read int $quality_score
 * @property-read string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance absent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance byDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance byEnrollment(int $enrollmentId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance bySession(int $sessionId)
 * @method static \Database\Factories\AttendanceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance late()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance present()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance thisWeek()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereArrivalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCourseSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereEnrollmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereExcuseNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereLeaveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance wherePresent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance withExcuse()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance withoutExcuse()
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\BannerSizeEnum $size
 * @property string|null $link
 * @property \App\Enums\BooleanEnum $published
 * @property int $click
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner draft()
 * @method static \Database\Factories\BannerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereClick($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereUpdatedAt($value)
 */
	class Banner extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $slug
 * @property \App\Enums\BooleanEnum $published
 * @property int $view_count
 * @property int $comment_count
 * @property int $wish_count
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WishList> $wishes
 * @property-read int|null $wishes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog draft()
 * @method static \Database\Factories\BlogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereWishCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class Blog extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Class Board
 *
 * @property int                 $id
 * @property string              $name
 * @property string|null         $description
 * @property string              $color
 * @property bool                $is_active
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|Column[] $columns
 * @property-read \Illuminate\Database\Eloquent\Collection|Card[] $cards
 * @property-read \Illuminate\Database\Eloquent\Collection|CardFlow[] $cardFlows
 * @property bool $system_protected
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read int|null $card_flows_count
 * @property-read int|null $cards_count
 * @property-read int|null $columns_count
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereSystemProtected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board withoutTrashed()
 */
	class Board extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property string $slug
 * @property \App\Enums\BooleanEnum $published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $user_id
 * @property int $category_id
 * @property int $view_count
 * @property int $comment_count
 * @property int $wish_count
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WishList> $wishes
 * @property-read int|null $wishes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin draft()
 * @method static \Database\Factories\BulletinFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin whereWishCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bulletin withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class Bulletin extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Class Card
 *
 * @property int                 $id
 * @property int                 $board_id
 * @property int                 $column_id
 * @property string              $title
 * @property string|null         $description
 * @property CardTypeEnum        $card_type
 * @property PriorityEnum        $priority
 * @property CardStatusEnum      $status
 * @property \Carbon\Carbon|null $due_date
 * @property int                 $order
 * @property array|null          $extra_attributes
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Board $board
 * @property-read Column $column
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|CardHistory[] $history
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignees
 * @property-read int|null $assignees_count
 * @property mixed|null $extra
 * @property-read int|null $history_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $reviewers
 * @property-read int|null $reviewers_count
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $watchers
 * @property-read int|null $watchers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card withoutTrashed()
 */
	class Card extends \Eloquent {}
}

namespace App\Models{
/**
 * Class CardFlow
 *
 * @property int                 $id
 * @property int                 $board_id
 * @property int                 $from_column_id
 * @property int                 $to_column_id
 * @property string              $name
 * @property string|null         $description
 * @property bool                $is_active
 * @property array|null          $condition_json
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Board $board
 * @property-read Column $fromColumn
 * @property-read Column $toColumn
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereFromColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereToColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow withoutTrashed()
 */
	class CardFlow extends \Eloquent {}
}

namespace App\Models{
/**
 * Class CardHistory
 *
 * @property int            $id
 * @property int            $card_id
 * @property int            $user_id
 * @property int            $column_id
 * @property string         $action
 * @property string|null    $description
 * @property array|null     $extra_attributes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Card $card
 * @property-read User $user
 * @property-read Column $column
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property mixed|null $extra
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereColumnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardHistory withExtraAttributes()
 */
	class CardHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $body
 * @property int $id
 * @property array<array-key, mixed>|null $languages
 * @property string $slug
 * @property int|null $parent_id
 * @property \App\Enums\CategoryTypeEnum $type
 * @property \App\Enums\BooleanEnum $published
 * @property int $ordering
 * @property int $view_count
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Blog> $blogs
 * @property-read int|null $blogs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Faq> $faqs
 * @property-read int|null $faqs_count
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PortFolio> $portfolios
 * @property-read int|null $portfolios_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Slider> $sliders
 * @property-read int|null $sliders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category active()
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereOrdering($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withoutTrashed()
 */
	class Category extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Certificate Model
 * 
 * Issued on course completion to certify student achievement.
 * Includes verification features and grade tracking.
 *
 * @property int                 $id
 * @property int                 $enrollment_id
 * @property \Carbon\Carbon      $issue_date
 * @property string              $grade
 * @property string              $certificate_path
 * @property string              $signature_hash
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Enrollment $enrollment
 * @property-read int $age_in_days
 * @property-read float $attendance_percentage
 * @property-read string $certificate_number
 * @property-read mixed $course
 * @property-read string $course_duration
 * @property-read string|null $course_level
 * @property-read mixed $course_template
 * @property-read string $course_title
 * @property-read string $download_url
 * @property-read int|null $file_size
 * @property-read string|null $formatted_file_size
 * @property-read string $formatted_issue_date
 * @property-read string $grade_color
 * @property-read string $grade_description
 * @property-read float $progress_percentage
 * @property-read mixed $student
 * @property-read string $student_name
 * @property-read string $verification_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate byCourse(int $courseId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate byEnrollment(int $enrollmentId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate byGrade(string $grade)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate byStudent(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate issuedBetween(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate recent(int $days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCertificatePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereEnrollmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereSignatureHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereUpdatedAt($value)
 */
	class Certificate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\BooleanEnum $published
 * @property array<array-key, mixed>|null $languages
 * @property string|null $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 */
	class Client extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Class Column
 *
 * @property int                 $id
 * @property int                 $board_id
 * @property string              $name
 * @property string|null         $description
 * @property string              $color
 * @property int                 $order
 * @property int|null            $wip_limit
 * @property bool                $is_active
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Board $board
 * @property-read \Illuminate\Database\Eloquent\Collection|Card[] $cards
 * @property-read \Illuminate\Database\Eloquent\Collection|CardFlow[] $fromFlows
 * @property-read \Illuminate\Database\Eloquent\Collection|CardFlow[] $toFlows
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read int|null $cards_count
 * @property-read int|null $from_flows_count
 * @property-read int|null $to_flows_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column whereWipLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column withoutTrashed()
 */
	class Column extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int $user_id
 * @property int|null $admin_id
 * @property int|null $parent_id
 * @property string $morphable_type
 * @property int $morphable_id
 * @property string $comment
 * @property int|null $rate 1 to 5
 * @property string|null $admin_note
 * @property \App\Enums\YesNoEnum $suggest
 * @property \App\Enums\BooleanEnum $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Http\Resources\Json\JsonResource|null $morph_resource
 * @property-read string $morph_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $morphable
 * @property-read Comment|null $parent
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereMorphableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereMorphableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereSuggest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\YesNoEnum $follow_up
 * @property string $name
 * @property string $email
 * @property string|null $mobile
 * @property string $comment
 * @property string|null $admin_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ContactUsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereFollowUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereUpdatedAt($value)
 */
	class ContactUs extends \Eloquent {}
}

namespace App\Models{
/**
 * Course Model
 * 
 * A real execution of a CourseTemplate in a Term with a teacher.
 * This represents an actual course instance that students can enroll in.
 *
 * @property int                 $id
 * @property int                 $course_template_id
 * @property int                 $term_id
 * @property int                 $teacher_id
 * @property int|null            $capacity
 * @property float               $price
 * @property CourseTypeEnum      $type
 * @property CourseStatusEnum    $status
 * @property array|null          $days_of_week
 * @property \Carbon\Carbon|null $start_time
 * @property \Carbon\Carbon|null $end_time
 * @property int|null            $room_id
 * @property string|null         $meeting_link
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read CourseTemplate $template
 * @property-read Term                                                         $term
 * @property-read User                                                         $teacher
 * @property-read Room|null                                                    $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSession> $sessions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Enrollment>    $enrollments
 * @property-read OrderItem|null                                               $orderItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Enrollment> $activeEnrollments
 * @property-read int|null $active_enrollments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseSession> $completedSessions
 * @property-read int|null $completed_sessions_count
 * @property-read int|null $enrollments_count
 * @property-read int $available_spots
 * @property-read string $days_of_week_readable
 * @property-read int $enrollment_count
 * @property-read string $formatted_price
 * @property-read string $formatted_session_duration
 * @property-read int $session_duration
 * @property-read int|null $sessions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course byPriceRange(float $minPrice, float $maxPrice)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course byTeacher(int $teacherId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course byTerm(int $termId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course byType(\App\Enums\CourseTypeEnum $type)
 * @method static \Database\Factories\CourseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course instructorLed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course selfPaced()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCourseTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withAvailableSpots()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course withoutTrashed()
 */
	class Course extends \Eloquent {}
}

namespace App\Models{
/**
 * Session Model
 * 
 * A scheduled session for a Course (instance of a SessionTemplate).
 * This represents an actual class session that students attend.
 *
 * @property int                                                                      $id
 * @property int                                                                      $course_id
 * @property int                                                                      $session_template_id
 * @property \Carbon\Carbon|null                                                      $date
 * @property \Carbon\Carbon|null                                                      $start_time
 * @property \Carbon\Carbon|null                                                      $end_time
 * @property int|null                                                                 $room_id
 * @property string|null                                                              $meeting_link
 * @property string|null                                                              $recording_link
 * @property SessionStatus                                                            $status
 * @property SessionType                                                              $session_type
 * @property \Carbon\Carbon|null                                                      $created_at
 * @property \Carbon\Carbon|null                                                      $updated_at
 * @property \Carbon\Carbon|null                                                      $deleted_at
 * @property-read Course                                                              $course
 * @property-read CourseSessionTemplate                                               $sessionTemplate
 * @property-read Room|null                                                           $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance>           $attendances
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property int $course_session_template_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $absentAttendances
 * @property-read int|null $absent_attendances_count
 * @property-read int|null $attendances_count
 * @property-read int $absent_count
 * @property-read int $attendance_count
 * @property-read float $attendance_percentage
 * @property-read int $duration
 * @property-read string $formatted_duration
 * @property-read string $full_title
 * @property-read string $location
 * @property-read int $present_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $presentAttendances
 * @property-read int|null $present_attendances_count
 * @property-read int|null $resources_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession byDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession byRoom(int $roomId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession byType(\App\Enums\SessionType $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession completed()
 * @method static \Database\Factories\CourseSessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession hybrid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession inPerson()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession online()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession planned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession thisWeek()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereCourseSessionTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereMeetingLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereRecordingLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereSessionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession withRecordings()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession withoutTrashed()
 */
	class CourseSession extends \Eloquent {}
}

namespace App\Models{
/**
 * SessionTemplate Model
 * 
 * Represents a lesson inside a course template (e.g., Lesson 1: Alphabet).
 * This defines the structure and content of individual sessions that will
 * be instantiated for specific course runs.
 *
 * @property int         $id
 * @property int         $course_template_id
 * @property int         $order
 * @property string      $title
 * @property string      $description
 * @property int         $duration_minutes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read CourseTemplate                 $courseTemplate
 * @property-read Collection<int, CourseSession> $sessions
 * @property-read Collection<int, resource>      $resources
 * @property \App\Enums\SessionType $type
 * @property array<array-key, mixed>|null $languages
 * @property-read float $duration_hours
 * @property-read string $formatted_duration
 * @property-read int|null $resources_count
 * @property-read int|null $sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate byDurationRange(int $minMinutes, int $maxMinutes)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate longerThan(int $minutes)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereCourseTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate withoutTrashed()
 */
	class CourseSessionTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * CourseTemplate Model
 * 
 * Represents the definition of a course (e.g., English Level 1).
 * This is the template that defines the structure and content of a course,
 * which can be instantiated multiple times in different terms.
 *
 * @property int         $id
 * @property string      $title
 * @property string      $description
 * @property int|null    $category_id
 * @property string|null $level
 * @property array|null  $prerequisites
 * @property bool        $is_self_paced
 * @property array|null  $languages
 * @property array|null  $syllabus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, CourseSessionTemplate> $sessionTemplates
 * @property-read Collection<int, Course>                $courses
 * @property-read Collection<int, resource>              $resources
 * @property string $slug
 * @property \App\Enums\CourseTypeEnum $type
 * @property int $view_count
 * @property int $comment_count
 * @property int $wish_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $activeCourses
 * @property-read int|null $active_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read int|null $courses_count
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read int $session_count
 * @property-read int $total_duration
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read int|null $resources_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property-read int|null $session_templates_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WishList> $wishes
 * @property-read int|null $wishes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate byCategory(int $categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate byLevel(string $level)
 * @method static \Database\Factories\CourseTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate instructorLed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate selfPaced()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereIsSelfPaced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate wherePrerequisites($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate whereWishCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withoutTrashed()
 */
	class CourseTemplate extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Enrollment Model
 * 
 * Student registration in a course instance.
 * Tracks student progress, attendance, and completion status.
 *
 * @property int                                                            $id
 * @property int                                                            $user_id
 * @property int                                                            $course_id
 * @property int|null                                                       $order_item_id
 * @property EnrollmentStatusEnum                                           $status
 * @property \Carbon\Carbon                                                 $enrolled_at
 * @property float                                                          $progress_percent
 * @property \Carbon\Carbon|null                                            $created_at
 * @property \Carbon\Carbon|null                                            $updated_at
 * @property-read User                                                      $user
 * @property-read Course                                                    $course
 * @property-read OrderItem|null                                            $orderItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance> $attendances
 * @property-read Certificate|null                                          $certificate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ActivityLog> $activityLogs
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $absentAttendances
 * @property-read int|null $absent_attendances_count
 * @property-read int|null $activity_logs_count
 * @property-read int|null $attendances_count
 * @property-read float $attendance_percentage
 * @property-read int $days_since_enrollment
 * @property-read \Carbon\Carbon|null $estimated_completion_date
 * @property-read int $present_attendance_count
 * @property-read int $total_attendance_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $presentAttendances
 * @property-read int|null $present_attendances_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment byCourse(int $courseId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment byStatus(\App\Enums\EnrollmentStatusEnum $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment byUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment completed()
 * @method static \Database\Factories\EnrollmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment recent(int $days = 30)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereEnrolledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereProgressPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment withCertificates()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment withMinimumProgress(float $progress)
 */
	class Enrollment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property array<array-key, mixed>|null $languages
 * @property int|null $category_id
 * @property int $ordering
 * @property int $like_count
 * @property int $view_count
 * @property \App\Enums\YesNoEnum $favorite
 * @property \App\Enums\YesNoEnum $deletable
 * @property \App\Enums\BooleanEnum $published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq draft()
 * @method static \Database\Factories\FaqFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereDeletable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereFavorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereLikeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereOrdering($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereViewCount($value)
 */
	class Faq extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $payment_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon $due_date
 * @property \App\Enums\InstallmentMethodEnum $method
 * @property \App\Enums\InstallmentStatusEnum $status
 * @property string|null $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Payment $payment
 * @method static \Database\Factories\InstallmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Installment whereUpdatedAt($value)
 */
	class Installment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property string $slug
 * @property int $view_count
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @method static \Database\Factories\LicenseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereViewCount($value)
 */
	class License extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property \App\Enums\BooleanEnum $published
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Database\Factories\NotificationTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate query()
 */
	class NotificationTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\BooleanEnum $published
 * @property string $user_name
 * @property string|null $company
 * @property string $comment
 * @property int $ordering
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $view_count
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion draft()
 * @method static \Database\Factories\OpinionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereOrdering($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Opinion withoutTrashed()
 */
	class Opinion extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property float $total_amount
 * @property \App\Enums\OrderStatusEnum $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * OrderItem Model
 * 
 * Payment link for course enrollments and other purchasable items.
 * Supports polymorphic relationships to link to different types of purchasable entities.
 *
 * @property int                 $id
 * @property int                 $order_id
 * @property string              $itemable_type
 * @property int                 $itemable_id
 * @property float               $price
 * @property int                 $quantity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Order $order
 * @property-read Model $itemable
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\Enrollment|null $enrollment
 * @property-read string $formatted_price
 * @property-read string $formatted_total_price
 * @property-read string $item_description
 * @property-read string $item_title
 * @property-read string $item_type
 * @property-read string $refund_policy
 * @property-read float $total_price
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem byOrder(int $orderId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem byPriceRange(float $minPrice, float $maxPrice)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem courseEnrollments()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem courses()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereItemableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereItemableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem withMinimumQuantity(int $quantity)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\PageTypeEnum $type
 * @property string $slug
 * @property array<array-key, mixed>|null $extra_attributes
 * @property int $view_count
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\PageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereViewCount($value)
 */
	class Page extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property float $amount
 * @property \App\Enums\PaymentTypeEnum $type
 * @property \App\Enums\PaymentStatusEnum $status
 * @property string|null $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Installment> $installment
 * @property-read int|null $installment_count
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon $execution_date
 * @property int $category_id
 * @property int $creator_id
 * @property \App\Enums\BooleanEnum $published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $view_count
 * @property int $comment_count
 * @property int $like_count
 * @property int $wish_count
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\User $creator
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio draft()
 * @method static \Database\Factories\PortFolioFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereExecutionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereLikeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio whereWishCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PortFolio withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class PortFolio extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $national_code
 * @property string|null $birth_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\GenderEnum $gender
 * @property \App\Enums\ReligionEnum $religion
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * Resource Model
 * 
 * Educational material (PDF, Video, Image, Link) that can be attached
 * to any resourceable entity (CourseTemplate, SessionTemplate, Session).
 *
 * @property int                 $id
 * @property string              $resourceable_type
 * @property int                 $resourceable_id
 * @property ResourceType        $type
 * @property string              $path
 * @property string              $title
 * @property array|null          $metadata
 * @property bool                $is_public
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Model $resourceable
 * @property-read int|null $duration
 * @property-read int|null $file_size
 * @property-read string|null $formatted_file_size
 * @property-read string|null $mime_type
 * @property-read string|null $thumbnail_path
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource byType(\App\Enums\ResourceType $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource documents()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource forModel(string $modelType, int $modelId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource media()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource private()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource withoutTrashed()
 */
	class Resource extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property array<array-key, mixed>|null $languages
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * Room Model
 * 
 * Physical location for in-person/hybrid courses.
 * Represents classrooms, labs, or other physical spaces where courses can be held.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property int                                                               $capacity
 * @property string|null                                                       $location
 * @property array|null                                                        $languages
 * @property \Carbon\Carbon|null                                               $created_at
 * @property \Carbon\Carbon|null                                               $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course>        $courses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSession> $sessions
 * @property-read int|null $courses_count
 * @property-read string $full_location
 * @property-read int|null $sessions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room byCapacityRange(int $minCapacity, int $maxCapacity)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room byLocation(string $location)
 * @method static \Database\Factories\RoomFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room multilingual()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room supportingLanguage(string $language)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room withMinimumCapacity(int $capacity)
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property int $id
 * @property string $morphable_type
 * @property int $morphable_id
 * @property string|null $description
 * @property string|null $canonical
 * @property string|null $old_url
 * @property string|null $redirect_to
 * @property \App\Enums\SeoRobotsMetaEnum $robots_meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Http\Resources\Json\JsonResource|null $morph_resource
 * @property-read string $morph_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $morphable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereMorphableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereMorphableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereOldUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereRedirectTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereRobotsMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereUpdatedAt($value)
 */
	class SeoOption extends \Eloquent {}
}

namespace App\Models{
/**
 * @property mixed $extra_attributes
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed> $permissions
 * @property bool|null $show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string $title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withExtraAttributes()
 */
	class Setting extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property array<array-key, mixed>|null $languages
 * @property \App\Enums\SliderPositionEnum $position
 * @property string|null $link
 * @property \App\Enums\BooleanEnum $published
 * @property \App\Enums\YesNoEnum $has_timer
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $timer_start
 * @property int $ordering
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SliderReference> $references
 * @property-read int|null $references_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider draft()
 * @method static \Database\Factories\SliderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereHasTimer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereOrdering($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereTimerStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider withExtraAttributes()
 */
	class Slider extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $slider_id
 * @property string $morphable_type
 * @property int $morphable_id
 * @property-read \Illuminate\Http\Resources\Json\JsonResource|null $morph_resource
 * @property-read string $morph_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $morphable
 * @property-read \App\Models\Slider $slider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SliderReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SliderReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SliderReference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SliderReference whereMorphableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SliderReference whereMorphableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SliderReference whereSliderId($value)
 */
	class SliderReference extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property \App\Enums\BooleanEnum $published
 * @property \App\Enums\SmsSendStatusEnum $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\SmsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms search($keyword)
 */
	class Sms extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property string|null $languages
 * @property string $link
 * @property int $ordering
 * @property \App\Enums\BooleanEnum $published
 * @property \App\Enums\SocialMediaPositionEnum $position
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\SocialMediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereOrdering($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialMedia whereUpdatedAt($value)
 */
	class SocialMedia extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property string $body
 * @property string $description
 * @property int $id
 * @property array<array-key, mixed> $name
 * @property array<array-key, mixed> $slug
 * @property array<array-key, mixed>|null $languages
 * @property string|null $type
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $customTranslations
 * @property-read int|null $custom_translations_count
 * @property-read mixed $seo_canonical
 * @property-read mixed $seo_description
 * @property-read mixed $seo_redirect_to
 * @property-read mixed $seo_robot_meta
 * @property-read mixed $seo_title
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\SeoOption|null $seoOption
 * @property-read mixed $translations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag containing(string $name, $locale = null)
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag withType(?string $type = null)
 */
	class Tag extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\BooleanEnum $published
 * @property array<array-key, mixed>|null $languages
 * @property string $position
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\TeammateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Teammate withExtraAttributes()
 */
	class Teammate extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * Term Model
 * 
 * Represents an academic period (Fall 2025, Spring 2026, or long-term for self-paced).
 * Terms define the time boundaries for course runs and help organize
 * academic scheduling.
 *
 * @property int                 $id
 * @property string              $title
 * @property string              $description
 * @property \Carbon\Carbon      $start_date
 * @property \Carbon\Carbon      $end_date
 * @property TermStatus          $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course> $courses
 * @property array<array-key, mixed>|null $languages
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $activeCourses
 * @property-read int|null $active_courses_count
 * @property-read int|null $courses_count
 * @property-read string $academic_year
 * @property-read int $days_remaining
 * @property-read int $duration_days
 * @property-read int $duration_weeks
 * @property-read float $progress_percentage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term byAcademicYear(string $academicYear)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term current()
 * @method static \Database\Factories\TermFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term future()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term longTerm()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term past()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Term whereUpdatedAt($value)
 */
	class Term extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $subject
 * @property \App\Enums\TicketDepartmentEnum $department
 * @property int $user_id
 * @property int|null $closed_by
 * @property \App\Enums\TicketStatusEnum $status
 * @property \App\Enums\TicketPriorityEnum $priority
 * @property string $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $closeBy
 * @property-read int $unread_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUserId($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $message
 * @property int|null $read_by
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User|null $readBy
 * @property-read \App\Models\Ticket $ticket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereReadBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketMessage whereUserId($value)
 */
	class TicketMessage extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $translatable_type
 * @property int $translatable_id
 * @property string $key
 * @property string $value
 * @property string $locale
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $translatable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereTranslatableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereTranslatableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Translation whereValue($value)
 */
	class Translation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $family
 * @property string|null $email
 * @property string|null $mobile
 * @property int|null $gender
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $mobile_verified_at
 * @property string $password
 * @property \App\Enums\BooleanEnum $status
 * @property \App\Enums\UserTypeEnum $type
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $assignedCards
 * @property-read int|null $assigned_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Blog> $blogs
 * @property-read int|null $blogs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Board> $boards
 * @property-read int|null $boards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CardHistory> $cardHistory
 * @property-read int|null $card_history_count
 * @property-read mixed $full_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $reviewingCards
 * @property-read int|null $reviewing_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseSession> $taughtClasses
 * @property-read int|null $taught_classes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $watchingCards
 * @property-read int|null $watching_cards_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobileVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $morphable_type
 * @property int $morphable_id
 * @property string|null $ip
 * @property string|null $collection
 * @property string|null $visitor
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Http\Resources\Json\JsonResource|null $morph_resource
 * @property-read string $morph_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $morphable
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereMorphableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereMorphableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserView whereVisitor($value)
 */
	class UserView extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $morphable_type
 * @property int $morphable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Media|null $image
 * @property-read \Illuminate\Http\Resources\Json\JsonResource|null $morph_resource
 * @property-read string $morph_type
 * @property-read string $title
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $morphable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList whereMorphableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList whereMorphableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WishList whereUserId($value)
 */
	class WishList extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|c newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|c newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|c query()
 */
	class c extends \Eloquent {}
}

