<?php

namespace App\Livewire\Partners\Parcels;

use App\Models\County;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Parcel;
use App\Models\Partner;
use App\Services\SMSService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Parcels extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url]
    public $search = '';

    #[Url]
    public $statusFilter = '';

    #[Url]
    public $customerFilter = '';

    #[Url]
    public $driverFilter = '';

    #[Url]
    public $transportPartnerFilter = '';

    #[Url]
    public $pickupPartnerFilter = '';

    #[Url]
    public $deliveryPartnerFilter = '';

    #[Url]
    public $countyFilter = '';

    #[Url]
    public $parcelTypeFilter = '';

    #[Url]
    public $paymentStatusFilter = '';

    #[Url]
    public $dateFrom = '';

    #[Url]
    public $dateTo = '';

    #[Url]
    public $sortField = 'created_at';

    #[Url]
    public $sortDirection = 'desc';

    public $showDeleteModal = false;
    public $parcelToDelete = null;

    public $showBulkActions = false;
    public $selectedParcels = [];
    public $selectAll = false;

    public $showAssignDriverModal = false;
    public $selectedParcelForAssignment = null;
    public $selectedDriver = '';

    public $showStatusUpdateModal = false;
    public $selectedParcelForStatusUpdate = null;
    public $newStatus = '';

    public $partnerType;
    public $partner;
    public $loggedDriver;
    public $loggedUser;
    public $loggedUserType;

    public $selectedParcelForDriver;
    public $selectedDriverId;
    public $driverSearch = '';
    public $availableDrivers = [];
    public $notifyDriver = true;
    public $estimatedDeliveryDate;
    public $assignmentNotes = '';

    public $pointsCount = 0;

    protected $listeners = [
        'refreshParcels' => '$refresh',
        'driverAssigned' => '$refresh',
        'modalClosed' => 'resetModalState',
    ];

    /**
     * Fields that are safe to sort from the UI.
     */
    protected array $sortableFields = [
        'parcel_id',
        'current_status',
        'payment_status',
        'created_at',
        'updated_at',
        'total_amount',
    ];

    public function mount()
    {
        $this->loggedUser = Auth::guard('partner')->user();
        $this->loggedDriver = $this->loggedUser?->driver;

        $this->partner =
            $this->loggedUser?->partner
            ?? $this->loggedUser?->driver?->partner
            ?? $this->loggedUser?->parcelHandlingAssistant?->partner;

        $this->partnerType = $this->partner?->partner_type;
        $this->loggedUserType = $this->loggedUser?->user_type;

        $this->estimatedDeliveryDate = now()->addDay()->format('Y-m-d');
        $this->pointsCount = $this->partner?->pickUpAndDropOffPoints()->count() ?? 0;

        // Prevent invalid sort values coming from URL query parameters.
        if (! in_array($this->sortField, $this->sortableFields, true)) {
            $this->sortField = 'created_at';
        }

        if (! in_array($this->sortDirection, ['asc', 'desc'], true)) {
            $this->sortDirection = 'desc';
        }
    }

    /**
     * Reset pagination and selection whenever one of the parcel filters changes.
     */
    public function updated($property, $value)
    {
        $filters = [
            'search',
            'statusFilter',
            'customerFilter',
            'driverFilter',
            'transportPartnerFilter',
            'pickupPartnerFilter',
            'deliveryPartnerFilter',
            'countyFilter',
            'parcelTypeFilter',
            'paymentStatusFilter',
            'dateFrom',
            'dateTo',
        ];

        if (in_array($property, $filters, true)) {
            $this->resetPage();
            $this->clearSelection();
        }
    }

    /**
     * The Blade already calls this button. The filters are live, so this method
     * mainly validates the date range and resets pagination.
     */
    public function applyDateRange(): void
    {
        $this->validate([
            'dateFrom' => ['nullable', 'date'],
            'dateTo' => ['nullable', 'date', 'after_or_equal:dateFrom'],
        ], [
            'dateTo.after_or_equal' => 'The To date must be the same as or later than the From date.',
        ]);

        $this->resetPage();
        $this->clearSelection();
    }

    /**
     * Restrict parcels to the records the logged-in partner user is allowed to see.
     */
    protected function roleScopedQuery(): Builder
    {
        $query = Parcel::query();

        if (! $this->loggedUser) {
            return $query->whereRaw('1 = 0');
        }

        // Transport partner
        if ($this->loggedUser->partner?->partner_type === 'transport') {
            return $query->whereHas('parcelPayouts', function (Builder $q) {
                $q->where('partner_id', $this->loggedUser->partner->id);
            });
        }

        // Pickup / drop-off partner owner
        if ($this->loggedUser->partner?->partner_type === 'pickup-dropoff') {
            $partnerId = $this->loggedUser->partner->id;

            return $query->where(function (Builder $q) use ($partnerId) {
                $q->where('sender_partner_id', $partnerId)
                    ->orWhere('delivery_partner_id', $partnerId);
            });
        }

        // Driver
        if ($this->loggedUser->driver) {
            return $query->where('driver_id', $this->loggedUser->driver->id);
        }

        // Parcel handling assistant
        if ($this->loggedUser->parcelHandlingAssistant) {
            $pha = $this->loggedUser->parcelHandlingAssistant;
            $partnerId = $pha->partner_id ?? $pha->partner?->id;

            return $query->where(function (Builder $q) use ($pha, $partnerId) {
                $q->where('pha_id', $pha->id);

                if ($partnerId) {
                    $q->orWhere('sender_partner_id', $partnerId)
                        ->orWhere('delivery_partner_id', $partnerId);
                }
            });
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * Apply every active filter in one place.
     *
     * This method is reused by:
     * - the table
     * - stats
     * - select all
     * - bulk actions
     */
    protected function filteredQuery(): Builder
    {
        $query = $this->roleScopedQuery();

        $search = trim((string) $this->search);

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $like = '%' . $search . '%';

                $q->where('parcel_id', 'like', $like)
                    ->orWhere('sender_name', 'like', $like)
                    ->orWhere('sender_phone', 'like', $like)
                    ->orWhere('sender_email', 'like', $like)
                    ->orWhere('receiver_name', 'like', $like)
                    ->orWhere('receiver_phone', 'like', $like)
                    ->orWhere('receiver_email', 'like', $like)
                    ->orWhere('content_description', 'like', $like);
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('current_status', $this->statusFilter);
        }

        if ($this->paymentStatusFilter !== '') {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        if ($this->parcelTypeFilter !== '') {
            $query->where('parcel_type', $this->parcelTypeFilter);
        }

        if ($this->customerFilter !== '') {
            $query->where('customer_id', $this->customerFilter);
        }

        if ($this->driverFilter !== '') {
            $query->where('driver_id', $this->driverFilter);
        }

        /*
         * A transport partner is linked to the parcel through parcel payouts
         * in your existing transport-partner visibility logic.
         */
        if ($this->transportPartnerFilter !== '') {
            $query->whereHas('parcelPayouts', function (Builder $q) {
                $q->where('partner_id', $this->transportPartnerFilter)
                    ->where('type', 'transport');
            });
        }

        if ($this->pickupPartnerFilter !== '') {
            $query->where('sender_partner_id', $this->pickupPartnerFilter);
        }

        if ($this->deliveryPartnerFilter !== '') {
            $query->where('delivery_partner_id', $this->deliveryPartnerFilter);
        }

        if ($this->countyFilter !== '') {
            $query->where(function (Builder $q) {
                $q->where('sender_county_id', $this->countyFilter)
                    ->orWhere('receiver_county_id', $this->countyFilter);
            });
        }

        if ($this->dateFrom !== '') {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo !== '') {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    public function render()
    {
        $filtered = $this->filteredQuery();

        /*
         * Eager load relations used repeatedly by the Blade to avoid N+1 queries.
         * Remove any relation here only if your Parcel model uses a different name.
         */
        $parcels = (clone $filtered)
            ->with([
                'payments',
                'statuses.driver',
                'parcelPayouts',
                // 'driver',
                'senderTown',
                'receiverTown',
                'senderPickUpDropOffPoint',
                'deliveryStation',
                'warehouse',
                'transportPartner',
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        /*
         * The Blade statistics currently use the filtered collection, so all
         * cards stay consistent with the filters currently applied.
         */
        $statParcels = (clone $filtered)
            ->with(['parcelPayouts'])
            ->get();

        $totalParcels = $statParcels->count();

        $pendingParcels = $statParcels
            ->whereIn('current_status', [
                Parcel::STATUS_CREATED,
                Parcel::STATUS_BOOKED,
                Parcel::STATUS_ACCEPTED,
                Parcel::STATUS_ASSIGNED,
                Parcel::STATUS_PENDING,
            ])
            ->count();

        $inTransitParcels = $statParcels
            ->whereIn('current_status', [
                Parcel::STATUS_IN_TRANSIT,
                Parcel::STATUS_WAREHOUSE,
                Parcel::STATUS_ARRIVED_AT_DESTINATION,
            ])
            ->count();

        $deliveredParcels = $statParcels
            ->whereIn('current_status', [
                Parcel::STATUS_PICKED,
                Parcel::STATUS_DELIVERED,
            ])
            ->count();

        /*
         * Only show customers and drivers that occur in parcels available to
         * the logged-in user. This keeps filter lists relevant and smaller.
         */
        $roleQuery = $this->roleScopedQuery();

        $customerIds = (clone $roleQuery)
            ->whereNotNull('customer_id')
            ->distinct()
            ->pluck('customer_id');

        $driverIds = (clone $roleQuery)
            ->whereNotNull('driver_id')
            ->distinct()
            ->pluck('driver_id');

        return view('livewire.partners.parcels.parcels', [
            'parcels' => $parcels,

            'customers' => Customer::query()
                ->whereIn('id', $customerIds)
                ->orderBy('name')
                ->get(),

            'drivers' => Driver::query()
                ->whereIn('id', $driverIds)
                ->orderBy('first_name')
                ->get(),

            'transportPartners' => Partner::query()
                ->where('partner_type', 'transport')
                ->orderBy('company_name')
                ->get(),

            'pickupPartners' => Partner::query()
                ->where('partner_type', 'pickup-dropoff')
                ->orderBy('company_name')
                ->get(),

            'deliveryPartners' => Partner::query()
                ->where('partner_type', 'pickup-dropoff')
                ->orderBy('company_name')
                ->get(),

            'counties' => County::orderBy('name')->get(),

            'parcelTypes' => [
                '' => 'All Types',
                'document' => 'Document',
                'package' => 'Package',
                'envelope' => 'Envelope',
                'box' => 'Box',
                'pallet' => 'Pallet',
                'other' => 'Other',
            ],

            /*
             * These statuses match the parcel workflow used elsewhere in your
             * Karibu Parcels components.
             */
            'statuses' => [
                '' => 'All Status',
                Parcel::STATUS_CREATED => 'Created',
                Parcel::STATUS_BOOKED => 'Booked',
                Parcel::STATUS_ACCEPTED => 'Accepted',
                Parcel::STATUS_ASSIGNED => 'Assigned',
                Parcel::STATUS_IN_TRANSIT => 'In Transit',
                Parcel::STATUS_PENDING => 'Pending',
                Parcel::STATUS_WAREHOUSE => 'Warehouse',
                Parcel::STATUS_ARRIVED_AT_DESTINATION => 'Arrived at Destination',
                Parcel::STATUS_PICKED => 'Picked',
                Parcel::STATUS_DELIVERED => 'Delivered',
                Parcel::STATUS_FAILED => 'Failed',
                Parcel::STATUS_RETURNED => 'Returned',
            ],

            'paymentStatuses' => [
                '' => 'All Payment Status',
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
                'partially_paid' => 'Partially Paid',
            ],

            'totalParcels' => $totalParcels,
            'pendingParcels' => $pendingParcels,
            'inTransitParcels' => $inTransitParcels,
            'deliveredParcels' => $deliveredParcels,
            'statParcels' => $statParcels,
        ]);
    }

    public function sortBy($field)
    {
        if (! in_array($field, $this->sortableFields, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'statusFilter',
            'customerFilter',
            'driverFilter',
            'transportPartnerFilter',
            'pickupPartnerFilter',
            'deliveryPartnerFilter',
            'countyFilter',
            'parcelTypeFilter',
            'paymentStatusFilter',
            'dateFrom',
            'dateTo',
        ]);

        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';

        $this->clearSelection();
        $this->resetPage();
    }

    protected function clearSelection(): void
    {
        $this->selectedParcels = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            /*
             * Select only parcels currently available through the user's role
             * and active filters. This avoids selecting unrelated parcels.
             */
            $this->selectedParcels = $this->filteredQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedParcels = [];
        }

        $this->showBulkActions = count($this->selectedParcels) > 0;
    }

    public function updatedSelectedParcels()
    {
        $this->selectAll = false;
        $this->showBulkActions = count($this->selectedParcels) > 0;
    }

    public function hasActiveFilters(): bool
    {
        return trim((string) $this->search) !== ''
            || $this->statusFilter !== ''
            || $this->customerFilter !== ''
            || $this->driverFilter !== ''
            || $this->transportPartnerFilter !== ''
            || $this->pickupPartnerFilter !== ''
            || $this->deliveryPartnerFilter !== ''
            || $this->countyFilter !== ''
            || $this->parcelTypeFilter !== ''
            || $this->paymentStatusFilter !== ''
            || $this->dateFrom !== ''
            || $this->dateTo !== '';
    }

    public function showAssignDriver($parcelId)
    {
        /*
         * Use the role-scoped query so a manipulated parcel ID cannot open a
         * parcel outside the logged-in user's permitted list.
         */
        $this->selectedParcelForDriver = $this->roleScopedQuery()->findOrFail($parcelId);

        $this->selectedDriverId = null;
        $this->driverSearch = '';
        $this->estimatedDeliveryDate = now()->addDay()->format('Y-m-d');
        $this->assignmentNotes = '';

        $this->loadAvailableDrivers();

        $this->showAssignDriverModal = true;
        $this->dispatch('openAssignDriverModal');
    }

    public function loadAvailableDrivers()
    {
        if (! $this->partner) {
            $this->availableDrivers = collect();
            return;
        }

        $query = Driver::query()
            ->where('partner_id', $this->partner->id)
            ->where('status', 'active')
            ->where('is_available', true);

        $search = trim((string) $this->driverSearch);

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $like = '%' . $search . '%';

                $q->where('first_name', 'like', $like)
                    ->orWhere('second_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('phone_number', 'like', $like)
                    ->orWhere('email', 'like', $like);
            });
        }

        $this->availableDrivers = $query
            ->orderBy('first_name')
            ->get();
    }

    public function updatedDriverSearch()
    {
        $this->loadAvailableDrivers();
    }

    public function selectDriver($driverId)
    {
        $this->selectedDriverId = $driverId;
    }

    public function assignDriver(SMSService $smsService)
    {
        $this->validate([
            'selectedDriverId' => ['required', 'exists:drivers,id'],
        ]);

        if (! $this->selectedParcelForDriver) {
            return;
        }

        try {
            $driver = Driver::query()
                ->whereKey($this->selectedDriverId)
                ->where('partner_id', $this->partner?->id)
                ->where('status', 'active')
                ->firstOrFail();

            DB::transaction(function () use ($driver, $smsService) {
                $parcelCode = $this->selectedParcelForDriver->generateDeliveryOtp();

                $this->selectedParcelForDriver->updateParcelStatus(
                    Parcel::STATUS_ASSIGNED,
                    $this->selectedParcelForDriver->sender_pick_up_drop_off_point_id,
                    Auth::guard('partner')->id(),
                    current_user_type(),
                    'Parcel assigned to driver: ' . $driver->full_name,
                    $driver->id,
                    $parcelCode,
                );

                $this->selectedParcelForDriver->update([
                    'current_status' => Parcel::STATUS_ASSIGNED,
                    'driver_id' => $driver->id,
                ]);

                try {
                    $smsService->sendDriverAssignmentSMS(
                        formatKenyaNumber($driver->phone_number ?? $driver->phone),
                        $driver->first_name ?? $driver->full_name,
                        $this->selectedParcelForDriver->parcel_id,
                        $this->selectedParcelForDriver->senderTown->name,
                        $this->selectedParcelForDriver->receiverTown->name,
                        $parcelCode
                    );
                } catch (\Throwable $e) {
                    Log::error('Failed to send SMS to driver', [
                        'driver_id' => $driver->id,
                        'parcel_id' => $this->selectedParcelForDriver->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            });

            session()->flash('success', 'Parcel assigned to driver successfully.');

            $this->dispatch('closeAssignDriverModal');
            $this->resetModalState();
            $this->dispatch('refreshParcels');
        } catch (\Throwable $e) {
            Log::error('Failed to assign driver', [
                'parcel_id' => $this->selectedParcelForDriver?->id,
                'driver_id' => $this->selectedDriverId,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to assign driver: ' . $e->getMessage());
        }
    }

    public function resetModalState()
    {
        $this->showAssignDriverModal = false;
        $this->selectedParcelForDriver = null;
        $this->selectedDriverId = null;
        $this->driverSearch = '';
        $this->availableDrivers = [];
        $this->notifyDriver = true;
        $this->estimatedDeliveryDate = now()->addDay()->format('Y-m-d');
        $this->assignmentNotes = '';
    }

    public function confirmDelete($parcelId)
    {
        $this->parcelToDelete = $this->roleScopedQuery()->findOrFail($parcelId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (! $this->parcelToDelete) {
            return;
        }

        $parcel = $this->roleScopedQuery()->findOrFail($this->parcelToDelete->id);
        $parcelNumber = $parcel->parcel_id;

        $parcel->delete();

        session()->flash('success', "Parcel #{$parcelNumber} deleted successfully.");

        $this->showDeleteModal = false;
        $this->parcelToDelete = null;
        $this->resetPage();
    }

    public function markAsPickedUp($parcelId)
    {
        $parcel = $this->roleScopedQuery()->findOrFail($parcelId);

        $parcel->markAsPickedUp(
            Auth::guard('partner')->id(),
            'Marked as picked up from partner panel'
        );

        session()->flash('success', "Parcel #{$parcel->parcel_id} marked as picked up.");
    }

    public function markAsDelivered($parcelId)
    {
        $parcel = $this->roleScopedQuery()->findOrFail($parcelId);
        $parcel->markAsDelivered(Auth::guard('partner')->id());

        session()->flash('success', "Parcel #{$parcel->parcel_id} marked as delivered.");
    }

    public function showUpdateStatus($parcelId)
    {
        $this->selectedParcelForStatusUpdate = $this->roleScopedQuery()->findOrFail($parcelId);
        $this->newStatus = $this->selectedParcelForStatusUpdate->current_status;
        $this->showStatusUpdateModal = true;
    }

    public function updateStatus()
    {
        $allowedStatuses = [
            Parcel::STATUS_CREATED,
            Parcel::STATUS_BOOKED,
            Parcel::STATUS_ACCEPTED,
            Parcel::STATUS_ASSIGNED,
            Parcel::STATUS_IN_TRANSIT,
            Parcel::STATUS_PENDING,
            Parcel::STATUS_WAREHOUSE,
            Parcel::STATUS_ARRIVED_AT_DESTINATION,
            Parcel::STATUS_PICKED,
            Parcel::STATUS_DELIVERED,
            Parcel::STATUS_FAILED,
            Parcel::STATUS_RETURNED,
        ];

        $this->validate([
            'newStatus' => ['required', 'in:' . implode(',', $allowedStatuses)],
        ]);

        if (! $this->selectedParcelForStatusUpdate) {
            return;
        }

        $parcel = $this->roleScopedQuery()
            ->findOrFail($this->selectedParcelForStatusUpdate->id);

        $oldStatus = $parcel->current_status;

        $parcel->updateStatus(
            $this->newStatus,
            'Status updated from partner panel',
            Auth::guard('partner')->id()
        );

        session()->flash(
            'success',
            "Parcel status updated from {$oldStatus} to {$this->newStatus}"
        );

        $this->showStatusUpdateModal = false;
        $this->selectedParcelForStatusUpdate = null;
        $this->newStatus = '';
    }

    public function bulkDelete()
    {
        if (count($this->selectedParcels) === 0) {
            return;
        }

        $parcels = $this->roleScopedQuery()
            ->whereIn('id', $this->selectedParcels)
            ->get();

        foreach ($parcels as $parcel) {
            $parcel->delete();
        }

        session()->flash('success', $parcels->count() . ' parcels deleted.');
        $this->clearSelection();
        $this->resetPage();
    }

    public function bulkMarkAsDelivered()
    {
        if (count($this->selectedParcels) === 0) {
            return;
        }

        $count = 0;

        DB::transaction(function () use (&$count) {
            $parcels = $this->roleScopedQuery()
                ->whereIn('id', $this->selectedParcels)
                ->get();

            foreach ($parcels as $parcel) {
                $parcel->markAsDelivered(Auth::guard('partner')->id());
                $count++;
            }
        });

        session()->flash('success', $count . ' parcels marked as delivered.');
        $this->clearSelection();
    }

    public function bulkCancel()
    {
        if (count($this->selectedParcels) === 0) {
            return;
        }

        $cancelled = 0;

        DB::transaction(function () use (&$cancelled) {
            $parcels = $this->roleScopedQuery()
                ->whereIn('id', $this->selectedParcels)
                ->get();

            foreach ($parcels as $parcel) {
                if ($parcel->canBeCancelled()) {
                    $parcel->cancel(
                        'Bulk cancellation from partner panel',
                        Auth::guard('partner')->id()
                    );
                    $cancelled++;
                }
            }
        });

        session()->flash('warning', $cancelled . ' parcels cancelled.');
        $this->clearSelection();
    }

    public function getStatusBadge($status)
    {
        $badges = [
            Parcel::STATUS_CREATED => ['color' => '#6b7280', 'text' => 'Created', 'icon' => 'bi-plus-circle'],
            Parcel::STATUS_BOOKED => ['color' => '#3b82f6', 'text' => 'Booked', 'icon' => 'bi-journal-check'],
            Parcel::STATUS_ACCEPTED => ['color' => '#10b981', 'text' => 'Accepted', 'icon' => 'bi-check-circle'],
            Parcel::STATUS_ASSIGNED => ['color' => '#f59e0b', 'text' => 'Assigned', 'icon' => 'bi-person-check'],
            Parcel::STATUS_IN_TRANSIT => ['color' => '#8b5cf6', 'text' => 'In Transit', 'icon' => 'bi-truck'],
            Parcel::STATUS_PENDING => ['color' => '#6b7280', 'text' => 'Pending', 'icon' => 'bi-clock'],
            Parcel::STATUS_WAREHOUSE => ['color' => '#3b82f6', 'text' => 'Warehouse', 'icon' => 'bi-building'],
            Parcel::STATUS_ARRIVED_AT_DESTINATION => ['color' => '#0ea5e9', 'text' => 'Arrived at Destination', 'icon' => 'bi-geo-alt'],
            Parcel::STATUS_PICKED => ['color' => '#14b8a6', 'text' => 'Picked', 'icon' => 'bi-box-arrow-up'],
            Parcel::STATUS_DELIVERED => ['color' => '#10b981', 'text' => 'Delivered', 'icon' => 'bi-check-circle-fill'],
            Parcel::STATUS_FAILED => ['color' => '#ef4444', 'text' => 'Failed', 'icon' => 'bi-x-circle'],
            Parcel::STATUS_RETURNED => ['color' => '#f59e0b', 'text' => 'Returned', 'icon' => 'bi-arrow-return-left'],
        ];

        return $badges[$status]
            ?? [
                'color' => '#6b7280',
                'text' => ucfirst(str_replace('_', ' ', (string) $status)),
                'icon' => 'bi-question-circle',
            ];
    }

    public function getPaymentStatusBadge($paymentStatus)
    {
        $badges = [
            'pending' => ['color' => '#f59e0b', 'text' => 'Pending', 'icon' => 'bi-clock'],
            'paid' => ['color' => '#10b981', 'text' => 'Paid', 'icon' => 'bi-check-circle'],
            'failed' => ['color' => '#ef4444', 'text' => 'Failed', 'icon' => 'bi-x-circle'],
            'refunded' => ['color' => '#3b82f6', 'text' => 'Refunded', 'icon' => 'bi-arrow-counterclockwise'],
            'partially_paid' => ['color' => '#8b5cf6', 'text' => 'Partially Paid', 'icon' => 'bi-percent'],
        ];

        return $badges[$paymentStatus]
            ?? [
                'color' => '#6b7280',
                'text' => ucfirst(str_replace('_', ' ', (string) $paymentStatus)),
                'icon' => 'bi-question-circle',
            ];
    }

    public function getParcelTypeBadge($parcelType)
    {
        $badges = [
            'document' => ['color' => '#3b82f6', 'text' => 'Document', 'icon' => 'bi-file-text'],
            'package' => ['color' => '#8b5cf6', 'text' => 'Package', 'icon' => 'bi-box'],
            'envelope' => ['color' => '#6b7280', 'text' => 'Envelope', 'icon' => 'bi-envelope'],
            'box' => ['color' => '#f59e0b', 'text' => 'Box', 'icon' => 'bi-box-seam'],
            'pallet' => ['color' => '#1f2937', 'text' => 'Pallet', 'icon' => 'bi-palette'],
            'other' => ['color' => '#6b7280', 'text' => 'Other', 'icon' => 'bi-question-circle'],
        ];

        return $badges[$parcelType]
            ?? ['color' => '#6b7280', 'text' => ucfirst((string) $parcelType), 'icon' => 'bi-question-circle'];
    }

    public function getPackageTypeBadge($packageType)
    {
        $badges = [
            'regular' => ['color' => '#6b7280', 'text' => 'Regular', 'icon' => 'bi-box'],
            'fragile' => ['color' => '#f59e0b', 'text' => 'Fragile', 'icon' => 'bi-exclamation-triangle'],
            'perishable' => ['color' => '#3b82f6', 'text' => 'Perishable', 'icon' => 'bi-snow'],
            'valuable' => ['color' => '#10b981', 'text' => 'Valuable', 'icon' => 'bi-gem'],
            'hazardous' => ['color' => '#ef4444', 'text' => 'Hazardous', 'icon' => 'bi-exclamation-octagon'],
            'oversized' => ['color' => '#1f2937', 'text' => 'Oversized', 'icon' => 'bi-aspect-ratio'],
        ];

        return $badges[$packageType]
            ?? ['color' => '#6b7280', 'text' => ucfirst((string) $packageType), 'icon' => 'bi-box'];
    }
}
