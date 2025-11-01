<?php

declare(strict_types=1);

namespace App\Actions\Exam;

use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class EvaluateExamRulesAction
{
    use AsAction;

    /** Check if user can participate in exam based on rules. */
    public function handle(Exam $exam, User $user): bool
    {
        $rules = $exam->getRules();

        // If no rules exist, allow access (existing logic will handle other checks)
        if ( ! $rules || empty($rules)) {
            return true;
        }

        return $this->evaluateRules($rules, $user);
    }

    /** Evaluate rule conditions against user. */
    protected function evaluateRules(array $rules, User $user): bool
    {
        $groups     = $rules['groups'] ?? [];
        $groupLogic = $rules['group_logic'] ?? 'or';

        if (empty($groups)) {
            return true;
        }

        $groupResults = [];

        foreach ($groups as $group) {
            $conditions = $group['conditions'] ?? [];
            $logic      = $group['logic'] ?? 'and';

            if (empty($conditions)) {
                $groupResults[] = true;

                continue;
            }

            $conditionResults = [];

            foreach ($conditions as $condition) {
                $conditionResults[] = $this->checkCondition($condition, $user);
            }

            // Evaluate group based on logic (AND: all must be true, OR: any must be true)
            if ($logic === 'and') {
                $groupResults[] = ! in_array(false, $conditionResults, true);
            } else {
                $groupResults[] = in_array(true, $conditionResults, true);
            }
        }

        // Evaluate groups based on group logic
        if ($groupLogic === 'and') {
            return ! in_array(false, $groupResults, true);
        }

        // OR logic (default)
        return in_array(true, $groupResults, true);
    }

    /** Check a single condition against user. */
    protected function checkCondition(array $condition, User $user): bool
    {
        $field    = $condition['field'] ?? '';
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? null;

        return match ($field) {
            'user_type'          => $this->checkUserType($condition, $user),
            'enrollment_date'    => $this->checkEnrollmentDate($condition, $user),
            'enrolled_in_course' => $this->checkEnrolledInCourse($condition, $user),
            'has_role_in_course' => $this->checkHasRoleInCourse($condition, $user),
            'term_id'            => $this->checkTermId($condition, $user),
            'created_at'         => $this->checkCreatedAt($condition, $user),
            default              => true, // Unknown field, allow access
        };
    }

    /** Check user type condition. */
    protected function checkUserType(array $condition, User $user): bool
    {
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? '';

        $userType = $user->type->value;

        return match ($operator) {
            'equals'     => $userType === $value,
            'not_equals' => $userType !== $value,
            'in'         => is_array($value) && in_array($userType, $value, true),
            'not_in'     => is_array($value) && ! in_array($userType, $value, true),
            default      => true,
        };
    }

    /** Check enrollment date condition. */
    protected function checkEnrollmentDate(array $condition, User $user): bool
    {
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? '';

        // Get user's enrollments
        $enrollments = $user->enrollments;

        if ($enrollments->isEmpty()) {
            return $operator === 'is_null';
        }

        // Check if any enrollment matches the date condition
        foreach ($enrollments as $enrollment) {
            if ( ! $enrollment->enrolled_at) {
                continue;
            }

            $enrollmentDate = Carbon::parse($enrollment->enrolled_at);
            $conditionDate  = Carbon::parse($value);

            $matches = match ($operator) {
                'before'       => $enrollmentDate->lt($conditionDate),
                'after'        => $enrollmentDate->gt($conditionDate),
                'on'           => $enrollmentDate->isSameDay($conditionDate),
                'before_or_on' => $enrollmentDate->lte($conditionDate),
                'after_or_on'  => $enrollmentDate->gte($conditionDate),
                'is_null'      => false,
                default        => true,
            };

            if ($matches) {
                return true;
            }
        }

        return false;
    }

    /** Check if user is enrolled in course(s). */
    protected function checkEnrolledInCourse(array $condition, User $user): bool
    {
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? null;

        $enrolledCourseIds = $user->enrollments()
            ->pluck('course_id')
            ->toArray();

        if (empty($enrolledCourseIds)) {
            return $operator === 'is_null';
        }

        return match ($operator) {
            'equals'     => in_array($value, $enrolledCourseIds, true),
            'not_equals' => ! in_array($value, $enrolledCourseIds, true),
            'in'         => is_array($value) && ! empty(array_intersect($value, $enrolledCourseIds)),
            'not_in'     => is_array($value) && empty(array_intersect($value, $enrolledCourseIds)),
            'is_null'    => false,
            default      => true,
        };
    }

    /** Check if user has role in course. */
    protected function checkHasRoleInCourse(array $condition, User $user): bool
    {
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? '';

        // For students: check enrollments
        if ($value === 'student') {
            return $user->enrollments()->exists();
        }

        // For teachers: check if user teaches any course
        if ($value === 'teacher') {
            return $user->taughtClasses()->exists();
        }

        // For parents: check if user has children enrolled
        if ($value === 'parent') {
            return $user->children()->whereHas('enrollments')->exists();
        }

        return match ($operator) {
            'equals'     => false,
            'not_equals' => true,
            default      => true,
        };
    }

    /** Check term ID condition. */
    protected function checkTermId(array $condition, User $user): bool
    {
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? null;

        // Get term IDs from user's enrollments
        $enrolledTermIds = $user->enrollments()
            ->with('course.term')
            ->get()
            ->map(fn ($enrollment) => $enrollment->course->term_id)
            ->filter()
            ->unique()
            ->toArray();

        if (empty($enrolledTermIds)) {
            return $operator === 'is_null';
        }

        return match ($operator) {
            'equals'     => in_array($value, $enrolledTermIds, true),
            'not_equals' => ! in_array($value, $enrolledTermIds, true),
            'in'         => is_array($value) && ! empty(array_intersect($value, $enrolledTermIds)),
            'not_in'     => is_array($value) && empty(array_intersect($value, $enrolledTermIds)),
            'is_null'    => false,
            default      => true,
        };
    }

    /** Check user created_at date condition. */
    protected function checkCreatedAt(array $condition, User $user): bool
    {
        $operator = $condition['operator'] ?? '';
        $value    = $condition['value'] ?? '';

        if ( ! $user->created_at) {
            return $operator === 'is_null';
        }

        $userCreatedAt = Carbon::parse($user->created_at);
        $conditionDate = Carbon::parse($value);

        return match ($operator) {
            'before'       => $userCreatedAt->lt($conditionDate),
            'after'        => $userCreatedAt->gt($conditionDate),
            'on'           => $userCreatedAt->isSameDay($conditionDate),
            'before_or_on' => $userCreatedAt->lte($conditionDate),
            'after_or_on'  => $userCreatedAt->gte($conditionDate),
            'is_null'      => false,
            default        => true,
        };
    }
}
