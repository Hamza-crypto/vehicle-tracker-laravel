@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')

    <h1 class="h3 mb-3"> Vehicle Details </h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    @if(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif

    <div class="row">

        <div class="col-12">

            <div class="card">

                <table class="table">
                    <thead>
                    <tr>
                        <th style="width:40%;">Key</th>
                        <th style="width:25%">Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vehicle->toArray() as $key => $value)
                        <tr>
                            <td>{{ strtoupper($key) }}</td>
                            <td>{{ $value }}</td>
                        </tr>

                    @endforeach

                    @if(sizeof($vehicle_metas) > 0)
                        @foreach($vehicle_metas as $vehicle_meta)
                            <tr>
                                <td>{{ strtoupper($vehicle_meta->meta_key) }}</td>
                                <td>{{ $vehicle_meta->meta_value }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">No more data found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
