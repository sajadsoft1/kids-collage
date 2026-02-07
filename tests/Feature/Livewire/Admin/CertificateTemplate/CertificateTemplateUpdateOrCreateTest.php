<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Admin\CertificateTemplate;

use App\Livewire\Admin\Pages\CertificateTemplate\CertificateTemplateUpdateOrCreate;
use App\Models\CertificateTemplate;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class CertificateTemplateUpdateOrCreateTest extends TestCase
{
    public function test_create_page_renders_with_tabs_and_form(): void
    {
        $user = User::query()->first();
        if ( ! $user) {
            $this->markTestSkipped('No user in database.');
        }

        $this->actingAs($user);

        Livewire::test(CertificateTemplateUpdateOrCreate::class, [
            'certificateTemplate' => new CertificateTemplate,
        ])
            ->assertStatus(200)
            ->assertSet('selectedTab', 'basic-tab')
            ->assertSet('layout', 'classic')
            ->assertSee(trans('certificateTemplate.page.index_title'));
    }

    public function test_edit_page_renders_with_existing_data(): void
    {
        $template = CertificateTemplate::factory()->create([
            'title' => 'Test Template',
            'layout' => 'minimal',
        ]);

        $user = User::query()->first();
        if ( ! $user) {
            $this->markTestSkipped('No user in database.');
        }

        $this->actingAs($user);

        Livewire::test(CertificateTemplateUpdateOrCreate::class, [
            'certificateTemplate' => $template,
        ])
            ->assertStatus(200)
            ->assertSet('title', 'Test Template')
            ->assertSet('layout', 'minimal');
    }

    public function test_submit_creates_certificate_template(): void
    {
        $user = User::query()->first();
        if ( ! $user) {
            $this->markTestSkipped('No user in database.');
        }

        $this->actingAs($user);

        $test = Livewire::test(CertificateTemplateUpdateOrCreate::class, [
            'certificateTemplate' => new CertificateTemplate,
        ])
            ->set('title', 'E2E Test Template')
            ->set('layout', 'classic')
            ->set('header_text', 'Hello {{student_name}}')
            ->call('submit');

        $test->assertHasNoErrors();

        $this->assertDatabaseHas('certificate_templates', [
            'title' => 'E2E Test Template',
            'layout' => 'classic',
        ]);
    }
}
