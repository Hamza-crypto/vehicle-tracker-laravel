<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="modal fade" id="modal-vehicle-detail" role="dialog">
                <div class="modal-dialog" role="document">
                    <form id="vehicle-detail-form" method="post" action="#">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Vehicle Details</h5>
                            </div>
                            <div class="modal-body m-3">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th style="width:40%;">Key</th>
                                                <th style="width:25%">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="vehicle-detail-div">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="close-modal-btn"
                                        data-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-primary"
                                        @if($role == 'viewer')
                                            disabled
                                    @endif
                                >Save changes
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
