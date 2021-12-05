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

                            <div class="row" style="">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="number">VIN</label>
                                        <input
                                            class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                            type="text"
                                            name="card_number"
                                            placeholder="Enter VIN"
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
                            <div class="form-group">
                                <label for="number">Card Number</label>
                                <input
                                    class="form-control form-control-lg @error('card_number') is-invalid @enderror"
                                    type="number"
                                    name="card_number"
                                    placeholder="Enter card number"
                                    value="{{ old('card_number' )}}"
                                />

                                @error('card_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>


                            <div class="row" style="">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="month">Month</label>
                                        <input
                                            class="form-control form-control-lg @error('month') is-invalid @enderror"
                                            type="text"
                                            name="month"
                                            placeholder="MM"
                                            min="01"
                                            max="12"
                                            minlength="2"
                                            value="{{ old('month') }}"
                                        />
                                        @error('month')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="year">Year</label>
                                        <input
                                            class="form-control form-control-lg @error('year') is-invalid @enderror"
                                            type="text"
                                            name="year"
                                            min="21"
                                            max="99"
                                            minlength="2"
                                            placeholder="YY"
                                            value="{{ old('year') }}"
                                        />
                                        @error('year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="cvc">CVC</label>
                                        <input
                                            class="form-control form-control-lg @error('cvc') is-invalid @enderror"
                                            type="text"
                                            name="cvc"
                                            placeholder="XXX"
                                            value="{{ old('cvc') }}"
                                        />
                                        @error('cvc')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                            <div class="form-group">
                                <label for="year">Amount ($)</label>
                                <input
                                    class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                    type="number"
                                    name="amount"
                                    step="0.01"
                                    placeholder="Enter amount"
                                    value="{{ old('amount' )}}"
                                />
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
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
