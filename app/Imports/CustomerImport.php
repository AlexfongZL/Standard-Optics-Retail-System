<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Customers;
use App\Models\Degrees;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $customer = Customers::create([
                            'name' => $row[0] ?? null,
                            'ic_passport_num' => $row[1] ?? null,
                            'telephone_num' => $row[2] ?? null,
                            'address' => $row[3] ?? null,
                            'remarks' => $row[6] ?? null,
                        ]);

            $degree = Degrees::create([
                        'customers_id' => $customer->id,
                        'left_eye_degree' => $row[4] ?? null,
                        'right_eye_degree' => $row[5] ?? null,
                    ]);
        }
    }
}
