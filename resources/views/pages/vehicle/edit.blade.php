@extends('layouts.app')

@section('title', 'Edit Vehicle')

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".daterange").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: "Y-MM-DD"
                }
            });

        });
    </script>

@endsection
@section('content')

    <h1 class="h3 mb-3">Edit Vehicle </h1>

    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif
                    @if(session('error'))
                        <x-alert type="danger">{{ session('error') }}</x-alert>
                    @endif
                    @if(session('warning'))
                        <x-alert type="warning">{{ session('warning') }}</x-alert>
                    @endif

                    <form method="post" action="{{ route('vehicles.update', $vehicle->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="number">VIN</label>
                                    <input
                                        class="form-control form-control-lg"
                                        type="text"
                                        name="vin"
                                        placeholder="Enter VIN"
                                        value="{{ old('vin', $vehicle->vin) }}"
                                    />
                                </div>

                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="number">Invoice Date</label>

                                    <input
                                        class="form-control form-control-lg daterange"
                                        type="text"
                                        name="invoice_date"
                                        placeholder="Enter card number"
                                        value="{{ old('invoice_date', $vehicle->invoice_date) }}"
                                    />

                                </div>

                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="number">Lot</label>
                                    <input
                                        class="form-control form-control-lg"
                                        type="text"
                                        name="lot"
                                        placeholder="Enter Lot number"
                                        value="{{ old('lot', $vehicle->lot) }}"

                                    />

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-4">

                                <div class="form-group">
                                    <label for="make"> Make </label>
                                    <select id="make" class="form-control select2" name="make" data-toggle="select2">
                                        <option value="" > Select </option>
                                        @foreach($makes as $make)
                                            <option value={{ $make->make}} @if(old('make', $vehicle->make) == $make->make) selected @endif  >{{ $make->make}}</option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="model"> Model </label>
                                    <select id="model" class="form-control select2" name="model" data-toggle="select2">
                                        <option value="" > Select </option>
                                        @foreach($models as $model)
                                            <option value={{ $model->model}} @if(old('model', $vehicle->model) == $model->model) selected @endif >{{ $model->model}}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="year"> Year </label>
                                    <select id="year" class="form-control select2" name="year" data-toggle="select2">

                                        @foreach($years as $year)
                                            <option value={{$year}} @if(old('year', $vehicle->year) == $year) selected @endif >{{ $year }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-4">

                                <div class="form-group">
                                    <label for="location"> Location </label>
                                    <select id="location" class="form-control select2" name="location" data-toggle="select2">

                                        @foreach($locations as $location)
                                            <option value="{{ $location->meta_value }}" @if(old('location' , $vehicle->location->meta_value ?? '') == $location->meta_value) selected @endif >{{ $location->meta_value }}</option>
                                        @endforeach

                                            @foreach($locations2 as $location)
                                            <option value="{{ $location->location }}" @if(old('location' , $vehicle->location->meta_value ?? '') == $location->location) selected @endif >{{ $location->location }}</option>
                                        @endforeach


                                    </select>

                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="number">Pick up Date</label>

                                    <input
                                        class="form-control form-control-lg daterange"
                                        type="text"
                                        name="pickup_date"
                                        placeholder="Enter card number"
                                        value="{{ old('pickup_date' , $vehicle->date_pickup->meta_value ?? '')}}"

                                    />

                                </div>

                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label for="year">Invoice Amount($)</label>
                                    <input
                                        class="form-control form-control-lg"
                                        type="text"
                                        name="invoice_amount"
                                        placeholder="Enter Location"
                                        value="{{ old('invoice_amount' , $vehicle->invoice_amount->meta_value ?? '')}}"
                                    />

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" id="add" class="btn btn-lg btn-primary">Update
                                Vehicle
                            </button>
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>





@endsection
