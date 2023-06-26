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

                if (url) {
                    window.location.reload();
                }
            });

            // Attach click event handler to delete buttons
            $('.delete_header').on('click', function () {
                // Get the data-id attribute value
                var id = $(this).data('id');

                // Send AJAX request to delete the record
                $.ajax({
                    url: 'headers/' + id,
                    type: 'DELETE',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Handle the success response
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        );

                        window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle the error response
                        console.error('Error deleting record:', error);
                    }
                });
            });


            // Attach submit event handler to the form
            $('#add_header').on('submit', function (e) {
                e.preventDefault();  // Prevent the default form submission

                // Get the value of the input field
                var headerValue = $('#header').val();

                // Send AJAX request to create the record
                $.ajax({
                    url: 'headers',  // Replace with your create endpoint
                    type: 'POST',              // Adjust the HTTP method if necessary
                    data: {
                        header: headerValue,
                        filename:  '{{request()->filetype}}',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Handle the success response
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        );

                        window.location.reload();

                    },
                    error: function (xhr, status, error) {
                        // Handle the error response
                        console.error('Error creating record:', error);
                    }
                });
            });


        });


    </script>
@endsection

@section('content')

    <h1 class="h3 mb-3">CSV Headers</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="add_header">
                        <div class="row">

                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-label" for="filetype"> File Type</label>
                                    <select name="filetype" id="filetype"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        <option value="-100"> Select File</option>
                                        <option value="copart_buy"
                                                @if(request()->filetype == 'copart_buy') selected @endif> Copart Buy (Step 1)
                                        </option>
                                        <option value="iaai_buy" @if(request()->filetype == 'iaai_buy') selected @endif>
                                            IAAI Buy (Step 1)
                                        </option>
                                        <option value="copart_inventory"
                                                @if(request()->filetype == 'copart_inventory') selected @endif> Copart
                                            Inventory (Step 2)
                                        </option>
                                        <option value="copart_sale"
                                                @if(request()->filetype == 'copart_sale') selected @endif> Copart Sale (Step 3)
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-label" for="filetype"> New Header</label>
                                    <input type="text" name="header" id="header" class="form-control"
                                           placeholder="Enter Header Name">
                                </div>
                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-6">
            <div class="card">

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width:10%;">Sr.#</th>
                        <th style="width:80%;">Header Name</th>
                        <th style="width:10%;">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($headers as $header)
                        <tr>
                            <td>{{ $loop->index +1  }}</td>
                            <td>{{ $header->header }}</td>
                            <td>
                                <button class="btn text-danger delete_header" data-id="{{ $header->id }}">
                                    <i class="fa fa-trash"></i>

                                </button>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection


