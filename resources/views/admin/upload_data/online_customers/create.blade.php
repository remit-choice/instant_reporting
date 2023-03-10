@extends('layouts.admin.master')
@section('content')
@section('links_content_head')
    @Include('layouts.links.datatable.head')
@endsection
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-1">
            </div>

            <div class="col-10">
                <form action="{{ route('admin.upload_data.online_customers.create') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputFile mb-3">Upload File</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" name="online_customer_file" class="custom-file-input"
                                    id="exampleInputFile" accept=".csv">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                        </div>
                        <button type="submit" class="btn mb-3"
                            style="background-color: #091E3E;color: white">Upload</button>
                        <a href="{{ route('admin.upload_data.online_customers.index') }}" class="btn mb-3"
                            style="background-color: #091E3E;color: white">Back</a>
                    </div>
                </form>
                <!-- /.card -->
            </div>
            <div class="col-1">

            </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@section('links_content_foot')
    @Include('layouts.links.datatable.foot')
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
@endsection
@endsection
