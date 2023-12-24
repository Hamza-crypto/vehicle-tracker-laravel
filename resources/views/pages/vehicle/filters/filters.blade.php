<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form>
                    <input type="hidden" class="d-none" name="filter" value="true" hidden>
                    <div class="row">

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="location"> Location </label>
                                <select name="location" id="location"
                                    class="form-control form-select custom-select select2" data-toggle="select2">
                                    <option value="-100"> Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location }}"
                                            {{ request()->location == $location ? 'selected' : '' }}>{{ $location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="status"> Status </label>
                                <select name="status" id="status"
                                    class="form-control form-select custom-select select2" data-toggle="select2">
                                    <option value="-100"> Select Status</option>
                                    @foreach (\App\Models\Vehicle::STATUSES as $status)
                                        <option value="{{ $status }}"
                                            {{ request()->status == $status ? 'selected' : '' }}>{{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="claim_number">Claim Number</label>
                                <input id="claim_number" class="form-control" type="text" name="claim_number"
                                    value="{{ request()->claim_number }}" placeholder="Enter Claim #" />
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="left_location">Left Location</label>
                                <input id="left_location" class="form-control daterange-filter" type="text"
                                    name="left_location" value="{{ request()->left_location }}"
                                    placeholder="{{ __('Select Date range') }}" />
                            </div>
                        </div>

                        <div class="col-sm">
                            <div class="form-group">
                                <label class="form-label" for="date_paid">Date Paid</label>
                                <input id="date_paid" class="form-control daterange-filter" type="text"
                                    name="date_paid" value="{{ request()->date_paid }}"
                                    placeholder="{{ __('Select Date range') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm mt-4">
                            <button type="button"
                                class="btn btn-sm btn-primary apply-dt-filters mt-2">{{ __('Apply') }}</button>
                            <button type="button"
                                class="btn btn-sm btn-secondary clear-dt-filters mt-2">{{ __('Clear') }}</button>


                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
</div>
