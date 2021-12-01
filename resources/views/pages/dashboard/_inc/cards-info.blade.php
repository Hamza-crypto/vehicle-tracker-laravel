<div class="row">
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">
                    24/7 Automatic Gateway

                    <div class="d-inline float-right">
                        <span class="fas fa-circle chat-online"></span>
                        Online
                    </div>

                </h5>


            </div>
            <table class="table table-sm table-striped my-0">
                <thead>
                <tr>
                    <th>ID</th>

                    <th>BIN</th>
                </tr>
                </thead>
                <tbody class="text-end">
                <tr>
                    <td>1</td>

                    <td>451129</td>
                </tr>
                <tr>
                    <td>2</td>

                    <td>491277</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">

                <h5 class="card-title mb-0">
                    Manually Processed Gateway
                    <div class="d-inline float-right">
                        @if($customer_status)
                            <span class="fas fa-circle chat-online"></span>
                            Online
                            @else
                            <span class="fas fa-circle chat-offline"></span>
                            Offline
                        @endif
                    </div>
                </h5>
            </div>
            <table class="table table-sm table-striped my-0">
                <thead>
                <tr>
                    <th>ID</th>

                    <th>BIN</th>
                </tr>
                </thead>
                <tbody class="text-end">
                <tr>
                    <td>1</td>

                    <td>411810</td>
                </tr>

                <tr>
                    <td>2</td>

                    <td>510404</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="card">
            {{--            <div class="card-header">--}}
            {{--                <h5 class="card-title">Alerts with buttons</h5>--}}
            {{--                <h6 class="card-subtitle text-muted">Alerts with actions.</h6>--}}
            {{--            </div>--}}
            <div class="card-body">

                <div class="mb-3">
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <div class="alert-message">
                            <h4 class="alert-heading">Good Card Declined?</h4>
                            <p>
                                If a card was good and was still declined enter the card information here and upload a
                                screenshot so our support can review it.
                            </p>
                            <hr>
                            <div class="btn-list">
                                <button class="btn btn-success" data-toggle="modal" data-target="#myModal"
                                        type="button">Send card info
                                </button>
                                <button class="btn btn-danger" type="button">No, thanks</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-3"></div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ route('feedbacks.store') }}">
                        @csrf
                        @method('POST')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Card Feedback</h5>

                        </div>

                        <div class="modal-body m-3">
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
                                            maxlength="2"
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
                                            maxlength="4"
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
                                            maxlength="4"
                                            minlength="3"
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
                                    min="0.01"
                                    max="500"
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
                                    <textarea
                                        class="form-control text-area-description"
                                        name="user_note"
                                        placeholder="Put your card info and also paste image here"></textarea>
                                </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Save changes</button>
                        </div>

                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    @include('pages.dashboard.ckeditor')
@endsection
