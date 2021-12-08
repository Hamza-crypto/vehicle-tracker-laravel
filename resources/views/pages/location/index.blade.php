@extends('layouts.app')

@section('title', 'Gateways')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#gateway-table').DataTable();
        });
    </script>
@endsection



@section('content')
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">All Locations</h1>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="gateway-table" class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Location</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        @php  $count = 1 @endphp
                        @foreach($locations as $location)
                            <tr>
                                <td>{{ $count++ }} </td>


                                <td>{{ $location->location }}</td>

                                <td>{{ $location->created_at->diffForHumans() }}</td>
                                <td class="table-action">

                                    <form method="post" action="{{ route('locations.destroy', $location->id) }}"
                                          onsubmit="return confirmSubmission(this, 'Are you sure you want to delete location ' + '{{ "$location->location"  }}')"

                                          style="display: inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn text-danger"
                                                href="{{ route('locations.destroy', $location->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @endforeach


                        @foreach($locations2 as $location)
                            <tr>
                                <td>{{ $count++ }} </td>

                                <td>{{ $location->meta_value }}</td>

                                <td>  Added from CSV</td>

                                <td> can not be deleted </td>

                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
