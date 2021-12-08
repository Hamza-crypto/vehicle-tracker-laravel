@extends('layouts.app')

@section('title', 'Add Order')

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".daterange").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                startDate: moment(),
                locale: {
                    format: "Y-MM-DD"
                }
            });

        });
    </script>

@endsection
@section('content')

        <h1 class="h3 mb-3">Add New Order </h1>

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

                        <form method="post" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="number">VIN</label>
                                        <input
                                            class="form-control form-control-lg"
                                            type="text"
                                            name="vin"
                                            placeholder="Enter VIN"
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
                                        />

                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="make"> Make </label>
                                        <select id="make" class="form-control select2" name="make" data-toggle="select2">

                                            @foreach($makes as $make)
                                                <option value={{ $make->make}}  >{{ $make->make}}</option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="model"> Model </label>
                                        <select id="model" class="form-control select2" name="model" data-toggle="select2">

                                            @foreach($models as $model)
                                                <option value={{ $model->model}}  >{{ $model->model}}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year"> Year </label>
                                        <select id="year" class="form-control select2" name="year" data-toggle="select2">

                                            @foreach($years as $year)
                                                <option value={{$year}}  >{{ $year }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Location</label>
                                        <input
                                            class="form-control form-control-lg"
                                            type="text"
                                            name="location"
                                            placeholder="Enter Location"
                                            value="{{ old('location' )}}"
                                        />

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
                                            value="{{ old('card_number' )}}"
                                        />

                                    </div>

                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Invoice Amount($)</label>
                                        <input
                                            class="form-control form-control-lg"
                                            type="number"
                                            name="invoice_amount"
                                            placeholder="Enter Location"
                                            value="{{ old('location' )}}"
                                        />

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="add" class="btn btn-lg btn-primary">Add New
                                    Card
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>





@endsection
