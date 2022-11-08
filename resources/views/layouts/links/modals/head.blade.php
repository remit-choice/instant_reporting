  
@if (config('app.env')=='production')
      <link rel="stylesheet" href="{{ secure_asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ secure_asset('assets/plugins/toastr/toastr.min.css') }}">
@else
   <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endif
{{--  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">--}}