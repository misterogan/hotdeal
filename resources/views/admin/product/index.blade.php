@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Product
                    <span class="d-block text-muted pt-2 font-size-sm">Data Products</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
            <a href="/admin/product/create" class="btn btn-primary font-weight-bolder" style="margin-right: 20px">
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

            </span>New Product</a>
            <a href="/admin/product/mass/create" class="btn btn-info font-weight-bolder">
            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Files/Uploaded-file.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24"/>
                    <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                    <path d="M8.95128003,13.8153448 L10.9077535,13.8153448 L10.9077535,15.8230161 C10.9077535,16.0991584 11.1316112,16.3230161 11.4077535,16.3230161 L12.4310522,16.3230161 C12.7071946,16.3230161 12.9310522,16.0991584 12.9310522,15.8230161 L12.9310522,13.8153448 L14.8875257,13.8153448 C15.1636681,13.8153448 15.3875257,13.5914871 15.3875257,13.3153448 C15.3875257,13.1970331 15.345572,13.0825545 15.2691225,12.9922598 L12.3009997,9.48659872 C12.1225648,9.27584861 11.8070681,9.24965194 11.596318,9.42808682 C11.5752308,9.44594059 11.5556598,9.46551156 11.5378061,9.48659872 L8.56968321,12.9922598 C8.39124833,13.2030099 8.417445,13.5185067 8.62819511,13.6969416 C8.71848979,13.773391 8.8329684,13.8153448 8.95128003,13.8153448 Z" fill="#000000"/>
                </g>
            </svg><!--end::Svg Icon--></span>Mass Upload</a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table id="dt_products" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Vendor</th>
                    <th>Category</th>
                    <th>SKU</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Last Modified</th>
                    <th>Modified By</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/product.js')}}" type="application/javascript" ></script>
@endsection
