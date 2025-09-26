<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Payment\StorePaymentAction;
use Exception;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['payment'] as $row) {
            $model = StorePaymentAction::run([
                'user_id'        => $row['user_id'],
                'order_id'       => $row['order_id'],
                'amount'         => $row['amount'],
                'type'           => $row['type'],
                'status'         => $row['status'],
                'transaction_id' => $row['transaction_id'],
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
