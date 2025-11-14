<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Client\StoreClientAction;
use App\Actions\ContactUs\StoreContactUsAction;
use App\Actions\SocialMedia\StoreSocialMediaAction;
use App\Actions\Teammate\StoreTeammateAction;
use Exception;
use Illuminate\Database\Seeder;

/**
 * Karno Team Seeder
 *
 * Seeds team and contact-related data from karno data files including
 * clients, teammates, contact us forms, and social media links.
 */
class KarnoTeamSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('👥 Seeding Karno Team Data...');

        $data = require database_path('seeders/data/karno_team.php');

        // Seed clients
        $this->seedClients($data['client']);

        // Seed teammates
        $this->seedTeammates($data['teammate']);

        // Seed contact us
        $this->seedContactUs($data['contact_us']);

        // Seed social media
        $this->seedSocialMedia($data['social_media']);

        $this->command->info('✅ Karno Team Data seeded successfully!');
    }

    /** Seed clients from karno data */
    private function seedClients(array $clients): void
    {
        $this->command->info('🏢 Seeding clients...');

        foreach ($clients as $row) {
            $client = StoreClientAction::run([
                'title' => $row['title'],
                'languages' => $row['languages'],
            ]);

            // Add images to the clients
            try {
                $client->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }

        $this->command->info('✅ Clients seeded successfully!');
    }

    /** Seed teammates from karno data */
    private function seedTeammates(array $teammates): void
    {
        $this->command->info('👨‍💼 Seeding teammates...');

        foreach ($teammates as $row) {
            $teammate = StoreTeammateAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'birthday' => $row['birthday'],
                'position' => $row['position'],
                'languages' => $row['languages'],
            ]);

            // Add images to the teammates
            try {
                $teammate->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }

        $this->command->info('✅ Teammates seeded successfully!');
    }

    /** Seed contact us from karno data */
    private function seedContactUs(array $contactUs): void
    {
        $this->command->info('📞 Seeding contact us...');

        foreach ($contactUs as $row) {
            StoreContactUsAction::run([
                'name' => $row['name'],
                'email' => $row['email'],
                'mobile' => $row['mobile'],
                'comment' => $row['comment'],
            ]);
        }

        $this->command->info('✅ Contact us seeded successfully!');
    }

    /** Seed social media from karno data */
    private function seedSocialMedia(array $socialMedia): void
    {
        $this->command->info('📱 Seeding social media...');

        foreach ($socialMedia as $row) {
            try {
                $social = StoreSocialMediaAction::run([
                    'title' => $row['title'],
                    'link' => $row['link'],
                    'ordering' => $row['ordering'],
                    'position' => $row['position'],
                    'image' => $row['path'],
                ]);
            } catch (Exception $e) {
                // If social media creation fails, skip it
                $this->command->warn('Social media creation failed, skipping...');

                continue;
            }
        }

        $this->command->info('✅ Social media seeded successfully!');
    }
}
