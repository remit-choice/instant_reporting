<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Transactions</title>
    <style>
        .dt-buttons {
            float: right !important;
        }

        .cur-rate>td {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        #example1 {
            width: 100% !important;
        }

        .table-responsive {
            display: inline-table !important;
        }

        input[type=date]:focus {
            outline: none;
        }
    </style>
    @Include('layouts.favicon')
    @Include('layouts.links.admin.head')
    @Include('layouts.links.datatable.head')
    {{-- @Include('layouts.links.selectpciker.head') --}}
    {{-- @Include('layouts.links.modals.head') --}}
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    @extends('layouts.admin.master')
    @section('content')
        <div class="wrapper">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Transactions</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Transactions</li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header row">
                                        <h3 class="card-title col-lg-6 col-md-6 col-sm-6 col-xs-6">Transactions List</h3>
                                        <div class="dates col-lg-6 col-md-6 col-sm-6 col-xs-6 d-flex justify-content-end">
                                            <form action="{{ route('admin.accounts.transactions') }}" method="get">
                                                {{-- {!! csrf_field() !!} --}}
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <select name="filter[]" id="" class="p-2" multiple>
                                                    <option value="" disabled hidden selected>Select</option>
                                                    <option value="GBP">GBP</option>
                                                    <option value="beneficiary_country">Benificiary Country</option>
                                                </select>
                                                {{-- <select class="select2" multiple="multiple"
                                                    data-placeholder="Select a State" style="width: 100%;">
                                                    <option>Alabama</option>
                                                    <option>Alaska</option>
                                                    <option>California</option>
                                                    <option>Delaware</option>
                                                    <option>Tennessee</option>
                                                    <option>Texas</option>
                                                    <option>Washington</option>
                                                </select> --}}
                                                @if (Request::get('date_from'))
                                                    <input type="date" name="date_from" id="" class="p-1"
                                                        value="{{ Request::get('date_from') }}">
                                                @else
                                                    <input type="date" name="date_from" id="" class="p-1"
                                                        value="">
                                                @endif
                                                @if (!empty(Request::get('date_to')))
                                                    <input type="date" name="date_to" id="" class="p-1"
                                                        value="{{ Request::get('date_to') }}">
                                                @else
                                                    <input type="date" name="date_to" id="" class="p-1"
                                                        value="">
                                                @endif
                                                <button type="submit" name="filter" class="btn mb-2"
                                                    style="background-color: #091E3E;color: white">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1"
                                            class="table table-bordered @php if(!empty($transactions_data)){echo "table-responsive";}else{} @endphp">
                                            <thead>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">beneficiary_country</th>
                                                    <th class="text-capitalize">Count of Tr_No</th>
                                                    <th class="text-capitalize">Sum of Volume in GBP</th>
                                                    <th class="text-capitalize">Sum Fx in GBP</th>
                                                    <th class="text-capitalize">Sum Charges in GBP</th>
                                                    <th class="text-capitalize">Sum of SSRL Charges</th>
                                                    <th class="text-capitalize">Sum FX Loss</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($transactions_data))
                                                    @php $counter = 1; @endphp
                                                    @foreach ($transactions as $transaction)
                                                        <tr>
                                                            <td>{{ $counter++ }}</td>
                                                            <td>{{ $transaction->beneficiary_country }}</td>
                                                            <td>{{ $count_of_tr_no }}</td>
                                                            <td>
                                                                @if ($transaction->payin_ccy == 'GBP')
                                                                    {{ $transaction->payin_amt - $transaction->admin_charges }}
                                                                @elseif ($transaction->payin_ccy != 'GBP')
                                                                    @php
                                                                        $rates = function ($query) {
                                                                            $query->where('status', 1);
                                                                        };
                                                                        $currency_rates = App\Models\Currency::where('currency', $transaction->payin_ccy)
                                                                            ->whereHas('rates', $rates)
                                                                            ->with('rates', $rates)
                                                                            ->get();
                                                                        foreach ($currency_rates as $currency_rate) {
                                                                            $rate = $currency_rate->rates['rate'];
                                                                        }
                                                                        $GBP_convert = round($transaction->payin_amt / $rate, 2) - $transaction->admin_charges;
                                                                    @endphp
                                                                    {{ $GBP_convert }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $fx_in_fc = (($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate;
                                                                    $fx_in_fc_round = round($fx_in_fc, 2);
                                                                @endphp
                                                                {{ $fx_in_fc_round }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $fx_in_fc = (($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate;
                                                                    $fx_in_fc_round = round($fx_in_fc, 2);
                                                                @endphp
                                                                @if ($transaction->payin_ccy == 'GBP')
                                                                    {{ $fx_in_fc_round }}
                                                                @elseif ($transaction->payin_ccy != 'GBP')
                                                                    @php
                                                                        $rates = function ($query) {
                                                                            $query->where('status', 1);
                                                                        };
                                                                        $currency_rates = App\Models\Currency::where('currency', $transaction->payin_ccy)
                                                                            ->whereHas('rates', $rates)
                                                                            ->with('rates', $rates)
                                                                            ->get();
                                                                        foreach ($currency_rates as $currency_rate) {
                                                                            $rate = $currency_rate->rates['rate'];
                                                                        }
                                                                        // $fx_in_GBP = abs(round($fx_in_fc_round / $rate, 2));
                                                                        $fx_in_GBP = round($fx_in_fc_round / $rate, 2);
                                                                    @endphp
                                                                    {{ $fx_in_GBP }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($transaction->payin_ccy == 'GBP')
                                                                    {{ $transaction->admin_charges }}
                                                                @elseif ($transaction->payin_ccy != 'GBP')
                                                                    @php
                                                                        $rates = function ($query) {
                                                                            $query->where('status', 1);
                                                                        };
                                                                        $currency_rates = App\Models\Currency::where('currency', $transaction->payin_ccy)
                                                                            ->whereHas('rates', $rates)
                                                                            ->with('rates', $rates)
                                                                            ->get();
                                                                        foreach ($currency_rates as $currency_rate) {
                                                                            $rate = $currency_rate->rates['rate'];
                                                                        }
                                                                        $charges_in_GBP = round($transaction->admin_charges / $rate, 2);
                                                                    @endphp
                                                                    {{ $charges_in_GBP }}
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $fx_in_fc = (($transaction->buyer_dc_rate - $transaction->agent_rate) * ($transaction->payin_amt - $transaction->admin_charges)) / $transaction->buyer_dc_rate;
                                                                    $fx_in_fc_round = round($fx_in_fc, 2);
                                                                    
                                                                    $rates = function ($query) {
                                                                        $query->where('status', 1);
                                                                    };
                                                                    $currency_rates = App\Models\Currency::where('currency', $transaction->payin_ccy)
                                                                        ->whereHas('rates', $rates)
                                                                        ->with('rates', $rates)
                                                                        ->get();
                                                                    foreach ($currency_rates as $currency_rate) {
                                                                        $rate = $currency_rate->rates['rate'];
                                                                    }
                                                                    $fx_in_GBP = round($fx_in_fc_round / $rate, 2);
                                                                    $charges_in_GBP = round($transaction->admin_charges / $rate, 2);
                                                                    $fx_loss = $fx_in_GBP + $charges_in_GBP;
                                                                    if ($fx_loss > 0) {
                                                                        $fx_loss = 0;
                                                                    } elseif ($fx_loss < 0) {
                                                                        $fx_loss = $fx_in_GBP + $charges_in_GBP;
                                                                    } else {
                                                                    }
                                                                @endphp
                                                                {{ $fx_loss }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">beneficiary_country</th>
                                                    <th class="text-capitalize">Count of Tr_No</th>
                                                    <th class="text-capitalize">Sum of Volume in GBP</th>
                                                    <th class="text-capitalize">Sum Fx in GBP</th>
                                                    <th class="text-capitalize">Sum Charges in GBP</th>
                                                    <th class="text-capitalize">Sum of SSRL Charges</th>
                                                    <th class="text-capitalize">Sum FX Loss</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        </div>
        <div class="modal fade" id="Transactions_update">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Transactions</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.currencies.update') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="Transactions_rate" class="text-capitalize">rate</label>
                                <input type="hidden" name="Transactions_id" id="Transactions_id" value="">
                                <input type="text" name="Transactions_rate" id="Transactions_rate"
                                    class="form-control">
                                {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                                else.</small> --}}
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
        @Include('layouts.links.datatable.foot')
        {{-- @Include('layouts.links.selectpciker.foot') --}}
        <!-- /.modal -->
        <script type="text/javascript">
            $(function() {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
            });
            $('.edit_Transactions').on('click', function() {
                var _this = $(this).parents('tr');
                $('#Transactions_rate').val(_this.find('.db_Transactions_rate').val());
                $('#Transactions_id').val(_this.find('.db_Transactions_id').val());
            });
            $(document).ready(function() {
                $('.update').click(function(e) {
                    e.preventDefault();
                    var id = $('#Transactions_id').val();
                    var rate = $('#Transactions_rate').val();

                    console.log(id);
                    console.log(rate);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    swal({
                            title: "Are you sure?",
                            text: "Once Update, you will not be able to recover this imaginary rate!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                $.ajax({
                                    type: "post",
                                    url: "{{ route('admin.currencies.update') }}",
                                    data: {
                                        "id": id,
                                        "rate": rate,
                                    },
                                    success: function(response) {
                                        if (response == '1') {
                                            $('#Transactions_update').modal('hide');
                                            $("#tr" + id).load(location.href +
                                                " #tr" + id);
                                            $("#tr" + id).addClass('cur-rate td');
                                        }
                                    },
                                    error: (error) => {
                                        console.log(JSON.stringify(error));
                                    }
                                });
                            }
                        });

                });
            });
        </script>
    @endsection

    {{-- @Include('layouts.links.modals.foot') --}}
</body>

</html>
