@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if (session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif
    <h1 class="h3 mb-3">Dashboard</h1>

    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @elseif(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @elseif(session('warning'))
        <x-alert type="warning">{{ session('warning') }}</x-alert>
    @endif

    @include('pages.dashboard._inc.stats')
    @include('pages.dashboard._inc.sold-table')
    <hr>
    @include('pages.dashboard._inc.last_30_new_vehicles')

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

    $("#checkLotButton").click(function() {
        const lotNumber = $("#lotNumberInput").val();
        fetchCopartLot(lotNumber);
    });

    function fetchCopartLot(lotNumber) {
        console.log('fetchCopartLot called');
    $.ajax({
        url: `/fetch-copart-lot/${lotNumber}`,
        method: "GET",
        dataType: "json",
        success: function(response) {
            if (response.data && response.data.lotDetails) {
                let hcrValue = response.data.lotDetails.hcr;
                let saleType = hcrValue ? "Insurance Sale" : "Dealer Sale";
                let textColor = hcrValue ? "green" : "red"; 
                let resultHTML = `<span style="color: ${textColor};">${saleType}</span>`;
                $("#lot_status").html(resultHTML); 

            } else {
                showError();
            }
        },
        error: function() {
            showError();
        }
    });
}

    function showError() {
    $("#lot_status").html("<span style='color: #e73939;'>Error fetching lot details.</span>"); 
}
});
</script>
@endsection