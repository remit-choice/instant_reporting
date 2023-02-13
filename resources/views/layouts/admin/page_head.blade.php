<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@Include('layouts.links.admin.title') | {{$module_name}}</title>
    @Include('layouts.favicon')
    @yield('page_head_content')
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
        .flex-wrap {
            float: right !important;
        }
        .dropdown-menu {
            min-width: 0 !important;
            padding: 0.375rem 0.75rem !important;
        }
        form{
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>
    <script>
            setTimeout(function() {
                $('#failed').slideUp('slow');
            }, 3000);
    </script>
</head>


