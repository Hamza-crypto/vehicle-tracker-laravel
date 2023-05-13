<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="modal fade" id="modal-vehicle-create" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <form class="vehicle-detail-form" id="vehicle-detail-form2" method="post" action="#">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Vehicle</h5>
                            </div>
                            <div id="vehicle-detail-div2"></div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="close-modal-btn-vehicle-create"
                                        data-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-primary"
                                        @if($role == 'viewer')
                                            disabled
                                    @endif
                                >Save Vehicle
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
