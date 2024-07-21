@extends('layouts.app')

@section('title', 'Vehicles')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '', url()->full());
@endphp

@section('styles')
    <style>
        .modal-body {
            padding: 0rem !important;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('create') && urlParams.get('create') === 'new') {

                $.get('/next_vehicle_id', function(response) {

                    var vehicle_id = response;
                    url = '/vehicles/' + vehicle_id;
                    console.log(url);
                    $.get(url + '/html', function(response) {
                        //replace ID with "Vehicle ID",keys with "Has Keys"

                        //create array which contains keys and values, all the keys will be replaced by their respective values in the response html
                        var replaceKeys = {
                            'ID': 'Vehicle ID',
                            'CREATED_AT': 'Date Entered',
                            'DESCRIPTION': 'Year-Make-Model',
                            'VIN': 'VIN Number',
                            'PURCHASE_LOT': 'Purchase Lot Number',
                            'DATE_PAID': 'Purchase Date',
                            'INVOICE_AMOUNT': 'Purchase Amount($)',
                            'LEFT_LOCATION': 'Left Location',
                            'AUCTION_LOT': 'Auction Lot Number',
                            'LOCATION': 'Current Location',
                            'CLAIM_NUMBER': 'Claim Number',
                            'STATUS': 'Current Status',
                            'ODOMETER': 'Mileage',
                            'ODOMETER_BRAND': 'Odometer',
                            'PRIMARY_DAMAGE': 'PRIMARY DAMAGE',
                            'SECONDARY_DAMAGE': 'SECONDARY DAMAGE',
                            'KEYS': 'Has Keys',
                            'DRIVABILITY_RATING': 'Engine',
                            'DAYS_IN_YARD': 'Days In Yard',
                            'SALE_TITLE_STATE': 'Sale Title State',
                            'SALE_TITLE_TYPE': 'Sale Title Type',
                        };
                        var new_response = response.html;

                        //iterate over the replaceKeys array and replace the keys with their respective values in the response html
                        $.each(replaceKeys, function(key, value) {
                            new_response = new_response.replace(key, value);
                        });

                        var requiredFields = ['vin', 'description', 'location'];
                        $.each(requiredFields, function(key, value) {
                            new_response = new_response.replace('name="' + value + '"',
                                'name="' + value + '" required');
                        });

                        //Apply pattern to vin field
                        new_response = new_response.replace('name="vin"',
                            'name="vin" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed"'
                        );

                        $('#vehicle-detail-div2').html(new_response);

                        //We are overriding select2 library
                        $('.select2').select2({
                            placeholder: "Select Location",
                            tags: true,
                            insertTag: function(data, tag) {
                                data.push(tag);
                            }
                        });


                    });

                    //Adding action attr to form
                    $('#vehicle-detail-form2').attr('action', url);
                });

                $('#modal-vehicle-create').modal('show');

            } else {

                var table = $('#vehicles-table').DataTable({
                    "dom": 'lrftBip',
                    "responsive": true,
                    "ordering": true,
                    'processing': true,
                    'serverSide': true,
                    'ajax': {
                        'url': "{{ route('vehicles.sold.ajax') }}",
                        "dataType": "json",
                        "type": "GET",
                        "data": function(data) {

                            data.make = $('#make').val();
                            data.model = $('#model').val();
                            data.status = $('#status').val();

                            data.daterange = $('#daterange').val();
                            data.user = $('#user').val();

                            data.used_status = $('#used_status').val();
                            data.gateway = $('#gateway').val();
                            data.tag = $('#tag').val();

                            var queryString = 'search=' + data.search.value + '&status=' + data.status;
                            var newurl = window.location.protocol + "//" + window.location.host + window
                                .location.pathname + '?' + queryString;
                            window.history.pushState({
                                path: newurl
                            }, '', newurl);


                        },
                        dataSrc: function(data) {
                            console.log(data['data']);
                            return data.data;
                        }
                    },
                    'columns': [
                        @if ($role == 'admin')
                            {
                                "data": "null"
                            },
                        @endif

                        {
                            "data": "description"
                        },
                        {
                            "data": "vin"
                        },
                        {
                            "data": "auction_lot"
                        }, //auction lot
                        {
                            "data": "sale_date"
                        },
                        {
                            "data": "invoice_amount"
                        },
                        {
                            "data": "sale_price"
                        },
                        {
                            "data": "days_in_yard"
                        },
                        @if ($role == 'admin')
                            {
                                "data": "actions"
                            },
                        @endif
                        // {"data": "status"},
                        // {"data": "id"},
                    ],
                    "initComplete": function() {
                        var api = this.api();
                        var role = "<?php echo Auth()->user()->role; ?>";

                        if (role == 'viewer') {
                            //api.columns([9]).visible(false);
                        }
                    },
                    "buttons": [{
                            text: 'Select all',
                            action: function() {
                                table.rows().select();
                            },
                            attr: {
                                id: 'select_all_btn',
                                class: 'btn btn-primary'
                            }
                        },
                        {
                            text: 'Select none',
                            action: function() {
                                table.rows().deselect();
                            }
                        },
                        {
                            text: 'Delete selected',
                            action: function() {
                                var ids = table.rows('.selected').ids().toArray();
                                if (ids.length > 0) {

                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "You want to delete selected vehicles?",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, delete it!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.ajax({
                                                url: "{{ route('vehicles.delete-multiple') }}",
                                                type: 'DELETE',
                                                data: {
                                                    ids: ids,
                                                    _token: "{{ csrf_token() }}"
                                                },
                                                success: function(data) {
                                                    Swal.fire(
                                                        'Successfully deleted!',
                                                        data.message,
                                                        'success'
                                                    );
                                                    table.ajax.reload();

                                                },
                                                error: function(data) {
                                                    Swal.fire(
                                                        'Unauthorized !',
                                                        'You are not allowed to delete vehicles!',
                                                        'error'
                                                    );
                                                }
                                            });
                                        }
                                    })
                                } else {
                                    Swal.fire(
                                        'No vehicles selected!',
                                        'Please select at least one vehicle to delete.',
                                        'warning'
                                    );
                                }


                            },
                            attr: {
                                id: 'delete_btn',
                                class: 'btn btn-danger'
                            }
                        }
                    ],
                    @if ($role == 'admin')
                        "columnDefs": [{
                                targets: [1, 2, 3, 6],
                                orderable: false
                            },
                            {
                                targets: [0],
                                className: 'select-checkbox sorting_disabled'
                            },
                        ],

                        "select": {
                            style: 'multi',
                            selector: 'td:first-child'
                        },
                    @else
                        "columnDefs": [{
                            targets: [0, 1, 3, 4],
                            orderable: false
                        }, ],
                    @endif


                    "pagingType": "simple_numbers"
                });

                $.fn.DataTable.ext.pager.numbers_length = 4;
            }

            $('.apply-dt-filters').on('click', function() {
                table.ajax.reload();
            });

            $('.clear-dt-filters').on('click', function() {
                $('#status').val('-100').trigger('change');
                $('#daterange').val('');

                $('#make').val('-100').trigger('change');
                $('#model').val('-100').trigger('change');
                table.search("");
                table.ajax.reload();
            });

            @if ($role != 'admin')
                $('#select_all_btn').parent().hide();
            @endif

            //Submit form
            $('.vehicle-detail-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var data = form.serialize();
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        console.log(response);
                        $('#modal-vehicle-create').modal('hide');

                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        );

                        // window.location.href = '/vehicles';

                    },
                    error: function(error) {

                        const errors = error.responseJSON.errors;
                        var errorString = '';
                        $.each(errors, function(key, value) {
                            errorString += '<li>' + value + '</li>';
                        });


                        Swal.fire(
                            'Error!',
                            errorString,
                            'error'
                        );

                    }
                });
            });



        });

        //After 3 seconds, remove one class from element whose class is select-checkbox
        setTimeout(function() {
            $('.select-checkbox').removeClass('sorting_asc');


        }, 1000);

        /**
         * Vehicle Detail Form
         */

        $('#vehicles-table tbody').on('click', 'tr', function() {

            $('#vehicle-detail-div').html(
                '<div class="text-center">Please wait... Data is loading<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>'
            );

            var vehicleId = '/vehicles/' + $(this).attr('id');
            $.get(vehicleId + '/html', function(response) {
                //replace ID with "Vehicle ID",keys with "Has Keys"

                //create array which contains keys and values, all the keys will be replaced by their respective values in the response html
                var replaceKeys = {
                    'ID': 'Vehicle ID',
                    'CREATED_AT': 'Date Entered',
                    'DESCRIPTION': 'Year-Make-Model',
                    'VIN': 'VIN Number',
                    'PURCHASE_LOT': 'Purchase Lot Number',
                    'DATE_PAID': 'Purchase Date',
                    'INVOICE_AMOUNT': 'Purchase Amount($)',
                    'LEFT_LOCATION': 'Left Location',
                    'AUCTION_LOT': 'Auction Lot Number',
                    'LOCATION': 'Current Location',
                    'CLAIM_NUMBER': 'Claim Number',
                    'STATUS': 'Current Status',
                    'ODOMETER': 'Mileage',
                    'ODOMETER_BRAND': 'Odometer',
                    'PRIMARY_DAMAGE': 'PRIMARY DAMAGE',
                    'SECONDARY_DAMAGE': 'SECONDARY DAMAGE',
                    'KEYS': 'Has Keys',
                    'DRIVABILITY_RATING': 'Engine',
                    'DAYS_IN_YARD': 'Days In Yard',
                    'SALE_TITLE_STATE': 'Sale Title State',
                    'SALE_TITLE_TYPE': 'Sale Title Type',
                    'SALE_PRICE': 'Sale Price',
                };
                var new_response = response.html;

                //iterate over the replaceKeys array and replace the keys with their respective values in the response html
                $.each(replaceKeys, function(key, value) {
                    new_response = new_response.replace(key, value);
                });

                var requiredFields = ['vin', 'description', 'location'];
                $.each(requiredFields, function(key, value) {
                    new_response = new_response.replace('name="' + value + '"', 'name="' + value +
                        '" required');
                });

                //Apply pattern to vin field
                new_response = new_response.replace('name="vin"',
                    'name="vin" pattern="[A-Za-z0-9]+" title="Only alphanumeric characters are allowed"'
                );

                $('#vehicle-detail-div').html(new_response);

                //We are overriding select2 library
                $('.select2').select2({
                    placeholder: "Select Location",
                    tags: true,
                    insertTag: function(data, tag) {
                        data.push(tag);
                    }
                });

                var startDate;
                $('.daterange').each(function(index) {
                    startDate = $(this).val();
                    if (startDate == '') {
                        startDate = moment().format('YYYY-MM-DD');
                    }
                    console.log(startDate);
                    $(this).daterangepicker({
                        singleDatePicker: true,
                        showDropdowns: true,
                        startDate: startDate,
                        locale: {
                            format: "YYYY-MM-DD"
                        }
                    });
                });

            });

            //Adding action attr to form
            $('#vehicle-detail-form').attr('action', vehicleId);
        });
    </script>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @elseif(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @elseif(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif


    <h1 class="h3 mb-3">Sold Vehicles</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="vehicles-table" class="table table-striped dataTable no-footer dtr-inline" style="width:100%">
                        <thead>
                            <tr>
                                @if ($role == 'admin')
                                    <th></th>
                                @endif

                                <th>Year-Make-Model</th>
                                <th>VIN</th>
                                <th>Auction Lot #</th>
                                <th>Sale Date</th>
                                <th>Purchased ($)</th>
                                <th>Sale Price</th>
                                <th>Days in Yard</th>
                                @if ($role == 'admin')
                                    <th>Actions</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
