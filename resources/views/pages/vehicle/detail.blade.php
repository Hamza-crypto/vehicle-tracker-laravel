@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('scripts')
    <script>
        $(document).ready(function () {

            $(".daterange").each(function(index) {
                var startDate = $(this).val();
                $(this).daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    startDate: startDate,
                    locale: {
                        format: "Y-MM-DD"
                    }
                });
            });
        });

    </script>

@endsection

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
                    <form method="post" action="{{ route('vehicles.update', $vehicle->id) }}">
                        @csrf
                        @method('PUT')
                        @foreach($vehicle->toArray() as $key => $value)
                            @if($key == 'updated_at')
                                @continue
                            @endif
                            <tr>
                                <td>{{ strtoupper($key) }}</td>

                                <td>
                                    <input type="text" class="form-control @if(in_array($key, ['left_location', 'date_paid'])) daterange @endif"
                                           name="{{ $key }}"
                                           value="{{ $value }}"
                                           @if(in_array($key, ['id', 'created_at'])) readonly @endif

                                    >
                                </td>
                            </tr>

                        @endforeach
                        @foreach($meta_keys as $key)
                            <tr>
                                <td>{{ strtoupper($key) }}</td>
                                <td>
                                    <input type="text" class="form-control"
                                           name="{{ $key }}"
                                           value="{{ $vehicle_metas[$key] ?? '' }}"
                                    >
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" id="add" class="btn btn-lg btn-primary">Update
                                    Vehicle
                                </button>
                            </td>

                        </tr>
                    </form>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
