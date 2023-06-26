@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')

    <h1 class="h3 mb-3">CSV Headers</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- field_mapping.blade.php -->
                    <form action="{{ route('field.mapping.save') }}" method="POST">
                        @csrf
                        <table>
                            <thead>
                            <tr>
                                <th>Database Field</th>
                                <th>CSV Header</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($fields as $field)
                                <tr>
                                    <td>{{ $field }}</td>
                                    <td>
                                        <select name="mapping[{{ $field }}]">
                                            <option value="">Select CSV Header</option>
                                            @foreach ($headers as $header)
                                                <option value="{{ $header }}">{{ $header }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="submit">Save Mapping</button>
                    </form>

                </div>
            </div>
        </div>
    </div>



@endsection


