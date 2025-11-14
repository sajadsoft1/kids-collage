<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        User::factory(1)->create([
            'type' => UserTypeEnum::TEACHER,
            'status' => true,
            'email' => 'teacher@gmail.com',
            'mobile' => '09100000001',
        ]);
        User::factory(1)->create([
            'type' => UserTypeEnum::EMPLOYEE,
            'status' => true,
            'email' => 'employee@gmail.com',
            'mobile' => '09100000002',
        ]);

        $users = User::factory(1)->create([
            'type' => UserTypeEnum::USER,
            'status' => true,
            'email' => 'user@gmail.com',
            'mobile' => '09100000003',
        ]);

        User::factory(1)->create([
            'type' => UserTypeEnum::PARENT,
            'status' => true,
            'email' => 'parent@gmail.com',
            'mobile' => '09100000004',
        ])->each(function (User $user) use ($users) {
            // add relation to user where not have parrent
            $user->children()->attach($users->random()->id);
        });
    }
}
