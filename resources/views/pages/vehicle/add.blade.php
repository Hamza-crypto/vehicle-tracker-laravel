@extends('layouts.app')

@section('title', 'Add Order')

@section('scripts')
    <script>
        $('#add').click(function(){
            // alert('sss');
             $('#loader').toggleClass('d-none');
        });

        $(document).ready(function () {
            $("input[name=\"invoice_date\"]").daterangepicker({
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

                        <form method="post" action="{{ route('orders.store') }}" enctype="multipart/form-data">
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
                                            class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                            type="text"
                                            name="invoice_date"
                                            placeholder="Enter card number"
                                            value="{{ old('card_number' )}}"
                                        />

                                        @error('card_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="number">Lot</label>
                                        <input
                                            class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                            type="number"
                                            name="card_number"
                                            placeholder="Enter Lot number"
                                            value="{{ old('card_number' )}}"
                                        />

                                        @error('card_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
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
                                            class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                            type="text"
                                            name="invoice_date"
                                            placeholder="Enter card number"
                                            value="{{ old('card_number' )}}"
                                        />

                                        @error('card_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Invoice Amount($)</label>
                                        <input
                                            class="form-control form-control-lg"
                                            type="number"
                                            name="location"
                                            placeholder="Enter Location"
                                            value="{{ old('location' )}}"
                                        />

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
                                            class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                            type="text"
                                            name="invoice_date"
                                            placeholder="Enter card number"
                                            value="{{ old('card_number' )}}"
                                        />

                                        @error('card_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Invoice Amount($)</label>
                                        <input
                                            class="form-control form-control-lg"
                                            type="number"
                                            name="location"
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

        <div id="loader" class="row text-center d-none">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Checking your card</h5>
                    <h6 class="card-subtitle text-muted">Please wait while we check your card.</h6>
                </div>
                <div class="card-body">

                    <div class="mb-2">

                        <div class="spinner-border text-primary me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        </div>

{{--        @if(old('image'))--}}

{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header text-center">--}}
{{--                            <h5 class="card-title mb-0">Balance Screenshot</h5>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <img class="card-img-top" src="{{old('image')}}">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}




{{--    @endif--}}



@endsection
