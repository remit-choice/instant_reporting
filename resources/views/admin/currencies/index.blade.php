    @extends('layouts.admin.master')
    @section('content')
    @section('links_content_head')
        @Include('layouts.links.datatable.head')
        @Include('layouts.links.toastr.head')
    @endsection
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $module_name }} List</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-capitalize">#</th>
                                        <th class="text-capitalize">name</th>
                                        <th class="text-capitalize">iso</th>
                                        <th class="text-capitalize">iso3</th>
                                        <th class="text-capitalize">dial</th>
                                        <th class="text-capitalize">currency</th>
                                        <th class="text-capitalize">currency_name</th>
                                        <th class="text-capitalize">min rate</th>
                                        <th class="text-capitalize">max rate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($currencies as $currency)
                                        <tr>
                                            <td>{{ $counter++ }} <input type="hidden" name="db_currency_id"
                                                    class="db_currency_id" value="{{ $currency->id }}">
                                            </td>
                                            <td>{{ $currency->name }}</td>
                                            <td>{{ $currency->iso }}</td>
                                            <td>{{ $currency->iso3 }}</td>
                                            <td>{{ $currency->dial }}</td>
                                            <td>{{ $currency->currency }}</td>
                                            <td>{{ $currency->currency_name }}</td>
                                            <td id="min_rate{{ $currency->id }}">
                                                {{ $currency->min_rate }} <input type="hidden" name="db_currency_id"
                                                    class="db_currency_min_rate" value="{{ $currency->min_rate }}">
                                            </td>
                                            <td id="max_rate{{ $currency->id }}">
                                                {{ $currency->max_rate }} <input type="hidden" name="db_currency_id"
                                                    class="db_currency_max_rate" value="{{ $currency->max_rate }}">
                                            </td>
                                            <td>
                                                @if ($edit == 1)
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu edit_currency"
                                                            aria-labelledby="dropdownMenuButton">
                                                            @if ($edit == 1)
                                                                <li>
                                                                    <a class="dropdown-item" type="button"
                                                                        href="#" data-toggle="modal"
                                                                        data-target="#edit_modal">
                                                                        Edit</a>
                                                                </li>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-capitalize">#</th>
                                        <th class="text-capitalize">name</th>
                                        <th class="text-capitalize">iso</th>
                                        <th class="text-capitalize">iso3</th>
                                        <th class="text-capitalize">dial</th>
                                        <th class="text-capitalize">currency</th>
                                        <th class="text-capitalize">currency_name</th>
                                        <th class="text-capitalize">min rate</th>
                                        <th class="text-capitalize">max rate</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <div class="modal fade" id="edit_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $module_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.currencies.edit') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div id="failes" class="alert alert-default-danger alert-dismissible fade show"
                            role="alert" style="display: none">
                            <span class="text_fails"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <input type="hidden" name="currency_id" id="currency_id" value="">
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="currency_min_rate" class="text-capitalize">min rate</label>
                                <div class="input-group">
                                    <input type="text" name="currency_min_rate" id="currency_min_rate"
                                        class="form-control">
                                </div>
                                <span class="invalid-feedback" id="currency_min_rate_error">
                                </span>
                            </div>
                            <div class="col-6">
                                <label for="currency_max_rate" class="text-capitalize">max rate</label>
                                <div class="input-group">
                                    <input type="text" name="currency_max_rate" id="currency_max_rate"
                                        class="form-control">
                                </div>
                                <span class="invalid-feedback" id="currency_max_rate_error">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary update">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @section('links_content_foot')
        @Include('layouts.links.datatable.foot')
        @Include('layouts.links.sweet_alert.foot')
        <script type="text/javascript">
            $('.edit_currency').on('click', function() {
                var _this = $(this).parents('tr');
                $('#currency_min_rate').val(_this.find('.db_currency_min_rate').val());
                $('#currency_max_rate').val(_this.find('.db_currency_max_rate').val());
                $('#currency_id').val(_this.find('.db_currency_id').val());
            });
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#currency_id').val();
                    var min_rate = $('#currency_min_rate').val();
                    var max_rate = $('#currency_max_rate').val();

                    console.log(id);
                    console.log(min_rate);
                    console.log(max_rate);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (min_rate != '' && max_rate != '' && min_rate < max_rate) {
                        Swal.fire({
                            title: 'Are you sure?',
                            icon: 'warning',
                            confirmButtonColor: '#e64942',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: `No`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: "post",
                                    url: "{{ route('admin.currencies.edit') }}",
                                    data: {
                                        "id": id,
                                        "min_rate": min_rate,
                                        "max_rate": max_rate,
                                    },
                                    success: function(response) {
                                        if (response == '1') {
                                            Swal.fire(
                                                'Updated!',
                                                'Data Successfully Updated.!',
                                                'success'
                                            ).then((result) => {
                                                $('#edit_modal').modal('hide');
                                                $("#min_rate" + id).load(location
                                                    .href +
                                                    " #min_rate" + id);
                                                $("#min_rate" + id).addClass(
                                                    'cur-rate td');
                                                $("#max_rate" + id).load(location
                                                    .href +
                                                    " #max_rate" + id);
                                                $("#max_rate" + id).addClass(
                                                    'cur-rate td');
                                            });
                                        }
                                    },
                                    error: (error) => {
                                        console.log(JSON.stringify(error));
                                    }
                                });
                            }
                        });
                    } else {
                        if (min_rate == '') {
                            $('#currency_min_rate_error').show();
                            $('#currency_min_rate_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#currency_min_rate_error').slideUp('slow');
                                $('#currency_min_rate_error').empty();
                            }, 4000);
                        }
                        if (max_rate == '') {
                            $('#currency_max_rate_error').show();
                            $('#currency_max_rate_error').html(
                                "Required!");
                            window.setInterval(function() {
                                $('#currency_max_rate_error').slideUp('slow');
                                $('#currency_max_rate_error').empty();
                            }, 4000);
                        }
                        if (min_rate > max_rate) {
                            $('#failes').show();
                            $('#failes .text_fails').html(
                                "Minimum rate high from maximum rate!");
                            window.setInterval(function() {
                                $('#failes').slideUp('slow');
                                $('#failes .text_fails').empty();
                            }, 4000);
                        } else if (min_rate == max_rate) {
                            $('#failes').show();
                            $('#failes .text_fails').html(
                                "Minimum rate is equal to maximum rate!");
                            window.setInterval(function() {
                                $('#failes').slideUp('slow');
                                $('#failes .text_fails').empty();
                            }, 4000);
                        } else {
                            $('#failes').show();
                            $('#failes .text_fails').html(
                                "Maximum rate less from Minimum rate!");
                            window.setInterval(function() {
                                $('#failes').slideUp('slow');
                                $('#failes .text_fails').empty();
                            }, 4000);
                        }
                    }
                });
            });
        </script>
    @endsection
@endsection
