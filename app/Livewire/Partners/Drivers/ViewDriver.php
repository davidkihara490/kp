<?php

namespace App\Livewire\Partners\Drivers;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\MaintenanceLog;
use Livewire\Component;
use Livewire\WithPagination;

class ViewDriver extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $driverId;
    public $driver;
    public $activeTab = 'overview';
    public $tripStatusFilter = '';
    public $ratingFilter = '';
    public $dateRange = '';

    public function mount($id)
    {
        $this->driverId = $id;
        $this->driver = Driver::with([
            'partner',
            // 'fleet',
            // 'county',
            // 'subCounty',
            'user',
            // 'trips' => function($query) {
            //     $query->orderBy('created_at', 'desc')->take(5);
            // },
            // 'deliveries' => function($query) {
            //     $query->orderBy('created_at', 'desc')->take(5);
            // }
        ])->findOrFail($id);
    }

    public function render()
    {
        $data = [
            'driver' => $this->driver,
            'recentTrips' => $this->getRecentTrips(),
            // 'recentDeliveries' => $this->getRecentDeliveries(),
            'performanceStats' => $this->getPerformanceStats(),
            'documents' => $this->getDocuments(),
            'tripStatuses' => [
                '' => 'All',
                'scheduled' => 'Scheduled',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ],
            'ratingOptions' => [
                '' => 'All Ratings',
                '5' => '5 Stars',
                '4' => '4 Stars & Above',
                '3' => '3 Stars & Above',
                '2' => '2 Stars & Above',
                '1' => '1 Star',
            ],
        ];

        return view('livewire.partners.drivers.view-driver', $data);
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    protected function getRecentTrips()
    {
        // Replace with actual relationship when available
        return collect([]);
        
        // Uncomment when Trip model and relationship is ready:
        /*
        $query = Trip::where('driver_id', $this->driverId)
            ->orderBy('created_at', 'desc');

        if ($this->tripStatusFilter) {
            $query->where('status', $this->tripStatusFilter);
        }

        if ($this->dateRange) {
            $dates = explode(' to ', $this->dateRange);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [trim($dates[0]), trim($dates[1])]);
            }
        }

        return $query->paginate(10, ['*'], 'trips_page');
        */
    }

    protected function getRecentDeliveries()
    {
        // Replace with actual relationship when available
        return collect([]);
        
        // Uncomment when Delivery model and relationship is ready:
        /*
        $query = Delivery::where('driver_id', $this->driverId)
            ->orderBy('created_at', 'desc');

        if ($this->ratingFilter) {
            $query->where('rating', '>=', $this->ratingFilter);
        }

        return $query->paginate(10, ['*'], 'deliveries_page');
        */
    }

    protected function getPerformanceStats()
    {
        // Calculate performance statistics
        $totalDeliveries = $this->driver->successful_deliveries + 
                          $this->driver->failed_deliveries + 
                          $this->driver->cancelled_deliveries;
        
        $successRate = $totalDeliveries > 0 
            ? round(($this->driver->successful_deliveries / $totalDeliveries) * 100, 1)
            : 0;
        
        $onTimeRate = $this->driver->on_time_delivery_rate ?? 0;
        
        $averageDeliveryTime = $this->driver->average_delivery_time 
            ? round($this->driver->average_delivery_time / 60, 1) . ' mins' 
            : 'N/A';
        
        $totalDistance = $this->driver->total_distance_covered 
            ? number_format($this->driver->total_distance_covered, 1) . ' km'
            : '0 km';

        return [
            'total_deliveries' => $totalDeliveries,
            'successful_deliveries' => $this->driver->successful_deliveries,
            'failed_deliveries' => $this->driver->failed_deliveries,
            'cancelled_deliveries' => $this->driver->cancelled_deliveries,
            'success_rate' => $successRate,
            'on_time_rate' => $onTimeRate,
            'average_delivery_time' => $averageDeliveryTime,
            'total_distance' => $totalDistance,
            'rating' => $this->driver->rating ?? 0,
        ];
    }

    protected function getDocuments()
    {
        return $this->driver->documents ?? [];
    }

    public function markAsAvailable()
    {
        $this->driver->markAsAvailable();
        $this->driver->refresh();
        session()->flash('success', 'Driver marked as available.');
    }

    public function markAsUnavailable()
    {
        $this->driver->markAsUnavailable();
        $this->driver->refresh();
        session()->flash('warning', 'Driver marked as unavailable.');
    }

    public function updateStatus($status)
    {
        $this->driver->update([
            'status' => $status,
            'is_available' => $status === 'active',
        ]);
        $this->driver->refresh();
        session()->flash('success', 'Driver status updated to ' . ucfirst($status));
    }

    public function assignFleet($fleetId)
    {
        $this->driver->update(['fleet_id' => $fleetId]);
        $this->driver->refresh();
        session()->flash('success', 'Driver assigned to fleet.');
    }

    public function unassignFleet()
    {
        $this->driver->update(['fleet_id' => null]);
        $this->driver->refresh();
        session()->flash('info', 'Driver unassigned from fleet.');
    }

    public function exportDocuments()
    {
        // Generate PDF or export documents
        session()->flash('info', 'Document export initiated. You will receive a download link shortly.');
    }

    public function calculateLicenseValidity()
    {
        if (!$this->driver->driving_license_expiry_date) {
            return ['status' => 'unknown', 'text' => 'Not Set', 'color' => 'secondary'];
        }
        
        $expiryDate = $this->driver->driving_license_expiry_date;
        $daysUntilExpiry = now()->diffInDays($expiryDate, false);
        
        if ($daysUntilExpiry < 0) {
            return [
                'status' => 'expired',
                'text' => 'Expired ' . abs($daysUntilExpiry) . ' days ago',
                'color' => 'danger',
                'icon' => 'exclamation-triangle'
            ];
        } elseif ($daysUntilExpiry <= 30) {
            return [
                'status' => 'expiring',
                'text' => 'Expires in ' . $daysUntilExpiry . ' days',
                'color' => 'warning',
                'icon' => 'clock'
            ];
        } else {
            return [
                'status' => 'valid',
                'text' => 'Valid for ' . $daysUntilExpiry . ' days',
                'color' => 'success',
                'icon' => 'check-circle'
            ];
        }
    }

    public function calculateMedicalCertificateValidity()
    {
        if (!$this->driver->has_medical_certificate || !$this->driver->medical_certificate_expiry) {
            return ['status' => 'none', 'text' => 'No Certificate', 'color' => 'secondary'];
        }
        
        $expiryDate = $this->driver->medical_certificate_expiry;
        $daysUntilExpiry = now()->diffInDays($expiryDate, false);
        
        if ($daysUntilExpiry < 0) {
            return [
                'status' => 'expired',
                'text' => 'Expired ' . abs($daysUntilExpiry) . ' days ago',
                'color' => 'danger',
                'icon' => 'exclamation-triangle'
            ];
        } elseif ($daysUntilExpiry <= 30) {
            return [
                'status' => 'expiring',
                'text' => 'Expires in ' . $daysUntilExpiry . ' days',
                'color' => 'warning',
                'icon' => 'clock'
            ];
        } else {
            return [
                'status' => 'valid',
                'text' => 'Valid for ' . $daysUntilExpiry . ' days',
                'color' => 'success',
                'icon' => 'check-circle'
            ];
        }
    }

    public function calculateFirstAidValidity()
    {
        if (!$this->driver->has_first_aid_training || !$this->driver->first_aid_training_expiry) {
            return ['status' => 'none', 'text' => 'No Training', 'color' => 'secondary'];
        }
        
        $expiryDate = $this->driver->first_aid_training_expiry;
        $daysUntilExpiry = now()->diffInDays($expiryDate, false);
        
        if ($daysUntilExpiry < 0) {
            return [
                'status' => 'expired',
                'text' => 'Expired ' . abs($daysUntilExpiry) . ' days ago',
                'color' => 'danger',
                'icon' => 'exclamation-triangle'
            ];
        } elseif ($daysUntilExpiry <= 30) {
            return [
                'status' => 'expiring',
                'text' => 'Expires in ' . $daysUntilExpiry . ' days',
                'color' => 'warning',
                'icon' => 'clock'
            ];
        } else {
            return [
                'status' => 'valid',
                'text' => 'Valid for ' . $daysUntilExpiry . ' days',
                'color' => 'success',
                'icon' => 'check-circle'
            ];
        }
    }

    public function getAge()
    {
        return $this->driver->date_of_birth ? $this->driver->date_of_birth->age : 'N/A';
    }

    public function getEmploymentDuration()
    {
        if (!$this->driver->employment_date) {
            return 'N/A';
        }
        
        $employmentDate = $this->driver->employment_date;
        return $employmentDate->diffForHumans(now(), true);
    }
}