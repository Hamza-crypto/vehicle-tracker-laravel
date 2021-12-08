@extends('layouts.app')

@section('title', 'Add File')

@section('scripts')
    <script>
        $('#add').click(function(){
             $('#loader').toggleClass('d-none');
        });

        $('#add2').click(function(){
             $('#loader').toggleClass('d-none');
        });
    </script>
@endsection
@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    @if(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif

        <h1 class="h3 mb-3">Add New File </h1>

        <div class="row">

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('buy.copart') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                            <div class="mb-3">
                                <label class="form-label w-100">Copart</label>
                                <input type="file" name="csv_file" required>
                            </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="add" class="btn btn-lg btn-primary">Add New File
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('buy.iaai') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <div class="mb-3">
                                    <label class="form-label w-100">IAAI</label>
                                    <input type="file" name="csv_file" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="add2" class="btn btn-lg btn-primary">Add New File
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>

        </div>

    <div id="loader" class="row text-center d-none">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Checking CSV File</h5>
                    <h6 class="card-subtitle text-muted">Please wait while we check your file and save in system</h6>
                </div>
                <div class="card-body">

                    <div class="mb-2">

                        <div class="spinner-border text-primary me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
