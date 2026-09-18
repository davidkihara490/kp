<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class PricingRowsImport implements ToCollection, WithHeadingRow
{
    public array $rows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->rows[] = [
                'item_id'                => $row['item_id'] ?? null,
                'source_zone_id'         => $row['source_zone_id'] ?? null,
                'source_zone_name'       => $row['source_zone_name'] ?? null,
                'destination_zone_id'    => $row['destination_zone_id'] ?? null,
                'destination_zone_name'  => $row['destination_zone_name'] ?? null,
                'cost'                   => $row['cost'] ?? 0,
                'extra'                  => $row['extra'] ?? 0,
            ];
        }
    }
}