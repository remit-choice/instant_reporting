<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 | Page Not Found</title>
    @Include('layouts.links.admin.head')
</head>
<body>
    <!-- Main content -->
    <section class="content" style="padding: 0 20px;margin-top: 35vh;text-align: center;">
      <div class="error-page d-flex flex-column">
        <h2 class="headline text-warning"> 404</h2>
        <div>
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
          <p>
            We could not find the page you were looking for.
            Meanwhile, you may <a href="{{route('admin.dashboard.index')}}">return to dashboard</a>
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
    @Include('layouts.links.admin.foot')
</body>
</html>