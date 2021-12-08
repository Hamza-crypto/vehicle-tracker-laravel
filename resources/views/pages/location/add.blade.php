@extends('layouts.app')

@section('title', 'Add Location')

@section('content')

    <h1 class="h3 mb-3">Add New Location</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    @endif

                    @if(session('error'))
                        <x-alert type="danger">{{ session('error') }}</x-alert>
                    @endif


                    <form method="post" action="{{ route('locations.store') }}">
                        @csrf

                        <x-input type="text" label="Location" placeholder="Enter Location"></x-input>

                        <div class="form-group">

                            <button type="submit" class="btn btn-lg btn-primary">Add New Location</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
