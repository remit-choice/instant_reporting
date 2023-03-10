@extends('layouts.admin.master')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <!-- /.card-header -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if (Session::has('image'))
                                    <img class="profile-user-img img-fluid img-circle" src="{{ Session::get('image') }}"
                                        alt="User profile picture">
                                @else
                                    <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}"
                                        class="profile-user-img img-fluid img-circle" alt="User profile picture">
                                @endif
                            </div>

                            <h3 class="profile-username text-center">{{ Session::get('full_name') }}</h3>

                            <p class="text-muted text-center">{{ Session::get('designation') }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Followers</b> <a class="float-right">1,322</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Following</b> <a class="float-right">543</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Friends</b> <a class="float-right">13,287</a>
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card-body -->
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
