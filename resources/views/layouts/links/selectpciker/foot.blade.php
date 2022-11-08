
  
@if (config('app.env')=='production')
    <!-- Bootstrap 4 -->
    <script src="{{ secure_asset('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ secure_asset('/assets/plugins/select2/js/select2.full.min.js') }}"></script>
@else
    <script src="{{ asset('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endif
{{-- <script src="{{ asset('/assets/plugins/jquery/jquery.min.js') }}"></script> --}}
<!-- Bootstrap4 Duallistbox -->
{{-- <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script> --}}
<script type="text/javascript">
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            // placeholder: "Select",
            // allowClear: true
        });
          $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    })
</script>
