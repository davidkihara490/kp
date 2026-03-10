<?php

namespace App\Livewire\Partners\Fleet;

use App\Models\Fleet;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditFleet extends Component
{
    public $fleet;
    
    // Form fields
    public $registration_number;
    public $make;
    public $model;
    public $vehicle_type;
    public $color;
    
    public $transport_partner_id;
    public $current_driver_id;
    
    public $status;
    public $is_available;
    
    public $odometer_reading;
    
    public $registration_expiry;
    public $insurance_expiry;
    public $last_service_date;
    public $next_service_date;
    
    public $year;
    public $capacity;
    public $fuel_type;
    
    public $notes;
    
    // For form
    public $drivers = [];
    public $original_registration_number;

    public function rules()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        
        return [
            'registration_number' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    if ($value !== $this->original_registration_number && 
                        Fleet::where('registration_number', $value)->exists()) {
                        $fail('This registration number is already registered.');
                    }
                }
            ],
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|in:van,truck,pickup,motorcycle,car',
            'color' => 'nullable|string|max:50',
            
            'current_driver_id' => 'nullable|exists:drivers,id',
            
            'status' => 'required|in:active,maintenance,inactive,accident',
            'is_available' => 'boolean',
            
            
            'registration_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            
            'year' => 'nullable|integer|min:1900|max:' . $nextYear,
            'capacity' => 'nullable|numeric|min:0',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'registration_number.unique' => 'This registration number is already registered.',
        'year.max' => 'Year cannot be in the future.',
    ];

    public function mount($id)
    {

        $this->fleet = Fleet::findOrFail($id);
        $this->loadFleetData();
        $this->loadFormData();
    }

    public function loadFleetData()
    {
        $this->registration_number = $this->fleet->registration_number;
        $this->original_registration_number = $this->fleet->registration_number;
        $this->make = $this->fleet->make;
        $this->model = $this->fleet->model;
        $this->vehicle_type = $this->fleet->fleet_type ?? $this->fleet->vehicle_type;
        $this->color = $this->fleet->color;
        
        $this->current_driver_id = $this->fleet->current_driver_id;
        
        $this->status = $this->fleet->status;
        $this->is_available = $this->fleet->is_available ?? true;
        
        $this->odometer_reading = $this->fleet->odometer_reading;
        
        $this->registration_expiry = $this->fleet->registration_expiry ? 
            $this->fleet->registration_expiry->format('Y-m-d') : null;
        $this->insurance_expiry = $this->fleet->insurance_expiry ? 
            $this->fleet->insurance_expiry->format('Y-m-d') : null;
        $this->last_service_date = $this->fleet->last_service_date ? 
            $this->fleet->last_service_date->format('Y-m-d') : null;
        $this->next_service_date = $this->fleet->next_service_date ? 
            $this->fleet->next_service_date->format('Y-m-d') : null;
        
        $this->year = $this->fleet->year;
        $this->capacity = $this->fleet->capacity;
        $this->fuel_type = $this->fleet->fuel_type;
        
        $this->notes = $this->fleet->notes;
    }

    public function loadFormData()
    {
        $this->drivers = Auth::guard('partner')->user()->drivers ?? [];
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->fleet->update([
                'registration_number' => $this->registration_number,
                'make' => $this->make,
                'model' => $this->model,
                'fleet_type' => $this->vehicle_type,
                'color' => $this->color,
                
                'current_driver_id' => $this->current_driver_id,
                
                'status' => $this->status,
                'is_available' => $this->is_available,
                
                'odometer_reading' => $this->odometer_reading,
                
                'registration_expiry' => $this->registration_expiry,
                'insurance_expiry' => $this->insurance_expiry,
                'last_service_date' => $this->last_service_date,
                'next_service_date' => $this->next_service_date,
                
                'year' => $this->year,
                'capacity' => $this->capacity,
                'fuel_type' => $this->fuel_type,
                
                'notes' => $this->notes,
            ]);

            DB::commit();
            
            return redirect()->route('partners.fleet.index')->with('success', 'Fleet vehicle updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update fleet: ' . $e->getMessage());
        }
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        $this->submit();
    }

    public function render()
    {
        return view('livewire.partners.fleet.edit-fleet');
    }
}