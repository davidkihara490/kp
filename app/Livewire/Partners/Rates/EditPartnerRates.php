<?php

namespace App\Livewire\Partners\Rates;

use App\Exports\PricingTemplateExport;
use App\Imports\PricingRowsImport;
use App\Models\Item;
use App\Models\PartnerRate;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class EditPartnerRates extends Component
{
    use WithFileUploads;

    public $itemId;
    public $item;

    // Existing rate data keyed by zone pair
    public $rates = []; // [source_zone_id][destination_zone_id] => ['cost' => x, 'id' => y, 'status' => z]

    // Zones
    public $sourceZones = [];
    public $destinationZones = [];

    // Bulk apply
    public $bulkSourceZoneId = '';
    public $bulkDestinationZoneId = '';
    public $bulkCost = '';
    public $showBulkApply = false;

    // Search within zones
    public $zoneSearch = '';

    // Show/hide
    public $showOnlyExisting = false;

    // Excel upload
    public $excel_file;
    public $import_errors = [];

    protected $rules = [
        'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
    ];

    protected $messages = [
        'excel_file.mimes' => 'The file must be an Excel or CSV file.',
        'excel_file.max'   => 'The file may not be greater than 10MB.',
    ];

    public function mount($id)
    {
        $this->itemId = $id;
        $this->loadItem();
        $this->loadZones();
        $this->loadRates();
    }

    public function loadItem()
    {
        $this->item = Item::findOrFail($this->itemId);
    }

    public function loadZones()
    {
        $this->sourceZones = Zone::orderBy('name')->get();
        $this->destinationZones = Zone::orderBy('name')->get();
    }

    public function loadRates()
    {
        $partner = Auth::guard('partner')->user()->partner;

        $existing = PartnerRate::where('partner_id', $partner->id)
            ->where('item_id', $this->itemId)
            ->get();

        $this->rates = [];
        foreach ($existing as $rate) {
            if (!isset($this->rates[$rate->source_zone_id])) {
                $this->rates[$rate->source_zone_id] = [];
            }
            $this->rates[$rate->source_zone_id][$rate->destination_zone_id] = [
                'id' => $rate->id,
                'cost' => $rate->cost,
                'status' => $rate->status,
            ];
        }
    }

    public function getRate($sourceZoneId, $destinationZoneId)
    {
        return $this->rates[$sourceZoneId][$destinationZoneId]['cost'] ?? null;
    }

    public function setRate($sourceZoneId, $destinationZoneId, $cost)
    {
        if (!isset($this->rates[$sourceZoneId])) {
            $this->rates[$sourceZoneId] = [];
        }

        if ($cost === '' || $cost === null) {
            unset($this->rates[$sourceZoneId][$destinationZoneId]);
        } else {
            $this->rates[$sourceZoneId][$destinationZoneId] = [
                'id' => $this->rates[$sourceZoneId][$destinationZoneId]['id'] ?? null,
                'cost' => (float) $cost,
                'status' => $this->rates[$sourceZoneId][$destinationZoneId]['status'] ?? 'active',
            ];
        }
    }

    public function updatedBulkCost()
    {
        // No-op, just for reactivity
    }

    public function applyBulkCost()
    {
        $this->validate([
            'bulkCost' => 'required|numeric|min:0',
        ], [
            'bulkCost.required' => 'Please enter a cost.',
            'bulkCost.numeric' => 'Cost must be a number.',
            'bulkCost.min' => 'Cost cannot be negative.',
        ]);

        $cost = (float) $this->bulkCost;

        if ($this->bulkSourceZoneId && $this->bulkDestinationZoneId) {
            $this->setRate($this->bulkSourceZoneId, $this->bulkDestinationZoneId, $cost);
            session()->flash('info', "Cost applied to " . $this->getZoneName($this->bulkSourceZoneId) . " → " . $this->getZoneName($this->bulkDestinationZoneId));
        } elseif ($this->bulkSourceZoneId) {
            foreach ($this->destinationZones as $dest) {
                $this->setRate($this->bulkSourceZoneId, $dest->id, $cost);
            }
            session()->flash('info', "Cost applied to all destinations from " . $this->getZoneName($this->bulkSourceZoneId));
        } elseif ($this->bulkDestinationZoneId) {
            foreach ($this->sourceZones as $src) {
                $this->setRate($src->id, $this->bulkDestinationZoneId, $cost);
            }
            session()->flash('info', "Cost applied to all sources to " . $this->getZoneName($this->bulkDestinationZoneId));
        } else {
            foreach ($this->sourceZones as $src) {
                foreach ($this->destinationZones as $dest) {
                    $this->setRate($src->id, $dest->id, $cost);
                }
            }
            session()->flash('info', "Cost applied to all zone combinations.");
        }

        $this->bulkCost = '';
        $this->showBulkApply = false;
    }

    public function clearAllRates()
    {
        $this->rates = [];
        session()->flash('info', 'All rate values cleared. Click Save to persist.');
    }

    protected function getZoneName($zoneId)
    {
        $zone = $this->sourceZones->firstWhere('id', $zoneId)
            ?? $this->destinationZones->firstWhere('id', $zoneId);
        return $zone ? $zone->name : 'Unknown';
    }

    // ------------------------------------------------------------------
    // EXCEL BULK UPLOAD — mirrors admin CreatePricing
    // ------------------------------------------------------------------

    /**
     * Download a template pre-filled with every zone-pair combination
     * for this partner's current item.
     */
    public function downloadTemplate()
    {
        $zones = $this->sourceZones->map(fn ($z) => ['id' => $z->id, 'name' => $z->name])->toArray();

        $filename = 'partner_rates_template_item_' . $this->itemId . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new PricingTemplateExport($this->itemId, $zones),
            $filename
        );
    }

    /**
     * Import an uploaded Excel/CSV into the in-memory $rates grid.
     *
     * Behaviour:
     *   - item_id in the file MUST match $this->itemId (otherwise that row is skipped)
     *   - source/destination zone ids must exist
     *   - cost must be numeric and >= 0
     *   - imported pairs OVERWRITE the current grid cell
     *   - pairs not present in the file are kept as-is
     *   - cost = 0 in the file is treated as a real 0 value
     *   - `extra` column is ignored for partners
     *   - user must click "Save Rates" to persist
     */
    public function importExcel()
    {
        $this->validateOnly('excel_file');

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

            $validZones = $this->sourceZones->keyBy('id'); // [id => Zone]
            $errors = [];
            $imported = 0;

            foreach ($import->rows as $i => $row) {
                $rowNum = $i + 2; // heading row offset

                // Verify item_id matches this partner's item
                if (isset($row['item_id']) && (int) $row['item_id'] !== (int) $this->itemId) {
                    $errors[] = "Row {$rowNum}: item_id ({$row['item_id']}) does not match the current item (#{$this->itemId}).";
                    continue;
                }

                $sourceId = (int) $row['source_zone_id'];
                $destId   = (int) $row['destination_zone_id'];

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

                // Merge into the grid — overwrite this cell only
                if (!isset($this->rates[$sourceId])) {
                    $this->rates[$sourceId] = [];
                }
                $existing = $this->rates[$sourceId][$destId] ?? null;

                $this->rates[$sourceId][$destId] = [
                    'id'     => $existing['id'] ?? null,
                    'cost'   => (float) $row['cost'],
                    'status' => $existing['status'] ?? 'active',
                ];
                $imported++;
            }

            if (!empty($errors)) {
                $this->import_errors = $errors;
                session()->flash('error', 'Some rows had errors and were skipped. See details below.');
            }

            if ($imported > 0) {
                session()->flash('success', $imported . ' pricing rows imported. Review the grid and click "Save Rates" to persist.');
            } elseif (empty($errors)) {
                session()->flash('error', 'No valid rows were imported.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        } finally {
            $this->excel_file = null;
        }
    }

    // ------------------------------------------------------------------

    public function save()
    {
        $partner = Auth::guard('partner')->user()->partner;

        $this->validate([
            'rates' => 'array',
        ]);

        foreach ($this->rates as $sourceId => $destinations) {
            foreach ($destinations as $destId => $data) {
                if (isset($data['cost']) && (!is_numeric($data['cost']) || $data['cost'] < 0)) {
                    session()->flash('error', "Invalid cost for zone pair {$sourceId} → {$destId}.");
                    return;
                }
            }
        }

        try {
            DB::beginTransaction();

            $existingRates = PartnerRate::where('partner_id', $partner->id)
                ->where('item_id', $this->itemId)
                ->get()
                ->keyBy(fn($r) => $r->source_zone_id . '_' . $r->destination_zone_id);

            $processedKeys = [];

            foreach ($this->rates as $sourceId => $destinations) {
                foreach ($destinations as $destId => $data) {
                    if (!isset($data['cost'])) {
                        continue;
                    }

                    $key = $sourceId . '_' . $destId;
                    $processedKeys[] = $key;

                    PartnerRate::updateOrCreate(
                        [
                            'partner_id' => $partner->id,
                            'item_id' => $this->itemId,
                            'source_zone_id' => $sourceId,
                            'destination_zone_id' => $destId,
                        ],
                        [
                            'cost' => $data['cost'],
                            'status' => 'active',
                        ]
                    );
                }
            }

            foreach ($existingRates as $key => $rate) {
                if (!in_array($key, $processedKeys)) {
                    $rate->delete();
                }
            }

            DB::commit();

            session()->flash('success', 'Rates updated successfully for ' . $this->item->name);
            $this->loadRates();

            $this->dispatch('ratesUpdated', $this->itemId);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update rates: ' . $e->getMessage());
        }
    }

    public function deleteAllRates()
    {
        $partner = Auth::guard('partner')->user()->partner;

        try {
            DB::beginTransaction();

            PartnerRate::where('partner_id', $partner->id)
                ->where('item_id', $this->itemId)
                ->delete();

            DB::commit();

            $this->rates = [];
            session()->flash('success', 'All rates deleted for ' . $this->item->name);
            $this->loadRates();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete rates: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.rates.edit-partner-rates');
    }
}