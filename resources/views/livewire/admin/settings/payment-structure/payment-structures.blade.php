<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Payment Structures
            </h3>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Delivery Type</th>
                        <th>Tax</th>
                        <th>PickUp Partner</th>
                        <th>DropOff Partner</th>
                        <th>Transport Partner</th>
                        <th>Platform</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentStructures as $paymentStructure)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $paymentStructure->delivery_type }}</td>
                        <td>{{ $paymentStructure->tax_percentage }}</td>
                        <td>{{ $paymentStructure->pick_up_drop_off_partner_percentage }}</td>
                        <td>{{ $paymentStructure->pick_up_drop_off_partner_percentage }}</td>
                        <td>{{ $paymentStructure->transport_partner_percentage }}</td>
                        <td>{{ $paymentStructure->platform_percentage }}</td>
                        <td>
                            <a href="{{ route('admin.payment-structure.edit', $paymentStructure->id) }}" class="btn btn-sm btn-info">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">No data was found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Delivery Type</th>
                        <th>Tax</th>
                        <th>PickUp Partner</th>
                        <th>DropOff Partner</th>
                        <th>Transport Partner</th>
                        <th>Platform</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>