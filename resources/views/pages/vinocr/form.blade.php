@extends('layouts.app')

@section('title', 'Add Vehicle')

@section('scripts')
    <script>
        $(document).ready(function() {



        });
    </script>

@endsection
@section('content')

    <h1 class="h3 mb-3">Add New Vehicle </h1>

    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if (session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif
                    @if (session('error'))
                        <x-alert type="danger">{{ session('error') }}</x-alert>
                    @endif
                    @if (session('warning'))
                        <x-alert type="warning">{{ session('warning') }}</x-alert>
                    @endif

                    <form enctype="multipart/form-data" action="{{ route('vinocr.process') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="mb-3">
                                <label class="form-label w-100">Choose an image to process:</label>
                                <input type="file" name="file" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary add-btn">Process
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





@endsection
