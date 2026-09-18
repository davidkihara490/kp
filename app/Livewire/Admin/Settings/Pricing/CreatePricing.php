<?php

namespace App\Livewire\Admin\Settings\Pricing;

use App\Exports\PricingTemplateExport;
use App\Imports\PricingRowsImport;
use App\Models\Item;
use App\Models\Pricing;
use App\Models\WeightRange;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CreatePricing extends Component
{
    use WithFileUploads;

    public string $type = 'item';
    public $items = [];
    public $weightRanges = [];
    public $zones = [];
    public int $selected_item_id;
    public int $selected_weight_range_id;
    public $pricing_rows = [];
    public $types = ['item', 'weight'];
    public $status;

    // Excel upload
    public $excel_file;
    public $import_errors = [];

    protected $rules = [
        'selected_item_id' => 'required|exists:items,id',
        'pricing_rows.*.source_zone_id' => 'required|exists:zones,id',
        'pricing_rows.*.destination_zone_id' => 'required|exists:zones,id',
        'pricing_rows.*.cost' => 'required|numeric|min:0',
        'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
    ];

    protected $messages = [
        'status.required' => 'Status is required',
        'pricing_rows.*.source_zone_id.required' => 'The source zone is required.',
        'pricing_rows.*.destination_zone_id.required' => 'The destination zone is required.',
        'pricing_rows.*.cost.required' => 'The cost is required.',
        'pricing_rows.*.cost.numeric' => 'The cost must be a number.',
        'pricing_rows.*.cost.min' => 'The cost must be at least 0.',
        'excel_file.mimes' => 'The file must be an Excel or CSV file.',
        'excel_file.max' => 'The file may not be greater than 10MB.',
    ];

    public function mount()
    {
        $this->items = Item::whereDoesntHave('pricing')->get();
        $this->weightRanges = WeightRange::all();
        $this->zones = Zone::all();

        $this->addPricingRow();
    }

    public function addPricingRow()
    {
        $this->pricing_rows[] = [
            'source_zone_id' => '',
            'destination_zone_id' => '',
            'cost' => '',
            'extra' => 0,
            'id' => null,
        ];
    }

    public function removePricingRow($index)
    {
        unset($this->pricing_rows[$index]);
        $this->pricing_rows = array_values($this->pricing_rows);
    }

    /**
     * Download Excel template pre-filled with all zone combinations.
     */
    public function downloadTemplate()
    {
        $this->validateOnly('selected_item_id');

        $zones = $this->zones->map(fn ($z) => ['id' => $z->id, 'name' => $z->name])->toArray();

        $filename = 'pricing_template_item_' . $this->selected_item_id . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new PricingTemplateExport($this->selected_item_id, $zones),
            $filename
        );
    }

    /**
     * Handle Excel file upload and populate pricing_rows.
     */
public function importExcel()
{
    $this->validateOnly('excel_file');
    $this->validateOnly('selected_item_id');

    if (!$this->excel_file) {
        session()->flash('error', 'Please select an Excel file to upload.');
        return;
    }

    $this->import_errors = [];

    try {
        $import = new PricingRowsImport();
        Excel::import($import, $this->excel_file->getRealPath());

        if (empty($import->rows)) {
            session()->flash('error', 'The uploaded file contains no data rows.');
            return;
        }

        $validZones = $this->zones->keyBy('id'); // [id => Zone]
        $rows = [];
        $errors = [];

        foreach ($import->rows as $i => $row) {
            $rowNum = $i + 2; // heading row offset

            // Verify item_id matches
            if (isset($row['item_id']) && (int) $row['item_id'] !== (int) $this->selected_item_id) {
                $errors[] = "Row {$rowNum}: item_id ({$row['item_id']}) does not match selected item.";
                continue;
            }

            $sourceId = (int) $row['source_zone_id'];
            $destId   = (int) $row['destination_zone_id'];

            // Prefer the name from the file for error messages, fallback to ID
            $sourceLabel = $row['source_zone_name'] ?: "ID {$sourceId}";
            $destLabel   = $row['destination_zone_name'] ?: "ID {$destId}";

            if (!$validZones->has($sourceId)) {
                $errors[] = "Row {$rowNum}: source zone '{$sourceLabel}' (ID {$sourceId}) does not exist.";
                continue;
            }
            if (!$validZones->has($destId)) {
                $errors[] = "Row {$rowNum}: destination zone '{$destLabel}' (ID {$destId}) does not exist.";
                continue;
            }

            if (!is_numeric($row['cost']) || $row['cost'] < 0) {
                $errors[] = "Row {$rowNum} ({$sourceLabel} → {$destLabel}): cost must be a non-negative number.";
                continue;
            }

            $rows[] = [
                'source_zone_id'      => $sourceId,
                'destination_zone_id' => $destId,
                'cost'                => (float) $row['cost'],
                'extra'               => (float) ($row['extra'] ?? 0),
                'id'                  => null,
            ];
        }

        if (!empty($errors)) {
            $this->import_errors = $errors;
            session()->flash('error', 'Some rows had errors and were skipped. See details below.');
        }

        if (!empty($rows)) {
            $this->pricing_rows = $rows;
            session()->flash('success', count($rows) . ' pricing rows imported successfully. Review and submit.');
        } else {
            session()->flash('error', 'No valid rows were imported.');
        }

    } catch (\Exception $e) {
        session()->flash('error', 'Import failed: ' . $e->getMessage());
    } finally {
        $this->excel_file = null;
    }
}

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $pricing = Pricing::create([
                'type' => $this->type,
                'item_id' => $this->selected_item_id,
                'min_weight' => 0,
                'max_weight' => 0,
                'status' => $this->status,
            ]);

            $pricing->items()->createMany($this->pricing_rows);

            DB::commit();

            return redirect()->route('admin.pricing.index')->with('success', 'Pricing created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating pricing: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.pricing.create-pricing');
    }
}