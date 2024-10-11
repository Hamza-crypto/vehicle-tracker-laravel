@extends('layouts.app')

@section('title', 'Notes')

@section('content')

<div class="row">
    <div class="col-12 col-lg-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Vehicles With Notes (Recent at top)</h5>
            </div>
            <table class="vehicles-table table table-sm table-striped my-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Year Make Model</th>
                        <th>VIN</th>
                        <th>Note</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody class="text-end">
                    @php
                        use Carbon\Carbon;
                    @endphp
                    @if (count($vehicles_with_notes))

                        @foreach ($vehicles_with_notes as $vehicle)
                            <tr id="{{ $vehicle->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vehicle->description }}</td>
                                <td>{{ $vehicle->vin }}</td>
                                <td>
                                    {{ $vehicle->note_content }}
                                </td>
                                <td data-toggle="tooltip" data-placement="top"
                                    title="{{ Carbon::parse($vehicle->note_updated_at) }}">
                                    {{ Carbon::parse($vehicle->note_updated_at)->diffForHumans() }}</td>

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
