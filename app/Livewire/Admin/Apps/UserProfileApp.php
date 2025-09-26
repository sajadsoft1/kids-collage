<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class UserProfileApp extends Component
{
    public User $user;
    public string $selectedTab = 'users-tab';

    public function mount(int|null $user = null): void
    {
        if ($user){
            $this->user = User::find($user);
        }else {
            $this->user = auth()->user();
        }
    }

    public function render(): View
    {
        return view('livewire.admin.apps.user-profile-app');
    }
}
