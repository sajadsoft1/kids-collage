<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        User::factory(15)->create();


        $adminRole = Role::where('name', RoleEnum::ADMIN->value)->first();

        $admin1 = User::find(2);
        $admin1->update([
            'password' => Hash::make('password'),
            'email'    => 'admin1@example.com',
            'mobile'   => '9100000001',
            'name'     => 'admin1',
        ]);
        $admin1->assignRole($adminRole);

        $admin2 = User::find(3);
        $admin2->update([
            'password' => Hash::make('password'),
            'email'    => 'admin2@example.com',
            'mobile'   => '9100000002',
            'name'     => 'admin2',
        ]);
        $admin2->assignRole($adminRole);
        for ($i = 0; $i <5 ; $i++) {
           $user= User::factory()->create([
               'type'=>UserTypeEnum::TEACHER->value
           ]);
            $user->addMedia(public_path('assets/web/img/teacher-1.jpg'))
                ->preservingOriginal()
                ->toMediaCollection('avatar');
       }
    }

}
