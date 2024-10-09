<div class="row">
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h3 class="mb-2"> Most Days at Auction</h3>
            </div>
            <table class="vehicles-table table table-sm table-striped table-hover my-0">
                <thead>
                    <tr>
                        <th>Year Make Model</th>
                        <th>VIN</th>
                        <th>Days in Yard</th>
                    </tr>
                </thead>
                <tbody class="text-end">
                    @if (count($vehicles_with_days_in_yard))
                        @foreach ($vehicles_with_days_in_yard as $vehicle)
                            <tr id="{{ $vehicle->id }}">
                                <td><a href="#" data-toggle="modal"
                                        data-target="#modal-vehicle-detail">{{ $vehicle->description }}</a></td>
                                <td><a href="https://www.copart.com/lot/{{ $vehicle->auction_lot }}"
                                        target="_blank">{{ $vehicle->vin }}</a></td>
                                <td><a href="#" data-toggle="modal"
                                        data-target="#modal-vehicle-detail">{{ $vehicle->meta_value }}</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No vehicles found</td>
                        </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ route('dashboard.index', ['type' => 'days_in_yard']) }}" class="btn btn-primary">View All</a>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h3 class="mb-2">Recently Sold Vehicles</h3>
            </div>
            <table class="vehicles-table table table-sm table-striped table-hover my-0">
                <thead>
                    <tr>
                        <th>Year Make Model</th>
                        <th>VIN</th>
                    </tr>
                </thead>
                <tbody class="text-end">
                    @if (count($vehicles_sold))
                        @foreach ($vehicles_sold as $vehicle)
                            <tr id="{{ $vehicle->id }}">
                                <td><a href="#" data-toggle="modal"
                                        data-target="#modal-vehicle-detail">{{ $vehicle->description }}</a></td>
                                <td><a href="https://www.copart.com/lot/{{ $vehicle->auction_lot }}"
                                        target="_blank">{{ $vehicle->vin }}</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No vehicles found</td>
                        </tr>
                    @endif

                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ route('dashboard.index', ['type' => 'sold']) }}" class="btn btn-primary">View All</a>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
