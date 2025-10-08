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
        User::factory(10)->create([
            'type'   => UserTypeEnum::TEACHER,
            'status' => true,
        ]);
        User::factory(10)->create([
            'type'   => UserTypeEnum::EMPLOYEE,
            'status' => true,
        ]);

        $users = User::factory(10)->create([
            'type'   => UserTypeEnum::USER,
            'status' => true,
        ]);

        User::factory(10)->create([
            'type'   => UserTypeEnum::PARENT,
            'status' => true,
        ])->each(function (User $user) use ($users) {
            // add relation to user where not have parrent
            $user->children()->attach($users->random()->id);
        });
    }
}
