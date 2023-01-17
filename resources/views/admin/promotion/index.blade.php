@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Promotion
                    <span class="d-block text-muted pt-2 font-size-sm">Data Promotion</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="/admin/promotion/create" class="btn btn-primary font-weight-bolder">
            <span class="svg-icon svg-icon-md">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <circle fill="#000000" cx="9" cy="15" r="6" />
                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>New Record</a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_promotions" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Voucher Name</th>
                    <th>Voucher Description</th>
                    <th>Image</th>
                    <th>Minimum Payment</th>
                    <th>Maximum Promo</th>
                    <th>Discount Type</th>
                    <th>Value Discount</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Last Modified</th>
                    <th>Modified By</th>
                </tr>
                </thead>
            </table>

{{--            <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th title="Field #1">No</th>--}}
{{--                    <th title="Field #2">Voucher Name</th>--}}
{{--                    <th title="Field #3">Voucher Description</th>--}}
{{--                    <th title="Field #4">Image</th>--}}
{{--                    <th title="Field #5">Voucher Code</th>--}}
{{--                    <th title="Field #6">Minimum Payment</th>--}}
{{--                    <th title="Field #7">Maximum Promo</th>--}}
{{--                    <th title="Field #8">Discount Type</th>--}}
{{--                    <th title="Field #9">Value Discount</th>--}}
{{--                    <th title="Field #10">Status</th>--}}
{{--                    <th title="Field #11">Start Date</th>--}}
{{--                    <th title="Field #12">End Date</th>--}}
{{--                    <th title="Field #13">Created By</th>--}}
{{--                    <th title="Field #14">Created At</th>--}}
{{--                    <th title="Field #15">Updated By</th>--}}
{{--                    <th title="Field #16">Updated At</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                </tbody>--}}
{{--            </table>--}}
            <!--end: Datatable-->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/promotion.js')}}" type="application/javascript" ></script>
@endsection
