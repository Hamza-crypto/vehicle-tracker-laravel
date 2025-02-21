<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title') - {{ env('APP_NAME') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link class="js-stylesheet" href="{{ asset('assets/css/light.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    @if (Request::is('vehicles/upload/*') ||
            Request::is('headers') ||
            Request::is('runlist/upload') ||
            Request::is('vinocr/form') ||
            Request::is('inventory/copart') ||
            Request::is('buy/iaai'))
        <link class="js-stylesheet2" href="{{ asset('assets/css/dropify.min.css') }}" rel="stylesheet">
    @endif
    @yield('styles')


</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">


    <div class="wrapper">

        @include('includes.aside')

        <div class="main">
            @include('includes.header')

            <main class="content">
                <div class="container-fluid p-0">

                    @yield('content')
                </div>
            </main>

            @include('includes.footer')
        </div>
    </div>


    <script src="{{ asset('/assets/js/app.js') }}"></script>

    {{-- <script src="{{ mix('/js/app.js') }}"></script> --}}

    <script>
        $(".select2").each(function() {
            $(this).select2();
        });

        
        $(document).ready(function() {
            $("#checkLotLink").on("click", function(event) {
                event.preventDefault(); // Prevent default link behavior

                Swal.fire({
                    title: "Enter Lot Number",
                    input: "text",
                    inputPlaceholder: "Enter Copart Lot Number",
                    showCancelButton: true,
                    confirmButtonText: "Check",
                    preConfirm: (lotNumber) => {
                        if (!lotNumber.trim()) {
                            Swal.showValidationMessage("Please enter a valid lot number.");
                            return false;
                        }
                        return fetchCopartLot(lotNumber);
                    }
                });
            });
        });

        function fetchCopartLot(lotNumber) {
            $.ajax({
    url: `/fetch-copart-lot/${lotNumber}`,  // Your Laravel backend
    method: "GET",
    dataType: "json",
    success: function(response) {
        if (response.data && response.data.lotDetails) {
            let hcrValue = response.data.lotDetails.hcr;
            let saleType = hcrValue ? "Insurance Sale" : "Dealer Sale";

            Swal.fire({
                title: saleType,
                text: ``,
                icon: hcrValue ? "success" : "info"
            });
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
            Swal.fire("Error", "Invalid Lot Number or No Data Found", "error");
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.2/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/js/alerts.js') }}"></script>


    @yield('alert')
    @yield('scripts')

    @if (Request::is('vehicles/upload/*') ||
            Request::is('headers') ||
            Request::is('runlist/upload') ||
            Request::is('vinocr/form') ||
            Request::is('inventory/copart') ||
            Request::is('buy/iaai'))
        <script src="{{ asset('assets/js/dropify.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('input[type="file"]').addClass('dropify');
                $('.dropify').dropify();
            });
        </script>
    @endif

    @if(Request::has('type'))
    <script>
        $('[data-target="#modal-vehicle-detail"]').css('color', '#495057');
    </script>
    @endif

    <script src="{{ asset('/assets/js/vehicle-modal.js') }}"></script>


</body>

</html>
