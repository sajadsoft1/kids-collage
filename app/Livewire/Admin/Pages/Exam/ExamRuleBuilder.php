<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Enums\UserTypeEnum;
use App\Models\Course;
use App\Models\Term;
use Livewire\Component;

class ExamRuleBuilder extends Component
{
    public array $rules = [
        'groups'      => [],
        'group_logic' => 'or',
    ];

    public array $availableFields = [
        'user_type'          => 'نوع کاربر',
        'enrollment_date'    => 'تاریخ ثبت نام',
        'enrolled_in_course' => 'ثبت نام در دوره',
        'has_role_in_course' => 'نقش در دوره',
        'term_id'            => 'ترم',
        'created_at'         => 'تاریخ ثبت نام کاربر',
    ];

    public array $availableOperators = [
        'user_type'          => [
            'equals'     => 'برابر با',
            'not_equals' => 'مخالف با',
            'in'         => 'یکی از',
            'not_in'     => 'هیچکدام از',
        ],
        'enrollment_date'    => [
            'before'       => 'قبل از',
            'after'        => 'بعد از',
            'on'           => 'در تاریخ',
            'before_or_on' => 'قبل از یا برابر',
            'after_or_on'  => 'بعد از یا برابر',
            'is_null'      => 'تعریف نشده',
        ],
        'enrolled_in_course' => [
            'equals'     => 'برابر با',
            'not_equals' => 'مخالف با',
            'in'         => 'یکی از',
            'not_in'     => 'هیچکدام از',
            'is_null'    => 'ثبت نام نشده',
        ],
        'has_role_in_course' => [
            'equals'     => 'برابر با',
            'not_equals' => 'مخالف با',
        ],
        'term_id'            => [
            'equals'     => 'برابر با',
            'not_equals' => 'مخالف با',
            'in'         => 'یکی از',
            'not_in'     => 'هیچکدام از',
            'is_null'    => 'تعریف نشده',
        ],
        'created_at'         => [
            'before'       => 'قبل از',
            'after'        => 'بعد از',
            'on'           => 'در تاریخ',
            'before_or_on' => 'قبل از یا برابر',
            'after_or_on'  => 'بعد از یا برابر',
        ],
    ];

    public function mount(?array $rules = null): void
    {
        if ($rules !== null && is_array($rules)) {
            $this->rules = array_merge([
                'groups'      => [],
                'group_logic' => 'or',
            ], $rules);
        }
    }

    public function addGroup(): void
    {
        $this->rules['groups'][] = [
            'logic'      => 'and',
            'conditions' => [],
        ];
    }

    public function removeGroup(int $groupIndex): void
    {
        unset($this->rules['groups'][$groupIndex]);
        $this->rules['groups'] = array_values($this->rules['groups']);
    }

    public function addCondition(int $groupIndex): void
    {
        if ( ! isset($this->rules['groups'][$groupIndex])) {
            return;
        }

        $this->rules['groups'][$groupIndex]['conditions'][] = [
            'field'    => 'user_type',
            'operator' => 'equals',
            'value'    => '',
        ];
    }

    public function removeCondition(int $groupIndex, int $conditionIndex): void
    {
        if ( ! isset($this->rules['groups'][$groupIndex]['conditions'][$conditionIndex])) {
            return;
        }

        unset($this->rules['groups'][$groupIndex]['conditions'][$conditionIndex]);
        $this->rules['groups'][$groupIndex]['conditions'] = array_values(
            $this->rules['groups'][$groupIndex]['conditions']
        );
    }

    public function updatedRules(): void
    {
        $this->dispatch('rulesUpdated', rules: $this->rules);
    }

    public function updated(string $property): void
    {
        // Dispatch when any rule property changes
        if (str_starts_with($property, 'rules.')) {
            $this->dispatch('rulesUpdated', rules: $this->rules);
        }
    }

    public function getOperatorsForField(string $field): array
    {
        return $this->availableOperators[$field] ?? [];
    }

    public function getFieldOptions(): array
    {
        $options = [];

        foreach ($this->availableFields as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $options;
    }

    public function getCourses(): array
    {
        return Course::with('term')
            ->get()
            ->map(fn ($course) => [
                'id'   => $course->id,
                'name' => $course->title . ' (' . ($course->term->title ?? 'بدون ترم') . ')',
            ])
            ->toArray();
    }

    public function getTerms(): array
    {
        return Term::all()
            ->map(fn ($term) => [
                'id'   => $term->id,
                'name' => $term->title,
            ])
            ->toArray();
    }

    public function getUserTypes(): array
    {
        return UserTypeEnum::options();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.exam.exam-rule-builder');
    }
}
