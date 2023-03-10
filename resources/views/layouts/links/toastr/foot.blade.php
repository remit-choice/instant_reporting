<!-- SweetAlert2 -->
{{-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> --}}

<!-- Toastr -->
<script type="text/javascript">
    $(document).ready(function() {
        $("#tablecontents").sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                var sort = [];
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('tr.row1').each(function(index, element) {
                    sort.push({
                        id: $(this).attr('data-id'),
                        position: index + 1
                    });
                });
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('admin.module.group.edit') }}",
                    data: {
                        sort: sort,
                    },
                    success: function(response) {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        if (response == '1') {
                            toastr.success('Updated Successfully');
                            //             setTimeout(function () {
                            //   location.reload(true);
                            // }, 6000);
                        } else {
                            console.log(response);
                        }
                    }
                });
            }
        });
        $(".tablecontents2").sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                var sort = [];
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('tr.row2').each(function(index, element) {
                    sort.push({
                        id: $(this).attr('data-id'),
                        position: index + 1
                    });
                });
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('admin.module.edit') }}",
                    data: {
                        sort: sort,
                    },
                    success: function(response) {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        if (response == '1') {
                            toastr.success('Updated Successfully');
                        } else {
                            console.log(response);
                        }
                    }
                });
            }
        });
    });
</script>
