<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            @if (session('success'))
                <div id="success" class="alert alert-default-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @elseif (session('failed'))
                <div id="failed" class="alert alert-default-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('failed') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @else
            @endif
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $module_name }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $module_name }}</li>
                    </ol>
                </div><!-- /.col -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @yield('content_1')
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    @yield('content')
</div>
