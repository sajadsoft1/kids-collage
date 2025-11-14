<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

use App\Models\Slider;

class SliderPermissions extends BasePermissions
{
    public const All = 'Slider.All';
    public const Index = 'Slider.Index';
    public const Show = 'Slider.Show';
    public const Store = 'Slider.Store';
    public const Update = 'Slider.Update';
    public const Toggle = 'Slider.Toggle';
    public const Delete = 'Slider.Delete';
    public const Restore = 'Slider.Restore';

    protected string $model = Slider::class;
}
