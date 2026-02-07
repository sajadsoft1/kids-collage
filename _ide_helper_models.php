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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
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
 * @property int                 $course_session_id
 * @property BooleanEnum         $present
 * @property \Carbon\Carbon|null $arrival_time
 * @property \Carbon\Carbon|null $leave_time
 * @property string|null         $excuse_note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Enrollment     $enrollment
 * @property-read CourseSession  $session
 * @property int|null $branch_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereBranchId($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $siteComments
 * @property-read int|null $site_comments_count
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
 * @property int|null $branch_id
 * @property bool $system_protected
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read int|null $card_flows_count
 * @property-read int|null $cards_count
 * @property-read int|null $columns_count
 * @property-read int|null $users_count
 * @method static \App\Query\BranchBuilder<static>|Board forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Board newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Board newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|Board query()
 * @method static \App\Query\BranchBuilder<static>|Board whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereColor($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereIsActive($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereName($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereSystemProtected($value)
 * @method static \App\Query\BranchBuilder<static>|Board whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Board withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Board withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Board withoutTrashed()
 */
	class Board extends \Eloquent {}
}

namespace App\Models{
/**
 * Branch Model
 * 
 * Represents a branch/location in the multi-branch system.
 * Each branch can have its own data separated from other branches.
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $code
 * @property BranchStatusEnum                $status
 * @property bool                            $is_default
 * @property array|null                      $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $languages
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $defaultUsers
 * @property-read int|null $default_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch default()
 * @method static \Database\Factories\BranchFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 */
	class Branch extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $siteComments
 * @property-read int|null $site_comments_count
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
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $assignees
 * @property-read int|null $assignees_count
 * @property-read \App\Models\Branch|null $branch
 * @property mixed|null $extra
 * @property-read int|null $history_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $reviewers
 * @property-read int|null $reviewers_count
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $watchers
 * @property-read int|null $watchers_count
 * @method static \App\Query\BranchBuilder<static>|Card forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Card newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|Card query()
 * @method static \App\Query\BranchBuilder<static>|Card whereBoardId($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereCardType($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereColumnId($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereDueDate($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereOrder($value)
 * @method static \App\Query\BranchBuilder<static>|Card wherePriority($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereTitle($value)
 * @method static \App\Query\BranchBuilder<static>|Card whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Card withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Card withExtraAttributes()
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
 * @property int|null $branch_id
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @method static \App\Query\BranchBuilder<static>|CardFlow forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|CardFlow newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|CardFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CardFlow onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|CardFlow query()
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereBoardId($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereFromColumnId($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereId($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereIsActive($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereName($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereToColumnId($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CardFlow withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|CardFlow withExtraAttributes()
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
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property mixed|null $extra
 * @method static \App\Query\BranchBuilder<static>|CardHistory forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|CardHistory newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|CardHistory newQuery()
 * @method static \App\Query\BranchBuilder<static>|CardHistory query()
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereAction($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereCardId($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereColumnId($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereId($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|CardHistory withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|CardHistory withExtraAttributes()
 */
	class CardHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $body
 * @property int $id
 * @property int|null $branch_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereBranchId($value)
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
 * @property int|null            $certificate_template_id
 * @property \Carbon\Carbon      $issue_date
 * @property string              $grade
 * @property string              $certificate_path
 * @property string              $signature_hash
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Enrollment              $enrollment
 * @property-read CertificateTemplate|null $certificateTemplate
 * @property int|null $branch_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCertificatePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCertificateTemplateId($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Certificate> $certificates
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseTemplate> $courseTemplates
 * @property-read int|null $certificates_count
 * @property-read int|null $course_templates_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate default()
 * @method static \Database\Factories\CertificateTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereBodyText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereFooterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereHeaderText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereInstituteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CertificateTemplate whereUpdatedAt($value)
 */
	class CertificateTemplate extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
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
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read int|null $cards_count
 * @property-read int|null $from_flows_count
 * @property-read int|null $to_flows_count
 * @method static \App\Query\BranchBuilder<static>|Column forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Column newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Column newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Column onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|Column query()
 * @method static \App\Query\BranchBuilder<static>|Column whereBoardId($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereColor($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereIsActive($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereName($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereOrder($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Column whereWipLimit($value)
 * @method static \App\Query\BranchBuilder<static>|Column withAllBranches()
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
 * @property string $subject
 * @property string|null $email
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereSubject($value)
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
 * @property int              $id
 * @property int              $course_template_id
 * @property int              $term_id
 * @property int              $teacher_id
 * @property int|null         $capacity
 * @property float            $price
 * @property CourseTypeEnum   $type
 * @property CourseStatusEnum $status
 * @property array|null       $days_of_week
 * @property Carbon|null      $start_time
 * @property Carbon|null      $end_time
 * @property int|null         $room_id
 * @property string|null      $meeting_link
 * @property Carbon|null      $created_at
 * @property Carbon|null      $updated_at
 * @property Carbon|null      $deleted_at
 * @property-read CourseTemplate $template
 * @property-read Term                                                         $term
 * @property-read User                                                         $teacher
 * @property-read Room|null                                                    $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSession> $sessions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Enrollment>    $enrollments
 * @property-read OrderItem|null                                               $orderItem
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Enrollment> $activeEnrollments
 * @property-read int|null $active_enrollments_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseSession> $completedSessions
 * @property-read int|null $completed_sessions_count
 * @property-read \App\Models\CourseTemplate $courseTemplate
 * @property-read int|null $enrollments_count
 * @property-read int $available_spots
 * @property-read string $days_of_week_readable
 * @property-read int $enrollment_count
 * @property-read string $formatted_price
 * @property-read string $formatted_session_duration
 * @property-read int $session_duration
 * @property-read int|null $sessions_count
 * @method static \App\Query\BranchBuilder<static>|Course active()
 * @method static \App\Query\BranchBuilder<static>|Course byPriceRange(float $minPrice, float $maxPrice)
 * @method static \App\Query\BranchBuilder<static>|Course byTeacher(int $teacherId)
 * @method static \App\Query\BranchBuilder<static>|Course byTerm(int $termId)
 * @method static \App\Query\BranchBuilder<static>|Course byType(\App\Enums\CourseTypeEnum $type)
 * @method static \Database\Factories\CourseFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Course forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Course instructorLed()
 * @method static \App\Query\BranchBuilder<static>|Course newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Course onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|Course query()
 * @method static \App\Query\BranchBuilder<static>|Course selfPaced()
 * @method static \App\Query\BranchBuilder<static>|Course whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereCapacity($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereCourseTemplateId($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Course wherePrice($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereTeacherId($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereTermId($value)
 * @method static \App\Query\BranchBuilder<static>|Course whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Course withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Course withAvailableSpots()
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
 * @property int                 $id
 * @property int                 $course_id
 * @property int                 $session_template_id
 * @property \Carbon\Carbon|null $date
 * @property \Carbon\Carbon|null $start_time
 * @property \Carbon\Carbon|null $end_time
 * @property int|null            $room_id
 * @property string|null         $meeting_link
 * @property string|null         $recording_link
 * @property SessionStatus       $status
 * @property SessionType         $session_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Course                                                              $course
 * @property-read CourseSessionTemplate                                               $sessionTemplate
 * @property-read Room|null                                                           $room
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance>           $attendances
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property int|null $branch_id
 * @property int $course_session_template_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $absentAttendances
 * @property-read int|null $absent_attendances_count
 * @property-read int|null $attendances_count
 * @property-read \App\Models\Branch|null $branch
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
 * @method static \App\Query\BranchBuilder<static>|CourseSession byDateRange(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
 * @method static \App\Query\BranchBuilder<static>|CourseSession byRoom(int $roomId)
 * @method static \App\Query\BranchBuilder<static>|CourseSession byType(\App\Enums\SessionType $type)
 * @method static \App\Query\BranchBuilder<static>|CourseSession cancelled()
 * @method static \App\Query\BranchBuilder<static>|CourseSession completed()
 * @method static \Database\Factories\CourseSessionFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|CourseSession forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|CourseSession hybrid()
 * @method static \App\Query\BranchBuilder<static>|CourseSession inPerson()
 * @method static \App\Query\BranchBuilder<static>|CourseSession newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|CourseSession newQuery()
 * @method static \App\Query\BranchBuilder<static>|CourseSession online()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSession onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|CourseSession planned()
 * @method static \App\Query\BranchBuilder<static>|CourseSession query()
 * @method static \App\Query\BranchBuilder<static>|CourseSession thisWeek()
 * @method static \App\Query\BranchBuilder<static>|CourseSession today()
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereCourseId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereCourseSessionTemplateId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereDate($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereEndTime($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereMeetingLink($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereRecordingLink($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereRoomId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereSessionType($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereStartTime($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSession withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|CourseSession withRecordings()
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
 * @property int|null $branch_id
 * @property \App\Enums\SessionType $type
 * @property array<array-key, mixed>|null $languages
 * @property-read \App\Models\Branch|null $branch
 * @property-read float $duration_hours
 * @property-read string $formatted_duration
 * @property-read int|null $resources_count
 * @property-read int|null $sessions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate byDurationRange(int $minMinutes, int $maxMinutes)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate longerThan(int $minutes)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseSessionTemplate onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate ordered()
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate query()
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate search($keyword)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereCourseTemplateId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereDurationMinutes($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereLanguages($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereOrder($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereType($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseSessionTemplate withAllBranches()
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
 * @property int|null    $course_template_level_id
 * @property int|null    $certificate_template_id
 * @property array|null  $prerequisites
 * @property bool        $is_self_paced
 * @property array|null  $languages
 * @property array|null  $syllabus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read CourseTemplateLevel|null               $level
 * @property-read CertificateTemplate|null               $certificateTemplate
 * @property-read Collection<int, CourseSessionTemplate> $sessionTemplates
 * @property-read Collection<int, Course>                $courses
 * @property-read Collection<int, resource>              $resources
 * @property int|null $branch_id
 * @property string $slug
 * @property \App\Enums\CourseTypeEnum $type
 * @property int $view_count
 * @property int $comment_count
 * @property int $wish_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $activeCourses
 * @property-read int|null $active_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $siteComments
 * @property-read int|null $site_comments_count
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WishList> $wishes
 * @property-read int|null $wishes_count
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate byCategory(int $categoryId)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate byLevel(int $levelId)
 * @method static \Database\Factories\CourseTemplateFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate instructorLed()
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate query()
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate search($keyword)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate selfPaced()
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereCategoryId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereCertificateTemplateId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereCommentCount($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereCourseTemplateLevelId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereId($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereIsSelfPaced($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereLanguages($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate wherePrerequisites($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereSlug($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereType($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereViewCount($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate whereWishCount($value)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withAllTagsOfAnyType($tags)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withAnyTagsOfAnyType($tags)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withTrashed(bool $withTrashed = true)
 * @method static \App\Query\BranchBuilder<static>|CourseTemplate withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplate withoutTrashed()
 */
	class CourseTemplate extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\BooleanEnum $published
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseTemplate> $courseTemplates
 * @property-read int|null $course_templates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\CourseTemplateLevelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CourseTemplateLevel whereUpdatedAt($value)
 */
	class CourseTemplateLevel extends \Eloquent {}
}

namespace App\Models{
/**
 * Discount Model
 * 
 * Manages discount codes with advanced features including:
 * - Percentage or fixed amount discounts
 * - User-specific or global discounts
 * - Minimum order amount requirements
 * - Usage limits (total and per user)
 * - Date-based activation/expiration
 * - Maximum discount cap for percentage types
 *
 * @property int                 $id
 * @property string              $code
 * @property string              $type
 * @property float               $value
 * @property int|null            $user_id
 * @property float               $min_order_amount
 * @property float|null          $max_discount_amount
 * @property int|null            $usage_limit
 * @property int                 $usage_per_user
 * @property int                 $used_count
 * @property \Carbon\Carbon|null $starts_at
 * @property \Carbon\Carbon|null $expires_at
 * @property bool                $is_active
 * @property string|null         $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<Order> $orders
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read int|null $orders_count
 * @method static \App\Query\BranchBuilder<static>|Discount active()
 * @method static \App\Query\BranchBuilder<static>|Discount byCode(string $code)
 * @method static \Database\Factories\DiscountFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Discount fixedAmount()
 * @method static \App\Query\BranchBuilder<static>|Discount forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Discount forUser(int $userId)
 * @method static \App\Query\BranchBuilder<static>|Discount newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Discount newQuery()
 * @method static \App\Query\BranchBuilder<static>|Discount percentage()
 * @method static \App\Query\BranchBuilder<static>|Discount query()
 * @method static \App\Query\BranchBuilder<static>|Discount valid()
 * @method static \App\Query\BranchBuilder<static>|Discount whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereCode($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereExpiresAt($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereIsActive($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereMaxDiscountAmount($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereMinOrderAmount($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereStartsAt($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereType($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereUsageLimit($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereUsagePerUser($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereUsedCount($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Discount whereValue($value)
 * @method static \App\Query\BranchBuilder<static>|Discount withAllBranches()
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
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
 * @property-read User                                                      $user
 * @property-read Course                                                    $course
 * @property-read OrderItem|null                                            $orderItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attendance> $attendances
 * @property-read Certificate|null                                          $certificate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ActivityLog> $activityLogs
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $absentAttendances
 * @property-read int|null $absent_attendances_count
 * @property-read int|null $activity_logs_count
 * @property-read int|null $attendances_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read float $attendance_percentage
 * @property-read int $days_since_enrollment
 * @property-read \Carbon\Carbon|null $estimated_completion_date
 * @property-read int $present_attendance_count
 * @property-read int $total_attendance_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $presentAttendances
 * @property-read int|null $present_attendances_count
 * @method static \App\Query\BranchBuilder<static>|Enrollment active()
 * @method static \App\Query\BranchBuilder<static>|Enrollment byCourse(int $courseId)
 * @method static \App\Query\BranchBuilder<static>|Enrollment byStatus(\App\Enums\EnrollmentStatusEnum $status)
 * @method static \App\Query\BranchBuilder<static>|Enrollment byUser(int $userId)
 * @method static \App\Query\BranchBuilder<static>|Enrollment completed()
 * @method static \Database\Factories\EnrollmentFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Enrollment forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Enrollment newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Enrollment newQuery()
 * @method static \App\Query\BranchBuilder<static>|Enrollment query()
 * @method static \App\Query\BranchBuilder<static>|Enrollment recent(int $days = 30)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereCourseId($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereEnrolledAt($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereOrderItemId($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereProgressPercent($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Enrollment withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Enrollment withCertificates()
 * @method static \App\Query\BranchBuilder<static>|Enrollment withMinimumProgress(float $progress)
 */
	class Enrollment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $location
 * @property int $capacity
 * @property int $price
 * @property \App\Enums\BooleanEnum $published
 * @property array<array-key, mixed>|null $languages
 * @property int $view_count
 * @property int $comment_count
 * @property int $wish_count
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \App\Enums\BooleanEnum $is_online
 * @property array<array-key, mixed>|null $extra_attributes
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $siteComments
 * @property-read int|null $site_comments_count
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserView> $views
 * @property-read int|null $views_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WishList> $wishes
 * @property-read int|null $wishes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event draft()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event publishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event scheduledForPublishing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event unpublishedScheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereWishCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class Event extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int|null $category_id
 * @property \App\Enums\ExamTypeEnum $type
 * @property numeric|null $total_score
 * @property int|null $duration minutes
 * @property numeric|null $pass_score
 * @property int|null $max_attempts
 * @property bool $shuffle_questions
 * @property string $show_results
 * @property bool $allow_review
 * @property array<array-key, mixed>|null $settings
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \App\Enums\ExamStatusEnum $status
 * @property int|null $created_by
 * @property int|null $branch_id
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamAttempt> $attempts
 * @property-read int|null $attempts_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \App\Query\BranchBuilder<static>|Exam accessibleByUser(\App\Models\User $user)
 * @method static \App\Query\BranchBuilder<static>|Exam active()
 * @method static \Database\Factories\ExamFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Exam forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Exam newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|Exam participableByUser(\App\Models\User $user)
 * @method static \App\Query\BranchBuilder<static>|Exam query()
 * @method static \App\Query\BranchBuilder<static>|Exam search(string $search)
 * @method static \App\Query\BranchBuilder<static>|Exam whereAllowReview($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereCategoryId($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereCreatedBy($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereDuration($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereEndsAt($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereMaxAttempts($value)
 * @method static \App\Query\BranchBuilder<static>|Exam wherePassScore($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereSettings($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereShowResults($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereShuffleQuestions($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereStartsAt($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereTitle($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereTotalScore($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereType($value)
 * @method static \App\Query\BranchBuilder<static>|Exam whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Exam withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Exam withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \App\Query\BranchBuilder<static>|Exam withAllTagsOfAnyType($tags)
 * @method static \App\Query\BranchBuilder<static>|Exam withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \App\Query\BranchBuilder<static>|Exam withAnyTagsOfAnyType($tags)
 * @method static \App\Query\BranchBuilder<static>|Exam withAnyTagsOfType(array|string $type)
 * @method static \App\Query\BranchBuilder<static>|Exam withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam withTrashed(bool $withTrashed = true)
 * @method static \App\Query\BranchBuilder<static>|Exam withUserAttempts(\App\Models\User $user)
 * @method static \App\Query\BranchBuilder<static>|Exam withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Exam withoutTrashed()
 */
	class Exam extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $exam_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property numeric|null $total_score
 * @property numeric|null $percentage
 * @property \App\Enums\AttemptStatusEnum $status
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\User $user
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt byExam($examId)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt byUser($userId)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt completed()
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt inProgress()
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt newQuery()
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt query()
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereCompletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereExamId($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereId($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereIpAddress($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereMetadata($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt wherePercentage($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereStartedAt($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereTotalScore($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereUserAgent($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|ExamAttempt withAllBranches()
 */
	class ExamAttempt extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $exam_id
 * @property int $question_id
 * @property string $weight
 * @property int $order
 * @property int $is_required
 * @property string|null $config_override
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereConfigOverride($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExamQuestion whereWeight($value)
 */
	class ExamQuestion extends \Eloquent {}
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
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class Faq extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string      $front
 * @property string      $back
 * @property BooleanEnum $favorite
 * @property-read int $leitner_box
 * @property-read bool $is_due
 * @property-read bool $is_new
 * @property-read bool $is_finished
 * @property-read \Carbon\Carbon|null $next_review
 * @property int $id
 * @property int|null $branch_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeitnerBox> $leitnerLogs
 * @property-read int|null $leitner_logs_count
 * @property-read \App\Models\LeitnerBox|null $leitnerProgress
 * @property-read \App\Models\Taxonomy|null $taxonomy
 * @property-read \App\Models\User $user
 * @method static \App\Query\BranchBuilder<static>|FlashCard availableForStudy(int $userId)
 * @method static \App\Query\BranchBuilder<static>|FlashCard dueForUser(int $userId)
 * @method static \Database\Factories\FlashCardFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|FlashCard favorites()
 * @method static \App\Query\BranchBuilder<static>|FlashCard forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|FlashCard newForUser(int $userId)
 * @method static \App\Query\BranchBuilder<static>|FlashCard newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|FlashCard newQuery()
 * @method static \App\Query\BranchBuilder<static>|FlashCard query()
 * @method static \App\Query\BranchBuilder<static>|FlashCard search(string $term)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereBack($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereFavorite($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereFront($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereId($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|FlashCard withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|FlashCard withLeitnerForUser(int $userId)
 */
	class FlashCard extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $user_id
 * @property int $flash_card_id
 * @property int $box
 * @property \App\Enums\BooleanEnum $finished
 * @property \Illuminate\Support\Carbon $next_review_at
 * @property \Illuminate\Support\Carbon $last_review_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\FlashCard|null $flashcard
 * @property-read \App\Models\User $user
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox due()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox finished()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox inProgress()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox newQuery()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox query()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox scheduled()
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereBox($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereFinished($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereFlashCardId($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereId($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereLastReviewAt($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereNextReviewAt($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|LeitnerBox withAllBranches()
 */
	class LeitnerBox extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property string $slug
 * @property \App\Enums\BooleanEnum $published
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereViewCount($value)
 */
	class License extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $user_id
 * @property int $question_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\User $user
 * @method static \App\Query\BranchBuilder<static>|Note forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Note newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Note newQuery()
 * @method static \App\Query\BranchBuilder<static>|Note query()
 * @method static \App\Query\BranchBuilder<static>|Note whereBody($value)
 * @method static \App\Query\BranchBuilder<static>|Note whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Note whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Note whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Note whereQuestionId($value)
 * @method static \App\Query\BranchBuilder<static>|Note whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Note whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Note withAllBranches()
 */
	class Note extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int|null $branch_id
 * @property int $user_id
 * @property int $taxonomy_id
 * @property string $body
 * @property array<array-key, mixed>|null $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Taxonomy|null $tag
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\NotebookFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Notebook forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Notebook newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Notebook newQuery()
 * @method static \App\Query\BranchBuilder<static>|Notebook query()
 * @method static \App\Query\BranchBuilder<static>|Notebook whereBody($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereTags($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereTaxonomyId($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereTitle($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Notebook withAllBranches()
 */
	class Notebook extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $event
 * @property string $channel
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string|null $notification_class
 * @property string $status
 * @property int $attempts
 * @property \Illuminate\Support\Carbon|null $queued_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $failed_at
 * @property array<array-key, mixed>|null $payload
 * @property array<array-key, mixed>|null $response
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog forChannel(string $channel)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog forEvent(string $event)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereFailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereNotificationClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereQueuedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationLog whereUpdatedAt($value)
 */
	class NotificationLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \App\Enums\NotificationEventEnum $event
 * @property \App\Enums\NotificationChannelEnum $channel
 * @property string $locale
 * @property string|null $subject
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $body
 * @property array<array-key, mixed>|null $cta
 * @property array<array-key, mixed>|null $placeholders
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate active()
 * @method static \Database\Factories\NotificationTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereCta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate wherePlaceholders($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NotificationTemplate whereUpdatedAt($value)
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
 * Order Model
 * 
 * Manages customer orders with support for:
 * - Multiple order items (polymorphic)
 * - Multiple payments
 * - Discount codes
 * - Activity logging
 *
 * @property int                 $id
 * @property int                 $user_id
 * @property int|null            $discount_id
 * @property float               $pure_amount
 * @property float               $discount_amount
 * @property float               $total_amount
 * @property string              $status
 * @property string|null         $note
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User                                                   $user
 * @property-read Discount|null                                          $discount
 * @property-read \Illuminate\Database\Eloquent\Collection<OrderItem>   $items
 * @property-read \Illuminate\Database\Eloquent\Collection<Payment>     $payments
 * @property string $order_number
 * @property \App\Enums\OrderTypeEnum $type
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read int|null $items_count
 * @property-read int|null $payments_count
 * @method static \App\Query\BranchBuilder<static>|Order cancelled()
 * @method static \App\Query\BranchBuilder<static>|Order completed()
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Order forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Order newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Order newQuery()
 * @method static \App\Query\BranchBuilder<static>|Order pending()
 * @method static \App\Query\BranchBuilder<static>|Order processing()
 * @method static \App\Query\BranchBuilder<static>|Order query()
 * @method static \App\Query\BranchBuilder<static>|Order whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereDiscountAmount($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereDiscountId($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereNote($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereOrderNumber($value)
 * @method static \App\Query\BranchBuilder<static>|Order wherePureAmount($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereTotalAmount($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereType($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Order whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Order withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Order withDiscount()
 * @method static \App\Query\BranchBuilder<static>|Order withStatus(\App\Enums\OrderStatusEnum $status)
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
 * @property int|null $branch_id
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\Enrollment|null $enrollment
 * @property-read string $formatted_price
 * @property-read string $formatted_total_price
 * @property-read string $item_description
 * @property-read string $item_title
 * @property-read string $item_type
 * @property-read string $refund_policy
 * @property-read float $total_price
 * @method static \App\Query\BranchBuilder<static>|OrderItem byOrder(int $orderId)
 * @method static \App\Query\BranchBuilder<static>|OrderItem byPriceRange(float $minPrice, float $maxPrice)
 * @method static \App\Query\BranchBuilder<static>|OrderItem courseEnrollments()
 * @method static \App\Query\BranchBuilder<static>|OrderItem courses()
 * @method static \App\Query\BranchBuilder<static>|OrderItem forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|OrderItem newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|OrderItem newQuery()
 * @method static \App\Query\BranchBuilder<static>|OrderItem query()
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereId($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereItemableId($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereItemableType($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereOrderId($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem wherePrice($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereQuantity($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|OrderItem withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|OrderItem withMinimumQuantity(int $quantity)
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
 * @property \App\Enums\YesNoEnum $deletable
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereDeletable($value)
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
 * @property int $parent_id
 * @property int $child_id
 * @property-read \App\Models\User $child
 * @property-read \App\Models\User $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParentChild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParentChild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParentChild query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParentChild whereChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParentChild whereParentId($value)
 */
	class ParentChild extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $user_id
 * @property int $order_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $scheduled_date
 * @property \App\Enums\PaymentTypeEnum $type
 * @property \App\Enums\PaymentStatusEnum $status
 * @property string|null $note
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Payment forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Payment newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Payment newQuery()
 * @method static \App\Query\BranchBuilder<static>|Payment query()
 * @method static \App\Query\BranchBuilder<static>|Payment whereAmount($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereNote($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereOrderId($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereScheduledDate($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereType($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Payment whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Payment withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Payment withExtraAttributes()
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
 * @property int|null $id_number
 * @property string|null $national_code
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property \App\Enums\GenderEnum|null $gender
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $father_name
 * @property string|null $father_phone
 * @property string|null $mother_name
 * @property string|null $mother_phone
 * @property \App\Enums\ReligionEnum|null $religion
 * @property float $salary
 * @property float $benefit
 * @property \Illuminate\Support\Carbon|null $cooperation_start_date
 * @property \Illuminate\Support\Carbon|null $cooperation_end_date
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBenefit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCooperationEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCooperationStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFatherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereFatherPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereMotherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereMotherPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereNationalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property \App\Enums\QuestionTypeEnum $type
 * @property int|null $category_id
 * @property int|null $subject_id
 * @property int|null $competency_id
 * @property string $title
 * @property string|null $body
 * @property string|null $explanation
 * @property \App\Enums\DifficultyEnum|null $difficulty
 * @property numeric $default_score
 * @property array<array-key, mixed>|null $config
 * @property array<array-key, mixed>|null $correct_answer
 * @property array<array-key, mixed>|null $metadata
 * @property int|null $created_by
 * @property bool $is_active
 * @property bool $is_public
 * @property bool $is_survey_question
 * @property int $usage_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\QuestionCompetency|null $competency
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exam> $exams
 * @property-read int|null $exams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuestionOption> $options
 * @property-read int|null $options_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read \App\Models\QuestionSubject|null $subject
 * @property-read int|null $tags_count
 * @method static \App\Query\BranchBuilder<static>|Question active()
 * @method static \App\Query\BranchBuilder<static>|Question byType(string $type)
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Question forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Question fromBank()
 * @method static \App\Query\BranchBuilder<static>|Question newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Question newQuery()
 * @method static \App\Query\BranchBuilder<static>|Question public()
 * @method static \App\Query\BranchBuilder<static>|Question query()
 * @method static \App\Query\BranchBuilder<static>|Question search(string $search)
 * @method static \App\Query\BranchBuilder<static>|Question surveyQuestions()
 * @method static \App\Query\BranchBuilder<static>|Question whereBody($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereCategoryId($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereCompetencyId($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereConfig($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereCorrectAnswer($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereCreatedBy($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereDefaultScore($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereDifficulty($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereExplanation($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereIsActive($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereIsPublic($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereIsSurveyQuestion($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereMetadata($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereSubjectId($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereTitle($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereType($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Question whereUsageCount($value)
 * @method static \App\Query\BranchBuilder<static>|Question withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Question withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \App\Query\BranchBuilder<static>|Question withAllTagsOfAnyType($tags)
 * @method static \App\Query\BranchBuilder<static>|Question withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \App\Query\BranchBuilder<static>|Question withAnyTagsOfAnyType($tags)
 * @method static \App\Query\BranchBuilder<static>|Question withAnyTagsOfType(array|string $type)
 * @method static \App\Query\BranchBuilder<static>|Question withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 */
	class Question extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int|null $branch_id
 * @property int $ordering
 * @property int $published
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\QuestionCompetencyFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency newQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency query()
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency search($keyword)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency whereId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency whereLanguages($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency whereOrdering($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency wherePublished($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionCompetency withAllBranches()
 */
	class QuestionCompetency extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $question_id
 * @property string $content
 * @property string $type
 * @property bool $is_correct
 * @property int $order
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Question $question
 * @method static \Database\Factories\QuestionOptionFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|QuestionOption forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionOption newQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionOption query()
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereContent($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereIsCorrect($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereMetadata($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereOrder($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereQuestionId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereType($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionOption withAllBranches()
 */
	class QuestionOption extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int|null $branch_id
 * @property int $ordering
 * @property int $published
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\QuestionSubjectFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject newQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject query()
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject search($keyword)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject whereId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject whereLanguages($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject whereOrdering($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject wherePublished($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSubject withAllBranches()
 */
	class QuestionSubject extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property int|null $branch_id
 * @property int $category_id
 * @property \App\Enums\BooleanEnum $published
 * @property int $ordering
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\QuestionSystemFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem newQuery()
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem query()
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem search($keyword)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereCategoryId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereId($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereLanguages($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereOrdering($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem wherePublished($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|QuestionSystem withAllBranches()
 */
	class QuestionSystem extends \Eloquent {}
}

namespace App\Models{
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSessionTemplate> $courseSessionTemplates
 * @property int|null $branch_id
 * @property-read \App\Models\Branch|null $branch
 * @property-read int|null $course_session_templates_count
 * @property-read int|null $duration
 * @property-read int|null $file_size
 * @property-read string|null $formatted_file_size
 * @property-read string|null $mime_type
 * @property-read string|null $thumbnail_path
 * @property-read string $url
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \App\Query\BranchBuilder<static>|Resource byType(\App\Enums\ResourceType $type)
 * @method static \App\Query\BranchBuilder<static>|Resource documents()
 * @method static \Database\Factories\ResourceFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Resource forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Resource forCourseSessionTemplate(int $courseSessionTemplateId)
 * @method static \App\Query\BranchBuilder<static>|Resource media()
 * @method static \App\Query\BranchBuilder<static>|Resource newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource onlyTrashed()
 * @method static \App\Query\BranchBuilder<static>|Resource ordered()
 * @method static \App\Query\BranchBuilder<static>|Resource private()
 * @method static \App\Query\BranchBuilder<static>|Resource public()
 * @method static \App\Query\BranchBuilder<static>|Resource query()
 * @method static \App\Query\BranchBuilder<static>|Resource whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereExtraAttributes($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereIsPublic($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereOrder($value)
 * @method static \App\Query\BranchBuilder<static>|Resource wherePath($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereTitle($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereType($value)
 * @method static \App\Query\BranchBuilder<static>|Resource whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Resource withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Resource withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource withoutTrashed()
 */
	class Resource extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
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
 * @property int                 $id
 * @property string              $name
 * @property int                 $capacity
 * @property string|null         $location
 * @property array|null          $languages
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Course>        $courses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CourseSession> $sessions
 * @property int|null $branch_id
 * @property-read \App\Models\Branch|null $branch
 * @property-read int|null $courses_count
 * @property-read string $full_location
 * @property-read int|null $sessions_count
 * @method static \App\Query\BranchBuilder<static>|Room byCapacityRange(int $minCapacity, int $maxCapacity)
 * @method static \App\Query\BranchBuilder<static>|Room byLocation(string $location)
 * @method static \Database\Factories\RoomFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Room forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Room multilingual()
 * @method static \App\Query\BranchBuilder<static>|Room newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Room newQuery()
 * @method static \App\Query\BranchBuilder<static>|Room query()
 * @method static \App\Query\BranchBuilder<static>|Room supportingLanguage(string $language)
 * @method static \App\Query\BranchBuilder<static>|Room whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Room whereCapacity($value)
 * @method static \App\Query\BranchBuilder<static>|Room whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Room whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Room whereLocation($value)
 * @method static \App\Query\BranchBuilder<static>|Room whereName($value)
 * @method static \App\Query\BranchBuilder<static>|Room whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Room withAllBranches()
 * @method static \App\Query\BranchBuilder<static>|Room withMinimumCapacity(int $capacity)
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
 * @property string|null $og_image
 * @property string|null $twitter_image
 * @property string|null $focus_keyword
 * @property string|null $meta_keywords
 * @property string|null $author
 * @property bool $sitemap_exclude
 * @property numeric|null $sitemap_priority
 * @property string|null $sitemap_changefreq
 * @property string|null $image_alt
 * @property string|null $image_title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Http\Resources\Json\JsonResource|null $morph_resource
 * @property-read string $morph_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $morphable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereFocusKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereImageTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereMorphableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereMorphableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereOldUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereRedirectTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereRobotsMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereSitemapChangefreq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereSitemapExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereSitemapPriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereTwitterImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoOption whereUpdatedAt($value)
 */
	class SeoOption extends \Eloquent {}
}

namespace App\Models{
/**
 * @property SchemalessAttributes $extra_attributes
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed> $permissions
 * @property bool|null $show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read string|null $title
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
 * @property int $id
 * @property \App\Enums\BooleanEnum $published
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sms whereUpdatedAt($value)
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
 * @property int $id
 * @property int|null $branch_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $scheduled_for
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TaskFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Task forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Task newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Task newQuery()
 * @method static \App\Query\BranchBuilder<static>|Task query()
 * @method static \App\Query\BranchBuilder<static>|Task whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereCompletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereDescription($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereScheduledFor($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereTitle($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Task whereUserId($value)
 * @method static \App\Query\BranchBuilder<static>|Task withAllBranches()
 */
	class Task extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property \App\Enums\TaxonomyTypeEnum $type
 * @property string|null $name
 * @property string $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Taxonomy whereUserId($value)
 */
	class Taxonomy extends \Eloquent {}
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
 * @property int|null $branch_id
 * @property array<array-key, mixed>|null $languages
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $activeCourses
 * @property-read int|null $active_courses_count
 * @property-read \App\Models\Branch|null $branch
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
 * @method static \App\Query\BranchBuilder<static>|Term active()
 * @method static \App\Query\BranchBuilder<static>|Term byAcademicYear(string $academicYear)
 * @method static \App\Query\BranchBuilder<static>|Term current()
 * @method static \Database\Factories\TermFactory factory($count = null, $state = [])
 * @method static \App\Query\BranchBuilder<static>|Term forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|Term future()
 * @method static \App\Query\BranchBuilder<static>|Term longTerm()
 * @method static \App\Query\BranchBuilder<static>|Term newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|Term newQuery()
 * @method static \App\Query\BranchBuilder<static>|Term past()
 * @method static \App\Query\BranchBuilder<static>|Term query()
 * @method static \App\Query\BranchBuilder<static>|Term search($keyword)
 * @method static \App\Query\BranchBuilder<static>|Term whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereDeletedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereEndDate($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereId($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereLanguages($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereStartDate($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereStatus($value)
 * @method static \App\Query\BranchBuilder<static>|Term whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|Term withAllBranches()
 */
	class Term extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property string $subject
 * @property int|null $ticket_department_id
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
 * @property-read \App\Models\TicketDepartment|null $department
 * @property-read int $unread_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUserId($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $title
 * @property string $description
 * @property int $id
 * @property \App\Enums\BooleanEnum $published
 * @property array<array-key, mixed>|null $languages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translations
 * @property-read int|null $translations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translation> $translationsPure
 * @property-read int|null $translations_pure_count
 * @method static \Database\Factories\TicketDepartmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketDepartment whereUpdatedAt($value)
 */
	class TicketDepartment extends \Eloquent {}
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
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $mobile_verified_at
 * @property string $password
 * @property \App\Enums\BooleanEnum $status
 * @property \App\Enums\UserTypeEnum $type
 * @property int|null $branch_id
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Branch> $branches
 * @property-read int|null $branches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CardHistory> $cardHistory
 * @property-read int|null $card_history_count
 * @property-read \App\Models\ParentChild|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Branch|null $defaultBranch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Enrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property-read mixed $full_name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $parents
 * @property-read int|null $parents_count
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFamily($value)
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
 * @property int|null $branch_id
 * @property int $exam_attempt_id
 * @property int $question_id
 * @property array<array-key, mixed> $answer_data
 * @property numeric|null $score
 * @property numeric $max_score
 * @property bool|null $is_correct
 * @property bool|null $is_partially_correct
 * @property int|null $time_spent seconds
 * @property \Illuminate\Support\Carbon|null $answered_at
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExamAttempt $attempt
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Question $question
 * @method static \App\Query\BranchBuilder<static>|UserAnswer correct()
 * @method static \App\Query\BranchBuilder<static>|UserAnswer forBranch(int $branchId)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer incorrect()
 * @method static \App\Query\BranchBuilder<static>|UserAnswer newModelQuery()
 * @method static \App\Query\BranchBuilder<static>|UserAnswer newQuery()
 * @method static \App\Query\BranchBuilder<static>|UserAnswer partiallyCorrect()
 * @method static \App\Query\BranchBuilder<static>|UserAnswer query()
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereAnswerData($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereAnsweredAt($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereBranchId($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereCreatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereExamAttemptId($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereId($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereIsCorrect($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereIsPartiallyCorrect($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereMaxScore($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereQuestionId($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereReviewedAt($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereScore($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereTimeSpent($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer whereUpdatedAt($value)
 * @method static \App\Query\BranchBuilder<static>|UserAnswer withAllBranches()
 */
	class UserAnswer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $otp
 * @property string $ip_address
 * @property int $try_count
 * @property string|null $used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereTryCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOtp whereUserId($value)
 */
	class UserOtp extends \Eloquent {}
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

