@extends('layouts.app')

@section('title', 'Recent Updated')

@section('content')
<div class="col-12 col-lg-6 d-flex">
    <div class="card flex-fill">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Updated Vehicles</h5>
        </div>
        <table class="vehicles-table table table-sm table-striped my-0">
            <thead>
                <tr>
                    <th>Year Make Model</th>
                    <th>VIN</th>
                    <th>Days in Yard</th>
                </tr>
            </thead>
            <tbody class="text-end">
                @if (count($last_30_updated))
                    @foreach ($last_30_updated as $vehicle)
                        <tr id="{{ $vehicle->id }}">
                            <td>{{$vehicle->description }}</td>
                            <td>
                                @if ($vehicle->auction_lot)
                                    <a href="https://www.copart.com/lot/{{ $vehicle->auction_lot }}"
                                        target="_blank">{{ $vehicle->vin }}</a>
                                @else
                                    {{ $vehicle->vin }}
                                @endif
                            </td>
                            <td data-toggle="tooltip" data-placement="top"
                                title="{{ $vehicle->updated_at }}">
                                {{ $vehicle->human_readable_format }}
                            </td>
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


@endsection
