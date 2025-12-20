<?php

use App\Livewire\Network\GetStarted;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(GetStarted::class)
        ->assertStatus(200);
});
