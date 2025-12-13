<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\User\StoreUserAction;
use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        StoreUserAction::run([
            'name' => 'سجاد',
            'family' => 'اسکندریان',
            'type' => UserTypeEnum::TEACHER,
            'status' => true,
            'email' => 'teacher@gmail.com',
            'mobile' => '09100000001',
            'password' => 'password',
        ]);

        StoreUserAction::run([
            'name' => 'حسین',
            'family' => 'ابروی',
            'type' => UserTypeEnum::EMPLOYEE,
            'status' => true,
            'email' => 'employee@gmail.com',
            'mobile' => '09100000002',
            'password' => 'password',
        ]);

        $user = StoreUserAction::run([
            'name' => 'سجاد',
            'family' => 'خدابخشی',
            'type' => UserTypeEnum::USER,
            'status' => true,
            'email' => 'user@gmail.com',
            'mobile' => '09100000003',
            'password' => 'password',
        ]);

        $parent = StoreUserAction::run([
            'name' => 'احمد',
            'family' => 'دهستانی',
            'type' => UserTypeEnum::PARENT,
            'status' => true,
            'email' => 'parent@gmail.com',
            'mobile' => '09100000004',
            'password' => 'password',
        ]);

        // add relation to user where not have parent
        $parent->children()->attach($user->id);
    }
}
