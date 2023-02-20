@extends('layouts.app')

@section('title', 'Add File')

@section('scripts')
    <script>
        $('#add').click(function(){
            // alert('sss');
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

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('sold.copart') }}" enctype="multipart/form-data">
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


        </div>


    @if(isset($csv_header))
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Expected Header for CSV File</h5>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width:40%;">Sr.#</th>
                            <th style="width:40%;">Column</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($csv_header as $key => $value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(isset($vehicles_not_found))
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Vehicles not found in the system</h5>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width:40%;">Sr.#</th>
                            <th style="width:40%;">LOT Number</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($vehicles_not_found as $key => $value)
                            <tr>
                                <td>{{ $key +1 }}</td>
                                <td>{{ $value['lot'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

@endsection
