@extends('layouts.app')
@section('content')
    <style type="text/css">
        .horizontal-scrollable > .row {
            overflow-x: auto;
            white-space: nowrap;
        }

        .horizontal-scrollable > .row > .col-xs-4 {
            display: inline-block;
            float: none;
        }

        row > .col-xs-3 {
            display:flex;
            flex: 0 0 25%;
            max-width: 25%
        }

        .flex-nowrap {
            -webkit-flex-wrap: nowrap!important;
            -ms-flex-wrap: nowrap!important;
            flex-wrap: nowrap!important;
        }
        .flex-row {
            display:flex;
            -webkit-box-orient: horizontal!important;
            -webkit-box-direction: normal!important;
            -webkit-flex-direction: row!important;
            -ms-flex-direction: row!important;
            flex-direction: row!important;
        }
    </style>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Upload Mass Product</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <span class="example-toggle" data-toggle="tooltip" title="View code"></span>
                                    <span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form from-prevent-multiple-submits" id="upload_product_form"  enctype="multipart/form-data" >
                            <div class="card-body">
                                <div class="mb-15">

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Vendor:</label>
                                        <div class="col-6 col-form-label">
                                            <select class="form-control select2" id="kt_select2_11" name="vendor_id">
                                                @foreach($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">File:</label>
                                        <div class="col-6 col-form-label">
                                            <div class="input-group date mb-2">
                                                <input type="file" name="file" class="form-control" placeholder="Top left" >
                                                <div class="input-group-append">
                                                    <a class="input-group-text" href="/admin/product/mass/download">
                                                        <i class="la la-arrow-circle-down"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" id="btn_create_voucher" class="btn btn-success mr-2 from-prevent-multiple-submits">Submit</button>
                                        <button type="button" id="btn_cancel" class="btn btn-secondary">Cancel</button>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->

                    <div class="card card-custom gutter-b">
                        <div class="card-header flex-wrap py-3">
                            <div class="card-title">
                                <h3 class="card-label">Log File
                                    <span class="d-block text-muted pt-2 font-size-sm">sorting &amp; pagination remote datasource</span></h3>
                            </div>

                        </div>
                        <div class="card-body">
                            <!--begin: Datatable-->
                            <table class="table" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Path</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($file as $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$val->path}}</td>
                                            <td>{{$val->created_at}}</td>
                                            <td>{{$val->created_by}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end: Datatable-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal-->
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ url('/assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/product.js') }}" type="application/javascript" ></script>
@endsection

