@extends('layouts.app')

@section('title', 'SOLD')

@section('content')
<div class="row">
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
