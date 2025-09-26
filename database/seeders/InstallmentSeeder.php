<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Installment\StoreInstallmentAction;
use Exception;
use Illuminate\Database\Seeder;

class InstallmentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['installment'] as $row) {
            $model = StoreInstallmentAction::run([
                'payment_id'     => $row['payment_id'],
                'amount'         => $row['amount'],
                'due_date'       => $row['due_date'],
                'method'         => $row['method'],
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
