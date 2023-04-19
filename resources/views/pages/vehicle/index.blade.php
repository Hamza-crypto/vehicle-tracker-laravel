@extends('layouts.app')

@section('title', 'Vehicles')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '',url()->full());
@endphp
@section('scripts')
    <script>
        {{--function get_query_params() {--}}
        {{--    var urlParams = new URLSearchParams(window.location.search);--}}
        {{--    var query = urlParams.toString();--}}
        {{--    let url = "{{ route('orders.export', ':id') }}";--}}
        {{--    url = url.replace(':id', query);--}}
        {{--    document.location.href = url;--}}
        {{--}--}}

        {{--function get_query_params2() {--}}
        {{--    var urlParams = new URLSearchParams(window.location.search);--}}
        {{--    var query = urlParams.toString();--}}
        {{--    let url = "{{ route('orders.export.full', ':id') }}";--}}
        {{--    url = url.replace(':id', query);--}}
        {{--    document.location.href = url;--}}
        {{--}--}}

        $(document).ready(function () {

            $("input[id=\"daterange\"]").daterangepicker({

                autoUpdateInput: false,
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            var table = $('#vehicles-table').DataTable({
                "dom": 'lrtBip',
                "responsive": true,
                "ordering": true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    'url': "{{  route('vehicles.ajax')  }}",
                    "dataType": "json",
                    "type": "GET",
                    "data": function (data) {

                        data.make = $('#make').val();
                        data.model = $('#model').val();
                        data.status = $('#status').val();

                        data.daterange = $('#daterange').val();
                        data.user = $('#user').val();

                        data.used_status = $('#used_status').val();
                        data.gateway = $('#gateway').val();
                        data.tag = $('#tag').val();

                        // if(data.user=='undefined') {
                        //     alert('ddd');
                        // }

                        var queryString = 'search=' + data.search.value + '&status=' + data.status;
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
                        window.history.pushState({path: newurl}, '', newurl);

                    },
                    dataSrc: function (data) {
                        return data.data;
                    }
                },
                'columns': [
                    {"data": "null"},
                    {"data": "description"},
                    {"data": "vin"},
                    {"data": "left_location"},
                    {"data": "location"},
                    {"data": "date_paid"},
                    {"data": "purchase_lot"}, //purchase lot
                    {"data": "auction_lot"}, //auction lot
                    {"data": "days_in_yard"},
                    {"data": "claim_number"},
                    {"data": "actions", "className": 'table-action'},
                    // {"data": "status"},
                    // {"data": "id"},
                ],
                "initComplete": function () {
                    var api = this.api();
                    var role = "<?php echo Auth()->user()->role ?>";

                    if (role == 'viewer') {
                        api.columns([9]).visible(false);
                    }
                },
                "buttons": [
                    {
                        text: 'Select all',
                        action: function () {
                            table.rows().select();
                        },
                        attr: {
                            id: 'select_all_btn',
                            class: 'btn btn-primary'
                        }
                    },
                    {
                        text: 'Select none',
                        action: function () {
                            table.rows().deselect();
                        }
                    },
                    {
                        text: 'Delete selected',
                        action: function () {
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
                                            success: function (data) {
                                                Swal.fire(
                                                    'Successfully deleted!',
                                                    data.message,
                                                    'success'
                                                );
                                                table.ajax.reload();

                                            }
                                        });
                                    }
                                })
                            }


                        },
                        attr: {
                            id: 'delete_btn',
                            class: 'btn btn-danger'
                        }
                    }
                ],
                "columnDefs": [
                    {targets: [0], className: 'select-checkbox'},
                    {targets: [0, 1, 2, 4, 8, 9, 10], orderable: false}


                ],
                "select": {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                "pagingType": "simple_numbers"
            });

            $.fn.DataTable.ext.pager.numbers_length = 4;

            // Attach click event to each row
            // $('#vehicles-table tbody').on('click', 'tr', function() {
            //     var tr = $(this);
            //     var row = $('#vehicles-table').DataTable().row(tr);
            //
            //     if (row.child.isShown()) {
            //         // This row is already open - close it
            //         row.child.hide();
            //         tr.removeClass('shown');
            //     } else {
            //         // Open this row
            //         row.child(row.data()).show();
            //         tr.addClass('shown');
            //     }
            // });


            $('.apply-dt-filters').on('click', function () {
                table.ajax.reload();
            });

            $('.clear-dt-filters').on('click', function () {
                $('#status').val('-100').trigger('change');
                $('#daterange').val('');

                $('#make').val('-100').trigger('change');
                $('#model').val('-100').trigger('change');
                table.search("");
                table.ajax.reload();
            });


            @if($role != 'admin')
            $('#select_all_btn').parent().hide();
            @endif

        });
    </script>
