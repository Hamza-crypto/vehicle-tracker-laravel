<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="filter-form">
                    <input type="hidden" class="d-none" name="filter" value="true" hidden>
                    <div class="row">

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="title">Year Make Model (Description)</label>
                                <input class="form-control" type="text" name="description"
                                    placeholder="Enter Description" />
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="category"> Item Number </label>
                                <input class="form-control" type="text" name="item_number"
                                    placeholder="Item number: 0000" />
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="category"> Lot Number </label>
                                <input class="form-control" type="text" name="lot_number"
                                    placeholder="Lot number: 00000000" />
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="category"> Claim Number </label>
                                <input class="form-control" type="text" name="claim_number"
                                    placeholder="Claim Number : 0000000000" />
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="category"> Number of runs </label>
                                <input class="form-control" type="number" name="number_of_runs"
                                    placeholder="Number of runs: 2" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm mt-4">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Apply</button>
                            <button type="button" class="btn btn-sm btn-secondary mt-2"
                                id="clear-button">Clear</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
</div>
