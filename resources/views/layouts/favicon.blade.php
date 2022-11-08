

@if (config('app.env')=='production')
    <link rel="shortcut icon" href="{{ secure_asset('assets/dist/img/Admin_logo.png') }}" type="image/x-icon" />
@else
    <link rel="shortcut icon" href="{{ asset('assets/dist/img/Admin_logo.png') }}" type="image/x-icon" />
@endif