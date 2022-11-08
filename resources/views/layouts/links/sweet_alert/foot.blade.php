<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->


  
@if (config('app.env')=='production')
   <script src="{{ secure_asset('/assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ secure_asset('/assets/plugins/toastr/toastr.min.js') }}"></script>
@else
    <script src="{{ asset('/assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/toastr/toastr.min.js') }}"></script>
@endif