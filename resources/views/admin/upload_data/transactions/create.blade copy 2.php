<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | Transactions</title>
    <style>
        .flex-wrap {
            float: right !important;
        }

        .cur-rate>td {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }
    </style>
    @Include('layouts.favicon')
    @Include('layouts.links.admin.head')
    @Include('layouts.links.dropzone.head')
    <script>
        setTimeout(function() {
            $('#success').slideUp('slow');
            $('#failed').slideUp('slow');
        }, 5000);
    </script>

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
                        <div class="col-1">
                        </div>
                        <div class="col-10">
                          <div class="card card-default">
                            <div class="card-body">
                              <div id="actions" class="row">
                                <div class="col-lg-6">
                                  <div class="btn-group w-100">
                                    <span class="btn btn-success col fileinput-button">
                                      <i class="fas fa-plus"></i>
                                      <span>Add files</span>
                                    </span>
                                    <button type="submit" class="btn btn-primary col start">
                                      <i class="fas fa-upload"></i>
                                      <span>Start upload</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning col cancel">
                                      <i class="fas fa-times-circle"></i>
                                      <span>Cancel upload</span>
                                    </button>
                                  </div>
                                </div>
                                <div class="col-lg-6 d-flex align-items-center">
                                  <div class="fileupload-process w-100">
                                    <div id="total-progress" class="progress progress-striped active" role="progressbar"
                                      aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                      <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="table table-striped files" id="previews">
                                <div id="template" class="row mt-2">
                                  <div class="col-auto">
                                    <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                  </div>
                                  <div class="col d-flex align-items-center">
                                    <p class="mb-0">
                                      <span class="lead" data-dz-name></span>
                                      (<span data-dz-size></span>)
                                    </p>
                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                  </div>
                                  <div class="col-4 d-flex align-items-center">
                                    <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0"
                                      aria-valuemax="100" aria-valuenow="0">
                                      <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                    </div>
                                  </div>
                                  <div class="col-auto d-flex align-items-center">
                                    <div class="btn-group">
                                      <button class="btn btn-primary start">
                                        <i class="fas fa-upload"></i>
                                        <span>Start</span>
                                      </button>
                                      <button data-dz-remove class="btn btn-warning cancel">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Cancel</span>
                                      </button>
                                      <button data-dz-remove class="btn btn-danger delete">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.card -->
                        </div>
                        <div class="col-1">
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    </div>
    @endsection
    @Include('layouts.links.admin.foot')
    @Include('layouts.links.dropzone.foot')
</body>

</html>