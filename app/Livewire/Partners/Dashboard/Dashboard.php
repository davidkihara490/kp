<?php

namespace App\Livewire\Partners\Dashboard;

use App\Models\Driver;
use App\Models\Parcel;
use App\Models\ParcelPayout;
use App\Models\ParcelHandlingAssistant;
use App\Models\Partner;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public Partner $partner;
    public $loggedDriver;
    public $loggedUser;
    public $loggedUserType;
    
    // Date range filters
    public $dateRange = 'today'; // today, this_week, this_month, this_year, all_time
    
    public function mount()
    {
        $this->partner = Auth::guard('partner')->user()->partner
            ?? Auth::guard('partner')->user()->driver?->partner
            ?? Auth::guard('partner')->user()->parcelHandlingAssistant?->partner;
        $this->loggedDriver = Auth::guard('partner')->user()->driver;
        $this->loggedUser = Auth::guard('partner')->user();
        $this->loggedUserType = Auth::guard('partner')->user()->user_type;
    }
    
    public function setDateRange($range)
    {
        $this->dateRange = $range;
    }
    
    private function getDateRangeCondition()
    {
        switch ($this->dateRange) {
            case 'today':
                return ['start' => Carbon::today(), 'end' => Carbon::tomorrow()];
            case 'this_week':
                return ['start' => Carbon::now()->startOfWeek(), 'end' => Carbon::now()->endOfWeek()];
            case 'this_month':
                return ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()];
            case 'this_year':
                return ['start' => Carbon::now()->startOfYear(), 'end' => Carbon::now()->endOfYear()];
            case 'all_time':
            default:
                return ['start' => null, 'end' => null];
        }
    }
    
    private function getBaseParcelQuery()
    {
        $query = Parcel::query();
        
        if ($this->loggedUser->partner && $this->loggedUser->partner->partner_type == "transport") {
            $query = Parcel::whereHas('parcelPayouts', function ($query) {
                $query->where('partner_id', $this->loggedUser->partner->id);
            })->with([
                'parcelPayouts' => function ($query) {
                    $query->where('partner_id', $this->loggedUser->partner->id);
                }
            ]);
        } elseif ($this->loggedUser->partner && $this->loggedUser->partner->partner_type == "pickup-dropoff") {
            $query = Parcel::where('sender_partner_id', $this->loggedUser->partner->id)
                ->orWhere('delivery_partner_id', $this->loggedUser->partner->id);
        } elseif ($this->loggedUser->driver && $this->loggedUser->driver) {
            $query = Parcel::where('driver_id', $this->loggedUser->driver->id);
        } elseif ($this->loggedUser->parcelHandlingAssistant) {
            $query = Parcel::where(function ($q) {
                $q->where('pha_id', $this->loggedUser->parcelHandlingAssistant->id)
                    ->orWhere('delivery_partner_id', $this->loggedUser->parcelHandlingAssistant->partner->id);
            });
        }
        
        return $query;
    }
    
    private function getBasePayoutQuery()
    {
        return ParcelPayout::where('partner_id', $this->partner->id);
    }
    
    public function render()
    {
        $dateRange = $this->getDateRangeCondition();
        $parcelQuery = $this->getBaseParcelQuery();
        $payoutQuery = $this->getBasePayoutQuery();
        
        // Apply date filters
        if ($dateRange['start']) {
            $parcelQuery->whereDate('created_at', '>=', $dateRange['start'])
                        ->whereDate('created_at', '<=', $dateRange['end']);
            $payoutQuery->whereDate('created_at', '>=', $dateRange['start'])
                        ->whereDate('created_at', '<=', $dateRange['end']);
        }
        
        // ==================== PARCEL STATISTICS ====================
        
        // Total parcels
        $totalParcels = (clone $parcelQuery)->count();
        
        // Parcels by status
        $parcelsByStatus = [
            'pending' => (clone $parcelQuery)->where('current_status', Parcel::STATUS_PENDING)->count(),
            'assigned' => (clone $parcelQuery)->where('current_status', Parcel::STATUS_ASSIGNED)->count(),
            'in_transit' => (clone $parcelQuery)->where('current_status', Parcel::STATUS_IN_TRANSIT)->count(),
            'delivered' => (clone $parcelQuery)->where('current_status', Parcel::STATUS_DELIVERED)->count(),
            'cancelled' => (clone $parcelQuery)->where('current_status', 'cancelled')->count(),
        ];
        
        // Parcels by payment status
        $parcelsByPaymentStatus = [
            'pending' => (clone $parcelQuery)->where('payment_status', 'pending')->count(),
            'paid' => (clone $parcelQuery)->where('payment_status', 'paid')->count(),
            'failed' => (clone $parcelQuery)->where('payment_status', 'failed')->count(),
        ];
        
        // Active vs Completed
        $activeParcels = (clone $parcelQuery)->whereNotIn('current_status', ['delivered', 'cancelled', 'returned'])->count();
        $completedParcels = (clone $parcelQuery)->where('current_status', Parcel::STATUS_DELIVERED)->count();
        
        // Delivery success rate
        $deliverySuccessRate = $totalParcels > 0 
            ? round(($completedParcels / $totalParcels) * 100, 1) 
            : 0;
        
        // ==================== PAYOUT STATISTICS ====================
        
        // Total earnings
        $totalEarnings = (clone $payoutQuery)->sum('amount');
        
        // Earnings by status
        $pendingEarnings = (clone $payoutQuery)->where('status', 'pending')->sum('amount');
        $completedEarnings = (clone $payoutQuery)->where('status', 'completed')->sum('amount');
        
        // Earnings by type
        $earningsByType = [
            'pickup' => (clone $payoutQuery)->where('type', 'pickup')->sum('amount'),
            'delivery' => (clone $payoutQuery)->where('type', 'delivery')->sum('amount'),
            'transport' => (clone $payoutQuery)->where('type', 'transport')->sum('amount'),
        ];
        
        // ==================== TRENDS ====================
        
        // Last 7 days parcels
        $last7DaysParcels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = (clone $parcelQuery)->whereDate('created_at', $date)->count();
            $last7DaysParcels[] = [
                'date' => $date->format('D'),
                'count' => $count,
            ];
        }
        
        // Last 7 days earnings
        $last7DaysEarnings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $earnings = (clone $payoutQuery)->whereDate('created_at', $date)->sum('amount');
            $last7DaysEarnings[] = [
                'date' => $date->format('D'),
                'earnings' => $earnings,
            ];
        }
        
        // ==================== RECENT ACTIVITY ====================
        
        // Recent parcels (last 5)
        $recentParcels = (clone $parcelQuery)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent payouts (last 5)
        $recentPayouts = (clone $payoutQuery)
            ->with(['parcel'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('livewire.partners.dashboard.dashboard', [
            // Parcel Stats
            'totalParcels' => $totalParcels,
            'parcelsByStatus' => $parcelsByStatus,
            'parcelsByPaymentStatus' => $parcelsByPaymentStatus,
            'activeParcels' => $activeParcels,
            'completedParcels' => $completedParcels,
            'deliverySuccessRate' => $deliverySuccessRate,
            
            // Payout Stats
            'totalEarnings' => $totalEarnings,
            'pendingEarnings' => $pendingEarnings,
            'completedEarnings' => $completedEarnings,
            'earningsByType' => $earningsByType,
            
            // Trends
            'last7DaysParcels' => $last7DaysParcels,
            'last7DaysEarnings' => $last7DaysEarnings,
            
            // Recent Activity
            'recentParcels' => $recentParcels,
            'recentPayouts' => $recentPayouts,
            
            // Date Range
            'currentDateRange' => $this->dateRange,
        ]);
    }
    
    // Helper methods for colors
    public function getStatusColor($status)
    {
        $colors = [
            'pending' => '#f59e0b',
            'assigned' => '#8b5cf6',
            'in_transit' => '#3b82f6',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
        ];
        return $colors[$status] ?? '#6b7280';
    }
    
    public function getPaymentColor($status)
    {
        $colors = [
            'pending' => '#f59e0b',
            'paid' => '#10b981',
            'failed' => '#ef4444',
        ];
        return $colors[$status] ?? '#6b7280';
    }
    
    public function getPayoutColor($status)
    {
        $colors = [
            'pending' => '#f59e0b',
            'approved' => '#3b82f6',
            'completed' => '#10b981',
            'cancelled' => '#ef4444',
        ];
        return $colors[$status] ?? '#6b7280';
    }
    
    public function getTypeIcon($type)
    {
        $icons = [
            'pickup' => 'bi-box-arrow-in-down',
            'delivery' => 'bi-box-arrow-up',
            'transport' => 'bi-truck',
        ];
        return $icons[$type] ?? 'bi-cash';
    }
}