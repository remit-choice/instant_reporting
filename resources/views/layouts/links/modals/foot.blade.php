
@if (config('app.env')=='production')
    <!-- SweetAlert2 -->
    <script src="{{ secure_asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ secure_asset('assets/plugins/toastr/toastr.min.js') }}"></script>
@else
   <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
@endif
