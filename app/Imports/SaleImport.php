<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Sales;
use App\Imports\DateTime;

class SaleImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $date = Carbon::createFromFormat('d/m/Y', $row[0]);

            $sale = Sales::create([
                            // 'created_at' => $row[0] ?? null,
                            // 'updated_at' => $row[0] ?? null,
                            'fully_paid_date' => $date ?? null,
                            'description' => $row[1] ?? null,
                            'price' => $row[2] ?? null,
                            'is_paid' => 1,
                        ]);
        }
    }
}
