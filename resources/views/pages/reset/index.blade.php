@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="h3 mb-3">Data Reset Successfully</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

@endsection
