@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">Dashboard</h1>

    @include('pages.dashboard._inc.stats')
@endsection
