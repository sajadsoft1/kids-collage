{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- TERM DETAIL - Term info, courses in term, students in term --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}

<div class="py-4 md:py-6">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    {{-- Term info card --}}
    <x-card :title="trans('general.page_sections.data')" class="mb-6" shadow separator>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="sm:col-span-2 lg:col-span-3">
                <h2 class="text-lg font-semibold">{{ $term->title }}</h2>
                @if ($term->description)
                    <p class="mt-2 text-sm text-base-content/80">{{ $term->description }}</p>
                @endif
            </div>
            <div>
                <span class="text-xs text-base-content/60">{{ trans('validation.attributes.start_date') }}</span>
                <p class="font-medium">{{ $term->start_date->format('Y-m-d') }}</p>
            </div>
            <div>
                <span class="text-xs text-base-content/60">{{ trans('validation.attributes.end_date') }}</span>
                <p class="font-medium">{{ $term->end_date->format('Y-m-d') }}</p>
            </div>
            <div>
                <span class="text-xs text-base-content/60">{{ trans('datatable.status') }}</span>
                <p><x-badge :value="$term->status->title()" class="mt-1 badge-sm" /></p>
            </div>
            <div>
                <span class="text-xs text-base-content/60">{{ trans('term.page.academic_year') }}</span>
                <p class="font-medium">{{ $term->academic_year }}</p>
            </div>
            <div>
                <span class="text-xs text-base-content/60">{{ trans('term.page.duration') }}</span>
                <p class="font-medium">{{ $term->duration_days }} {{ trans('term.page.days') }}
                    ({{ $term->duration_weeks }} {{ trans('term.page.weeks') }})</p>
            </div>
            @if ($term->hasStarted() && !$term->hasEnded())
                <div>
                    <span class="text-xs text-base-content/60">{{ trans('term.page.progress') }}</span>
                    <p class="font-medium">{{ number_format($term->progress_percentage, 1) }}%</p>
                </div>
            @endif
        </div>
    </x-card>

    {{-- Courses in this term --}}
    <x-card :title="trans('term.page.courses_in_term')" class="mb-6" shadow separator>
        @if ($this->courses->isEmpty())
            <p class="text-sm text-base-content/60">{{ trans('term.page.no_courses') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="table table-zebra table-pin-rows">
                    <thead>
                        <tr>
                            <th>{{ trans('validation.attributes.title') }}</th>
                            <th>{{ trans('validation.attributes.teacher') }}</th>
                            <th>{{ trans('validation.attributes.room_id') }}</th>
                            <th>{{ trans('datatable.status') }}</th>
                            <th>{{ trans('term.page.enrollments_count') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->courses as $course)
                            <tr wire:key="course-{{ $course->id }}">
                                <td>{{ $course->template?->title ?? '—' }}</td>
                                <td>{{ $course->teacher?->full_name ?? '—' }}</td>
                                <td>{{ $course->room?->name ?? '—' }}</td>
                                <td><x-badge :value="$course->status->title()" class="badge-sm" /></td>
                                <td>{{ $course->active_enrollments_count ?? 0 }}</td>
                                <td>
                                    @if ($course->template)
                                        <x-button :link="route('admin.course.show', [$course->template, $course])" icon="o-eye" class="btn-ghost btn-xs"
                                            no-wire-navigate>
                                            {{ trans('datatable.buttons.show') }}
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    {{-- Students in this term --}}
    <x-card :title="trans('term.page.students_in_term')" shadow separator>
        @if ($this->students->isEmpty())
            <p class="text-sm text-base-content/60">{{ trans('term.page.no_students') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="table table-zebra table-pin-rows">
                    <thead>
                        <tr>
                            <th>{{ trans('validation.attributes.name') }}</th>
                            <th>{{ trans('term.page.courses_enrolled_count') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->students as $row)
                            <tr wire:key="student-{{ $row['user']->id }}">
                                <td>{{ $row['user']->full_name }}</td>
                                <td>{{ $row['enrollment_count'] }}</td>
                                <td>
                                    <x-button :link="route('admin.user.edit', $row['user'])" icon="o-pencil" class="btn-ghost btn-xs"
                                        no-wire-navigate>
                                        {{ trans('datatable.buttons.edit') }}
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</div>
