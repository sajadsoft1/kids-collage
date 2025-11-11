<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\ContactUs\StoreContactUsAction;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['contact_us'] as $row) {
            StoreContactUsAction::run([
                'name' => $row['name'],
                'email' => $row['email'],
                'mobile' => $row['mobile'],
                'comment' => $row['comment'],
                'subject' => $row['subject'] ?? 'test',
            ]);
        }
    }
}
