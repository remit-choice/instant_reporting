    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @if (session('failed'))
                        <div id="failed" class="alert alert-default-danger alert-dismissible fade show"
                            role="alert">
                            <strong>{{ session('failed') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{$module_name}}</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">{{$module_name}}</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="success" class="alert alert-default-success alert-dismissible fade show"
                                    role="alert" style="display: none">
                                    <strong class="">{{ session('success') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mt-2">
                                    <a type="button" href="#" data-toggle="modal" data-target="#create_modal"
                                        class="border px-3 btn" style="background-color: #091E3E;color: white">
                                        Add
                                    </a>
                                </div>