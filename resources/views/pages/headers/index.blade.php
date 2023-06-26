@extends('layouts.app')

@section('title', 'Vehicles')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '',url()->full());
@endphp

@section('scripts')
    <script>

        $(document).ready(function () {
            //whenever any option from select is changed, put its value in the url and refresh the page
            $('select').on('change', function () {
                var url = $(this).val();
                console.log(url);
                var queryString = 'filetype=' + url;
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
                window.history.pushState({path: newurl}, '', newurl);

            });

        });


    </script>
@endsection

@section('content')

    <h1 class="h3 mb-3">CSV Headers Mapping</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('field.mapping') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="form-label" for="filetype"> File Type</label>
                                    <select name="filetype" id="filetype"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        <option value="-100"> Select File</option>
                                        <option value="copart_buy"
                                                @if(request()->filetype == 'copart_buy') selected @endif> Copart Buy
                                            (Step 1)
                                        </option>
                                        <option value="iaai_buy" @if(request()->filetype == 'iaai_buy') selected @endif>
                                            IAAI Buy v1 (Step 1)
                                        </option>

                                        <option value="copart_inventory"
                                                @if(request()->filetype == 'copart_inventory') selected @endif> Copart
                                            Inventory (Step 2)
                                        </option>
                                        <option value="copart_sale"
                                                @if(request()->filetype == 'copart_sale') selected @endif> Copart Sale
                                            (Step 3)
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mt-4 mb-0">
                                    <button type="submit" class="btn btn-lg btn-primary add-btn">Submit
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="mb-3">
                                        <label class="form-label w-100">Copart</label>
                                        <input type="file" name="csv_file" required>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($db_fields))
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="card">
                    <form method="post" action="{{ route('headers.store') }}" >
                        @csrf
                        <input type="hidden" name="filetype" value="{{$filetype}}">

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width:10%;">Sr.#</th>
                                <th style="width:45%;">Field Name</th>
                                <th style="width:45%;">Header Name</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($db_fields as $db_key => $header_field)
                                <tr>
                                    <td>{{ $loop->index +1  }}</td>
                                    <td>{{ strtoupper($db_key) }}</td>
                                    <td>
                                        <select name="{{ $db_key }}"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="-100"> Select Field</option>
                                            @foreach($headers as $header)
                                                <option value="{{ $header }}" @if($header == $header_field) selected @endif> {{ $header }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>

                        <div class="form-group mt-4 p-3">
                            <button type="submit" class="btn btn-lg btn-primary add-btn">Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection


