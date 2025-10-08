<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Order\StoreOrderAction;
use Exception;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_financial.php');
        foreach ($data['order'] as $row) {
            $model = StoreOrderAction::run([
                'user_id'      => $row['user_id'],
                'total_amount' => $row['total_amount'],
                'status'       => $row['status'],
            ]);

            try {
                $model->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
