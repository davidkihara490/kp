<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PricingTemplateExport implements FromArray, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    public function __construct(
        protected int $itemId,
        protected array $zones, // [['id' => 1, 'name' => 'Nairobi'], ...]
    ) {}

    public function array(): array
    {
        $data = [];

        foreach ($this->zones as $source) {
            foreach ($this->zones as $destination) {
                $data[] = [
                    $this->itemId,
                    $source['id'],
                    $source['name'],
                    $destination['id'],
                    $destination['name'],
                    0, // cost
                    0, // extra
                ];
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'item_id',
            'source_zone_id',
            'source_zone_name',
            'destination_zone_id',
            'destination_zone_name',
            'cost',
            'extra',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 16,
            'C' => 22,
            'D' => 20,
            'E' => 24,
            'F' => 10,
            'G' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Pricing Template';
    }
}