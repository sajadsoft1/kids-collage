{{-- Modal: show enrollment summary (attendance, progress, exam results) and let admin set final grade before issuing certificate. --}}

@php
    $enrollment = $this->issueCertificateEnrollment;
    $examAttempts = $this->issueCertificateExamAttempts;
@endphp

<x-modal wire:model="showIssueCertificateModal" :title="trans('enrollment.modal_issue_certificate_title')" separator with-close-button
    class="backdrop-blur" max-width="2xl">
    @if ($enrollment)
        <div class="space-y-4">
            {{-- Student & course --}}
            <div class="rounded-lg bg-base-200 p-4">
                <p class="text-sm font-medium text-base-content/80">{{ trans('enrollment.modal_student') }}</p>
                <p class="font-medium">{{ $enrollment->user->name ?? $enrollment->user->email }}</p>
                <p class="mt-2 text-sm font-medium text-base-content/80">{{ trans('enrollment.modal_course') }}</p>
                <p class="font-medium">{{ $enrollment->course->title ?? $enrollment->course->template?->title }}</p>
            </div>

            {{-- Attendance & progress --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-base-300 p-4">
                    <p class="text-sm text-base-content/70">{{ trans('enrollment.modal_attendance_percentage') }}</p>
                    <p class="text-xl font-semibold">{{ number_format((float) $enrollment->attendance_percentage, 1) }}%</p>
                </div>
                <div class="rounded-lg border border-base-300 p-4">
                    <p class="text-sm text-base-content/70">{{ trans('enrollment.modal_progress_percent') }}</p>
                    <p class="text-xl font-semibold">{{ number_format((float) $enrollment->progress_percent, 1) }}%</p>
                </div>
            </div>

            {{-- Exam results (user's completed attempts) --}}
            <div class="rounded-lg border border-base-300 p-4">
                <p class="mb-2 text-sm font-medium text-base-content/80">{{ trans('enrollment.modal_exam_results') }}</p>
                @if ($examAttempts->isEmpty())
                    <p class="text-sm text-base-content/60">{{ trans('enrollment.modal_no_exam_results') }}</p>
                @else
                    <ul class="max-h-40 space-y-2 overflow-y-auto text-sm">
                        @foreach ($examAttempts as $attempt)
                            @php
                                $passed = $attempt->isPassed();
                            @endphp
                            <li class="flex flex-wrap items-center justify-between gap-2 rounded bg-base-200 px-3 py-2">
                                <div class="flex flex-wrap items-center gap-2 min-w-0">
                                    {{-- Pass/Fail icon and color --}}
                                    @if ($passed === true)
                                        <x-icon name="o-check-circle" class="w-5 h-5 shrink-0 text-success" title="{{ trans('enrollment.modal_passed') }}" />
                                    @elseif ($passed === false)
                                        <x-icon name="o-x-circle" class="w-5 h-5 shrink-0 text-error" title="{{ trans('enrollment.modal_failed') }}" />
                                    @else
                                        <x-icon name="o-minus-circle" class="w-5 h-5 shrink-0 text-base-content/40" title="{{ trans('enrollment.modal_not_scored') }}" />
                                    @endif
                                    <span class="font-medium">{{ $attempt->exam?->title ?? __('enrollment.modal_exam_unknown') }}</span>
                                    @if ($this->isAttemptLinkedToCourse($attempt, $enrollment))
                                        <span class="badge badge-xs badge-info shrink-0">{{ trans('enrollment.modal_linked_to_course') }}</span>
                                    @endif
                                </div>
                                <span class="shrink-0">
                                    {{ trans('enrollment.modal_score') }}: {{ number_format((float) $attempt->total_score, 1) }}
                                    @if ($attempt->percentage !== null)
                                        ({{ number_format((float) $attempt->percentage, 1) }}%)
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Suggested grade (read-only) --}}
            <div class="rounded-lg border border-base-300 p-4">
                <p class="text-sm text-base-content/70">{{ trans('enrollment.modal_suggested_grade') }}</p>
                <p class="mt-1">
                    <span class="badge badge-lg badge-info">{{ $enrollment->getSuggestedGrade() }}</span>
                </p>
            </div>

            {{-- Final grade (admin choice) --}}
            <x-select
                :label="trans('enrollment.modal_final_grade_label')"
                wire:model="issueCertificateGrade"
                :options="[
                    ['id' => 'A', 'name' => 'A'],
                    ['id' => 'B', 'name' => 'B'],
                    ['id' => 'C', 'name' => 'C'],
                    ['id' => 'D', 'name' => 'D'],
                    ['id' => 'F', 'name' => 'F'],
                ]"
                option-value="id"
                option-label="name"
            />
        </div>
    @endif

    <x-slot:actions separator>
        <div class="flex justify-end gap-3">
            <x-button :label="trans('general.cancel')" @click="$wire.closeIssueCertificateModal()" />
            <x-button :label="trans('enrollment.modal_confirm_issue')" class="btn-primary"
                wire:click="confirmIssueCertificate" spinner="confirmIssueCertificate" />
        </div>
    </x-slot:actions>
</x-modal>
