<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Payment Structures
            </h3>
            <a href="{{ route('admin.payment-structure.create') }}" class="btn btn-success btn-sm float-right">New</a>
        </div>
        <div class="card-body">
            @include('components.alerts.response-alerts')
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Delivery Type</th>
                        <!-- <th>Tax(%)</th> -->
                        <th>Pick up / Drop off Partner</th>
                        <!-- <th>Transport Partner(%)</th> -->
                        <th>Platform Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentStructures as $paymentStructure)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $paymentStructure->item->name ?? '—' }}</td>
                        <td>
                            @if ($paymentStructure->delivery_type === 'direct')
                                <span class="badge badge-info">Agent to agent</span>
                            @elseif ($paymentStructure->delivery_type === 'warehouse_split')
                                <span class="badge badge-warning">Warehouse</span>
                            @else
                                {{ $paymentStructure->delivery_type }}
                            @endif
                        </td>
                        <!-- <td>{{ $paymentStructure->tax_percentage }}</td> -->
                        <td>
                            {{ $paymentStructure->pick_up_drop_off_partner_amount }}
                            @if ($paymentStructure->pick_up_drop_off_partner_type === 'percentage')
                                <small class="text-muted">(%)</small>
                            @else
                                <small class="text-muted">(fixed)</small>
                            @endif
                        </td>
                        <!-- <td>{{ $paymentStructure->transport_partner_percentage }}</td> -->
                        <td>
                            @if ($paymentStructure->platform_percentage > 0)
                                {{ $paymentStructure->platform_percentage }}
                                <small class="text-muted">(%)</small>
                            @elseif ($paymentStructure->platform_fixed_amount > 0)
                                {{ $paymentStructure->platform_fixed_amount }}
                                <small class="text-muted">(fixed)</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.payment-structure.edit', $paymentStructure->id) }}"
                                class="btn btn-sm btn-info">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger"
                                wire:click="delete({{ $paymentStructure->id }})"
                                wire:confirm="Are you sure you want to delete this payment structure?"
                                wire:loading.attr="disabled"
                                wire:target="delete({{ $paymentStructure->id }})">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No data was found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Delivery Type</th>
                        <!-- <th>Tax(%)</th> -->
                        <th>Pick up / Drop off Partner</th>
                        <!-- <th>Transport Partner(%)</th> -->
                        <th>Platform Fee</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>