<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    
    <meta charset="utf-8" />
    <title>Hotdeal | Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login page example" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{asset('/assets/css/pages/login/classic/login-1.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <!--begin::Layout Themes(used by all pages)-->
    <link href="{{asset('assets/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/png">
    <link rel="stylesheet" href="{{asset('assets/css/datatables.min.css')}}">

    <script src="{{asset('assets/plugins/custom/tinymce/tinymce.bundle.js')}}"></script>
    <style>
        textarea {
            all: revert;
            white-space: pre-line;
        }
        .text-gray-100 {
            color: #F5F8FA !important;
        }
        .fw-bolder {
            font-weight: 600 !important;
        }
        .fs-2 {
            font-size: 1.5rem !important;
        }
    </style>
</head>
<!--end::Head-->

<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
@if(Auth::check())
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            @include('layouts/aside2')
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                @include('layouts.header')
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    @include('layouts.subheader')
                </div>
            @include('layouts.footer')
            </div>
        </div>
    </div>
@include('layouts.panel')
@else
    @yield('content')
@endif

<script src="{{asset('/assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>

<script src="{{asset('/assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('/assets/js/core.datatable.js')}}"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{asset('assets/js/pages/custom/login/login-general.js')}}"></script>

<script src="{{asset('assets/js/pages/crud/ktdatatable/base/html-table.js')}}"></script>
<script src="{{asset('assets/js/pages/crud/forms/widgets/bootstrap-datetimepicker.js')}}"></script>
<script src="{{asset('assets/js/datatables.min.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--end::Page Scripts-->
@yield('js');
</body>
<!--end::Body-->
</html>


