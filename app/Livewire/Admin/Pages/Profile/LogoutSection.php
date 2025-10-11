<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Profile;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Livewire\Component;
use Mary\Traits\Toast;

/**
 * Component for logout section with session information
 */
class LogoutSection extends Component
{
    use Toast;

    public User $user;

    /** Mount the component */
    public function mount(?User $user = null): void
    {
        if ($user?->id) {
            $this->user = $user;
        } else {
            $authUser = auth()->user();
            if ( ! $authUser instanceof User) {
                abort(401, 'Unauthorized');
            }
            $this->user = $authUser;
        }
    }

    /** Get current session information */
    public function getCurrentSessionInfo(): array
    {
        $agent = new Agent;

        return [
            'ip'               => request()->ip(),
            'user_agent'       => request()->userAgent(),
            'browser'          => $agent->browser(),
            'browser_version'  => $agent->version($agent->browser()),
            'platform'         => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'device'           => $this->getDeviceType($agent),
            'is_mobile'        => $agent->isMobile(),
            'is_tablet'        => $agent->isTablet(),
            'is_desktop'       => $agent->isDesktop(),
            'session_id'       => Session::getId(),
            'last_activity'    => now(),
        ];
    }

    /** Get device type */
    private function getDeviceType(Agent $agent): string
    {
        if ($agent->isTablet()) {
            return 'تبلت';
        }

        if ($agent->isMobile()) {
            return 'موبایل';
        }

        return 'دسکتاپ';
    }

    /** Get device icon */
    public function getDeviceIcon(): string
    {
        $agent = new Agent;

        if ($agent->isTablet()) {
            return 'o-device-tablet';
        }

        if ($agent->isMobile()) {
            return 'o-device-phone-mobile';
        }

        return 'o-computer-desktop';
    }

    /** Get browser icon */
    public function getBrowserIcon(string $browser): string
    {
        return match (strtolower($browser)) {
            'chrome'  => 'o-globe-alt',
            'firefox' => 'o-globe-alt',
            'safari'  => 'o-globe-alt',
            'edge'    => 'o-globe-alt',
            'opera'   => 'o-globe-alt',
            default   => 'o-globe-alt',
        };
    }

    /** Logout from current session */
    public function logout(): void
    {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->success(
            title: 'شما با موفقیت خارج شدید',
            description: 'به امید دیدار مجدد',
            redirectTo: localized_route('login')
        );
    }

    /** Logout from all devices */
    public function logoutFromAllDevices(): void
    {
        // Invalidate all other sessions
        Auth::logoutOtherDevices(request('current_password'));

        $this->success(
            title: 'شما از همه دستگاه‌ها خارج شدید',
            description: 'تنها session فعلی شما فعال است',
            timeout: 3000
        );
    }

    /** Clear browser cache confirmation */
    public function suggestClearCache(): void
    {
        $this->info(
            title: 'پاکسازی Cache مرورگر',
            description: 'برای امنیت بیشتر، Cache مرورگر خود را پاک کنید',
            timeout: 5000
        );
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.pages.profile.logout-section', [
            'sessionInfo' => $this->getCurrentSessionInfo(),
        ]);
    }
}
