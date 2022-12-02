
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<script>
    $(function() {
        $("#example1").DataTable({
            "paging": true,
            "autoWidth": true,
            "searching": true,
            "responsive": true,
            "order": [[0, 'asc']],
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            // "pageLength": 10,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo(
            '#example1_wrapper .col-md-6:eq(1)');
         
        $("#example2").DataTable({
            "paging": true,
            "autoWidth": true,
            "searching": true,
            "responsive": true,
            "order": [[0, 'asc']],
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],

            // "pageLength": 10,
        });
    });
    //   $('#example1 tbody').on('click', 'tr.sub_table', function() {
    //     // alert(1);
    //     $(`.sub_table_dt`).DataTable();
    //     //  $('.sub_table').each(function(index, tableElement) {
    //     //           let table = $(`.sub_table_dt`).DataTable({
    //     //     responsive: true,
    //     //     stateSave: true
    //     // });

    //     // tableList.push(table);
    //     // });
    // });

    // function getChildren($row) {
    //     var children = [];
    //     while ($row.next().hasClass('child')) {
    //         children.push($row.next());
    //         $row = $row.next();
    //     }
    //     return children;
    // }

    // $('.parent').on('click', function () {

    //     var children = getChildren($(this));
    //     $.each(children, function () {
    //         $(this).toggle();
    //     })
    // });

    // $.each($('.parent'), function () {

    //     var children = getChildren($(this));
    //     $.each(children, function () {
    //         $(this).toggle();
    //     })

    // });
</script>