@endsection

@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @elseif(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @elseif(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif


    <h1 class="h3 mb-3">All Vehicles</h1>

{{--    @include('pages.order._inc.stats')--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <input type="hidden" class="d-none" name="filter" value="true" hidden>
                        <div class="row">



{{--                                <div class="col-sm">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="form-label" for="status"> Make </label>--}}
{{--                                        <select name="makes" id="make"--}}
{{--                                                class="form-control form-select custom-select select2"--}}
{{--                                                data-toggle="select2">--}}
{{--                                            <option value="-100"> Select Make </option>--}}
{{--                                            @foreach($makes as $make)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $make['make'] }}" {{ request()->user == $make['make'] ? 'selected' : '' }}>{{ $make['make'] }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            <div class="col-sm">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="form-label" for="status"> Model </label>--}}
{{--                                        <select name="models" id="model"--}}
{{--                                                class="form-control form-select custom-select select2"--}}
{{--                                                data-toggle="select2">--}}
{{--                                            <option value="-100"> Select Model </option>--}}
{{--                                            @foreach($models as $model)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $model['model'] }}" {{ request()->user == $model['model'] ? 'selected' : '' }}>{{ $model['model'] }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-label" for="status"> Status </label>
                                    <select name="status" id="status"
                                            class="form-control form-select custom-select select2"
                                            data-toggle="select2">
                                        <option value="-100"> Select Status </option>
                                        @foreach($statuses as $status)
                                            <option
                                                value="{{ $status['meta_value']  }}" {{ request()->status == $status['meta_value'] ? 'selected' : '' }}>{{ $status['meta_value'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

{{--                            <div class="col-sm">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-label" for="daterange">{{ __('Date Range') }}</label>--}}
{{--                                    <input id="daterange" class="form-control" type="text" name="daterange"--}}
{{--                                           value="{{ request()->daterange }}"--}}
{{--                                           placeholder="{{ __('Select Date range') }}"/>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>

                        <div class="row">
                            <div class="col-sm mt-4">
                                <button type="button"
                                        class="btn btn-sm btn-primary apply-dt-filters mt-2">{{ __('Apply') }}</button>
                                <button type="button"
                                        class="btn btn-sm btn-secondary clear-dt-filters mt-2">{{ __('Clear') }}</button>

{{--                                <button type="button" class="btn btn-sm btn-secondary mt-2"--}}
{{--                                        onclick="get_query_params2()"--}}
{{--                                >{{ 'Export ' }}</button>--}}

                            </div>
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="vehicles-table" class="table table-striped dataTable no-footer dtr-inline" style="width:100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Year-Make-Model</th>
                            <th>VIN</th>
                            <th>Left Location</th>
                            <th>Current Location</th>
                            <th>Purchase Date</th>
                            <th>Purchase Lot #</th>
                            <th>Auction Lot #</th>
                            <th>Days in Yard</th>
                            <th>Claim Number</th>
                            <th>Actions</th>
{{--                            <th>Status</th>--}}
{{--                            <th>ID</th>--}}
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


