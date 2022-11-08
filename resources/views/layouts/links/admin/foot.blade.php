 
 @if (config('app.env')=='production')
<!-- jQuery -->
    <script src="{{ secure_asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ secure_asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ secure_asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ secure_asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ secure_asset('assets/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ secure_asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ secure_asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ secure_asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ secure_asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ secure_asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ secure_asset('assets/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ secure_asset('assets/dist/js/demo.js') }}"></script> --}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ secure_asset('assets/dist/js/pages/dashboard.js') }}"></script>
@else
        <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ asset('assets/dist/js/demo.js') }}"></script> --}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script>
@endif