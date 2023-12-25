@extends('layouts.app')

@section('title', __('Run Lists'))

@section('scripts')
    <script src="{{ asset('/assets/js/custom.js') }}"></script>
    <script>
        var currentPage = 1;
        var totalPages = 1;
        var perPage = 10;
        var defaultSort = 'lot_number';
        var defaultOrder = 'asc';
        var filters = {};

        function fetchData(page, filters, column, order) {
            console.log('Sorting by', column, 'in', order, 'order');
            var requestData = {
                page: page,
                per_page: perPage
            };

            Object.keys(filters).forEach((key) => {
                if (filters[key] !== '-100') {
                    requestData[`filter[${key}]`] = filters[key];
                }
            });

            if (column != undefined) {
                //Update sorting order based on defaultSort and defaultOrder
                requestData['sort'] = (order === 'desc' ? '-' : '') + column;
            }


            $.ajax({
                url: 'get_run_lists',
                method: 'GET',
                data: requestData,
                dataType: 'json',
                success: function(response) {
                    totalPages = response.meta.last_page;
                    populateTable(response.data);
                    updatePaginationButtons(response.links, response.meta.links);
                    updateTableInfo(response.meta);

                },
                error: function() {
                    alert('Failed to fetch run lists from the API.');
                },

            });
        }

        function populateTable(runLists) {
            var tbody = $('#runs-table tbody');
            tbody.empty();

            if (runLists.length === 0) {
                displayNoRecordsMessage(11);
            }

            $.each(runLists, function(index, vehicle) {

                var editUrl = "/runLists/" + vehicle.id + "/details";
                var roleBasedActions = '';

                var row = '<tr>' +
                    '<td>' + vehicle.id + '</td>' +
                    '<td>' + vehicle.description + '</td>' +
                    '<td>' + vehicle.item_number + '</td>' +
                    '<td>' + vehicle.lot_number + '</td>' +
                    '<td>' + vehicle.claim_number + '</td>' +
                    '<td>' + vehicle.number_of_runs + '</td>' +

                    '<td><span class="badge bg-primary me-2">' + vehicle.created_at +
                    '</tr>';
                tbody.append(row);
            });
        }

        $(document).ready(function() {
            var column = defaultSort;
            var order = defaultOrder;

            fetchData(currentPage, filters, column, order);

            $(document).on('click', '.delete-btn', function() {
                var resourceId = $(this).data('id');
                var csrfToken = '{{ csrf_token() }}';
                deleteConfirmation(resourceId, 'vehicle', 'runLists', csrfToken);
            });


            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var description = $('[name="description"]').val();
                var item_number = $('[name="item_number"]').val();
                var lot_number = $('[name="lot_number"]').val();
                var claim_number = $('[name="claim_number"]').val();
                var number_of_runs = $('[name="number_of_runs"]').val();

                filters = {
                    description: description,
                    item_number: item_number,
                    lot_number: lot_number,
                    claim_number: claim_number,
                    number_of_runs: number_of_runs,

                };
                currentPage = 1;
                fetchData(currentPage, filters);
            });

            // Handle header click to change sorting
            $('#runs-table th').on('click', function() {
                var sortColumn = $(this).data('column');
                var sortOrder = 'asc';

                // Toggle sorting order
                if ($(this).hasClass('sorting_asc')) {
                    sortOrder = 'desc';
                }

                // Update sorting icons
                $('#runs-table th').removeClass('sorting_asc sorting_desc');
                $(this).addClass('sorting_' + sortOrder);

                // Sort the table
                fetchData(currentPage, filters, sortColumn, sortOrder);
            });
        });
    </script>
@endsection

@section('content')


    @include('pages.runlist.filters')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="datatables-reponsive_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="table_length" id="table_length"><label>Show <select
                                            name="datatables-reponsive_length" id="per-page-select"
                                            class="form-select form-select-sm">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="-1">All</option>
                                        </select> entries</label></div>
                            </div>

                        </div>
                        <div class="row dt-row">
                            <div class="col-sm-12">
                                <table id="runs-table" class="table table-striped dataTable no-footer dtr-inline"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th data-column="id">ID</th>
                                            <th data-column="description">Description</th>
                                            <th data-column="item_number">Item Number</th>
                                            <th data-column="lot_number">Lot Number</th>
                                            <th data-column="claim_number">Claim Number</th>
                                            <th data-column="number_of_runs">Number of Runs</th>
                                            <th data-column="created_at">Created At</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="table_entries_info" role="status" aria-live="polite">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="datatables-reponsive_paginate">
                                    <ul class="pagination"></ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
