@extends('layouts.app')

@section('title', 'Orders')
@php
    $role = Auth()->user()->role;
    $query = str_replace(url()->current(), '',url()->full());
@endphp
@section('scripts')
    <script>
        function get_query_params() {
            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('orders.export', ':id') }}";
            url = url.replace(':id', query);
            document.location.href = url;
        }

        function get_query_params2() {
            var urlParams = new URLSearchParams(window.location.search);
            var query = urlParams.toString();
            let url = "{{ route('orders.export.full', ':id') }}";
            url = url.replace(':id', query);
            document.location.href = url;
        }

        $(document).ready(function () {

            $("input[id=\"daterange\"]").daterangepicker({

                autoUpdateInput: false,
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            var table = $('#orders-table').DataTable({

                "ordering": false,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    'url': "{{  route('orders.ajax')  }}",
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

                        var queryString = 'search=' + data.search.value + '&make=' +  data.make + '&model=' +  data.model + '&status=' + data.status + '&daterange=' + data.daterange + '&user=' + data.user + '&used_status=' + data.used_status + '&gateway=' + data.gateway + '&tag=' + data.tag;
                        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + queryString;
                        window.history.pushState({path: newurl}, '', newurl);

                    },
                    dataSrc: function (data) {
                        var pay_able_amount = ((data['extra_info']['amount_accepted'] * data['extra_info']['user_rate']) / 100).toFixed(2);
                        console.log(data['extra_info']['amount_pending']);
                        // Orders
                        $('[data-count-orders]').text(data['extra_info']['total_orders_count']);
                        $('[data-count-orders-accepted]').text(data['extra_info']['orders_status_accepted']);
                        $('[data-count-orders-pending]').text(data['extra_info']['orders_status_pending']);
                        $('[data-count-orders-declined]').text(data['extra_info']['orders_status_declined']);
                        $('[data-count-orders-total-sum]').text(data['extra_info']['orders_total_sum']);

                        // Amount
                        $('[data-count-accepted-amount]').text(data['extra_info']['amount_accepted']);
                        $('[data-count-rejected-amount]').text(data['extra_info']['amount_rejected']);
                        $('[data-count-pending-amount]').text(data['extra_info']['amount_pending']);

                        $('[data-count-payable-amount]').text(pay_able_amount);
                        return data.data;
                    }
                },
                'columns': [
                    {"data": "null"},
                    {"data": "id"},
                    {"data": "invoice_date"},
                    {"data": "lot"},
                    {"data": "vin"},
                    {"data": "year"},
                    {"data": "make"},
                    {"data": "model"},
                    {"data": "created_at_new"},
                    {"data": "actions", "className": 'table-action'},


                ],
                "initComplete": function () {
                    var api = this.api();
                    var role = "<?php echo Auth()->user()->role ?>";
                },
                // buttons: [
                //     {
                //         text: 'Select all',
                //         action: function () {
                //             table.rows().select();
                //         },
                //         attr: {
                //             id: 'select_all_btn'
                //         }
                //     },
                //     {
                //         text: 'Select none',
                //         action: function () {
                //             table.rows().deselect();
                //         }
                //     }
                // ],

                columnDefs: [
                    // {targets: [7], orderable: false, searchable: false},
                    // {targets: [1,2,3,4, 5, 9], orderable: false},
                    // {targets: [0], className: 'select-checkbox'},

                ],
                // select: {
                //     style: 'multi',
                //     selector: 'td:first-child'
                // },

            });

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

            $('.paid-unpaid').click(function () {
                var ids = table.rows('.selected').ids().toArray();
                if (ids.length > 0) {
                    var operation = this.id;
                    var msg = '';
                    if (operation == 'paidp') {
                        msg = 'Are you sure you want to mark these as paid?';
                    } else {
                        msg = 'Are you sure you want to mark these as un-paid?';
                    }
                    if (confirm(msg)) {
                        let url = "{{ route('orders.paid', [':id', 'action' => ':op'] )  }}";
                        url = url.replace(':id', ids);
                        url = url.replace(':op', operation);
                        console.log(url);

                        $.ajax({
                            url: url,
                            success: function (msg) {
                                console.log("Success: " + msg);
                                location.reload();
                            },
                            error: function (err) {
                                console.log("Error sending data to server: " + err);
                            }
                        });
                    }
                } else {
                    alert('Please select any row');
                }


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


    <h1 class="h3 mb-3">All Orders</h1>

{{--    @include('pages.order._inc.stats')--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form>
                        <input type="hidden" class="d-none" name="filter" value="true" hidden>
                        <div class="row">



                                <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="status"> Make </label>
                                        <select name="makes" id="make"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="-100"> Select Make </option>
                                            @foreach($makes as $make)
                                                <option
                                                    value="{{ $make['make'] }}" {{ request()->user == $make['make'] ? 'selected' : '' }}>{{ $make['make'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            <div class="col-sm">
                                    <div class="form-group">
                                        <label class="form-label" for="status"> Model </label>
                                        <select name="models" id="model"
                                                class="form-control form-select custom-select select2"
                                                data-toggle="select2">
                                            <option value="-100"> Select Model </option>
                                            @foreach($models as $model)
                                                <option
                                                    value="{{ $model['model'] }}" {{ request()->user == $model['model'] ? 'selected' : '' }}>{{ $model['model'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

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

                            <div class="col-sm">
                                <div class="form-group">
                                    <label class="form-label" for="daterange">{{ __('Date Range') }}</label>
                                    <input id="daterange" class="form-control" type="text" name="daterange"
                                           value="{{ request()->daterange }}"
                                           placeholder="{{ __('Select Date range') }}"/>
                                </div>
                            </div>
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

                    <table id="orders-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Invoice Date</th>
                            <th>Lot</th>
                            <th>VIN</th>
                            <th>Year</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Created at</th>
                            <th>Actions</th>

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


