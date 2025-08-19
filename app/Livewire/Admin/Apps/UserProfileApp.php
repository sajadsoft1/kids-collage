<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Models\User;
use Livewire\Component;

class UserProfileApp extends Component
{
    public User $user;
    public string $selectedTab = 'users-tab';

    public function render()
    {
        return view('livewire.admin.apps.user-profile-app');
    }
}
