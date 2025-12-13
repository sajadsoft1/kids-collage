<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\User\StoreUserAction;
use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        StoreUserAction::run([
            'name' => 'سوپر',
            'family' => 'ادمین',
            'mobile' => '09100000000',
            'password' => 'password',
            'type' => UserTypeEnum::EMPLOYEE->value,
            'email' => 'developer@gmail.com',
            'status' => true,
            'gender' => GenderEnum::MALE->value,
            'birthday' => '1990-01-01',
            'national_code' => '1234567890',
            'national_card_image' => 'national_card_image.jpg',
            'birth_certificate_image' => 'birth_certificate_image.jpg',
            'roles' => [Role::where('name', RoleEnum::ADMIN->value)->first()->id],
        ]);
    }
}
