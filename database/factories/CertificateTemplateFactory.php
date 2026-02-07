<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CertificateTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateTemplateFactory extends Factory
{
    protected $model = CertificateTemplate::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'is_default' => false,
            'layout' => CertificateTemplate::LAYOUT_CLASSIC,
            'header_text' => 'Certificate of Completion',
            'body_text' => 'This is to certify that {{student_name}} has successfully completed {{course_title}}.',
            'footer_text' => 'Issued on {{issue_date}}',
            'institute_name' => $this->faker->company(),
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => ['is_default' => true]);
    }
}
