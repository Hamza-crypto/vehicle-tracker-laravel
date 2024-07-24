@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">Dashboard</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @elseif(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @elseif(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif

    @include('pages.dashboard._inc.stats')
    @include('pages.dashboard._inc.sold-table')
@endsection
