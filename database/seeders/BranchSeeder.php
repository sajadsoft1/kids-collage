<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BranchStatusEnum;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Create default branch if it doesn't exist
        $defaultBranch = Branch::firstOrCreate(
            ['code' => 'default'],
            [
                'name' => 'شعبه اصلی',
                'status' => BranchStatusEnum::ACTIVE,
                'is_default' => true,
                'settings' => [],
            ]
        );

        // Ensure only one default branch exists
        Branch::where('id', '!=', $defaultBranch->id)
            ->update(['is_default' => false]);
    }
}
