<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Tabs;

use App\Actions\User\UpdateUserResumeAction;
use App\Enums\UserTypeEnum;
use App\Helpers\Constants;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use League\Flysystem\UnableToRetrieveMetadata;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\FileNotPreviewableException;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class UserTabResume extends Component
{
    use CrudHelperTrait, Toast, WithFileUploads;

    public User $user;

    public UserTypeEnum $detected_user_type;

    public string $detected_route_name = 'admin.user';

    public ?string $resume_description = '';

    public ?string $education_description = '';

    public ?string $courses_description = '';

    public $resume_image;

    public $education_image;

    public $courses_image;

    public function mount(User $user, UserTypeEnum $detected_user_type, string $detected_route_name): void
    {
        $this->user = $user;
        $this->detected_user_type = $detected_user_type;
        $this->detected_route_name = $detected_route_name;

        if ($this->user->id && $this->user->profile) {
            $this->resume_description = $this->user->profile->extra_attributes->get('resume_description', '');
            $this->education_description = $this->user->profile->extra_attributes->get('education_description', '');
            $this->courses_description = $this->user->profile->extra_attributes->get('courses_description', '');
        }
    }

    protected function rules(): array
    {
        return [
            'resume_description' => 'nullable|string',
            'education_description' => 'nullable|string',
            'courses_description' => 'nullable|string',
            'resume_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'education_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'courses_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    public function submit(): void
    {
        try {
            $payload = $this->validate();
        } catch (UnableToRetrieveMetadata $e) {
            $this->clearStaleUploads();
            $this->error(
                title: trans('validation.file_upload_expired'),
            );

            return;
        }

        UpdateUserResumeAction::run($this->user, $payload);
        $this->success(
            title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
        );
    }

    /** Clear file properties that reference missing Livewire temp files. */
    private function clearStaleUploads(): void
    {
        $this->resume_image = null;
        $this->education_image = null;
        $this->courses_image = null;
    }

    /** Safe preview URL for resume: temp upload or saved media, or null. */
    public function getResumePreviewUrl(): ?string
    {
        if ($this->resume_image) {
            try {
                return $this->resume_image->temporaryUrl();
            } catch (FileNotPreviewableException|Throwable $e) {
                return null;
            }
        }

        return $this->user->getFirstMediaUrl('resume', Constants::RESOLUTION_512_SQUARE) ?: null;
    }

    /** Safe preview URL for education: temp upload or saved media, or null. */
    public function getEducationPreviewUrl(): ?string
    {
        if ($this->education_image) {
            try {
                return $this->education_image->temporaryUrl();
            } catch (FileNotPreviewableException|Throwable $e) {
                return null;
            }
        }

        return $this->user->getFirstMediaUrl('education_certificates', Constants::RESOLUTION_512_SQUARE) ?: null;
    }

    /** Safe preview URL for courses: temp upload or saved media, or null. */
    public function getCoursesPreviewUrl(): ?string
    {
        if ($this->courses_image) {
            try {
                return $this->courses_image->temporaryUrl();
            } catch (FileNotPreviewableException|Throwable $e) {
                return null;
            }
        }

        return $this->user->getFirstMediaUrl('completed_courses', Constants::RESOLUTION_512_SQUARE) ?: null;
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.tabs.user-tab-resume');
    }
}
