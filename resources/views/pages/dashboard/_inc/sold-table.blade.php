<div class="row">
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">
                    Days in Yard
                </h5>


            </div>
            <table class="table table-sm table-striped my-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>VIN</th>
                    <th>Days in Yard</th>
                </tr>
                </thead>
                <tbody class="text-end">
                    @if(count($vehicles_with_days_in_yard))
                        @foreach ($vehicles_with_days_in_yard as $vehicle)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $vehicle->vin }}</td>

                            <td>{{ $vehicle->meta_value }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No vehicles found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">
                    Recently Sold Vehicles
                </h5>
            </div>
            <table class="table table-sm table-striped my-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>VIN</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody class="text-end">
                    @if(count($vehicles_sold))
                        @foreach ($vehicles_sold as $vehicle)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $vehicle->vin }}</td>

                            <td>{{ $vehicle->meta_value }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No vehicles found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



