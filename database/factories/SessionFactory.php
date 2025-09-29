<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\Course;
use App\Models\Session;
use App\Models\SessionTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        return [
            'course_id'           => Course::factory(),
            'session_template_id' => SessionTemplate::factory(),
            'date'                => $this->faker->optional()->dateTimeBetween('+1 day', '+2 months'),
            'start_time'          => '09:00',
            'end_time'            => '10:30',
            'room_id'             => null,
            'meeting_link'        => null,
            'recording_link'      => null,
            'status'              => SessionStatus::PLANNED->value,
            'session_type'        => SessionType::IN_PERSON->value,
        ];
    }
}
