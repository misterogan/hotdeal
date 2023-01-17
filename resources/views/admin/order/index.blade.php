@extends('layouts.app')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Invoice
                    <span class="d-block text-muted pt-2 font-size-sm">Invoice Data</span></h3>
            </div>
        </div>
        <div class="toolbar" id="kt_toolbar">
            <!--begin::Container-->
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="w-100 page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
               
                    <div class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1 w-100">
                        <!--begin::Item-->
                        <div class="col-lg-12 d-flex mb-4">
                            <div class="col-lg-2">
                                <select name="filter_id" id="filter_id" class="form-control">
                                    <option value="" disabled>Filter</option>
                                    <option value="1">TRX ID</option>
                                    <option value="2">Invoice Number</option>
                                    <option value="3">Product</option>
                                    <option value="4">Vendor</option>
                                    {{-- <option value="5">Payment Method</option> --}}
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" name="filter" id="filter" class="form-control" placeholder="Masukkan Nomor Invoice"/>
                            </div>
                            <div class="col-lg-3 d-flex">
                                <form action="" class="d-flex">
                                    <div class="col-lg-5" style="display: flex; align-items: center;">
                                        <label class="text-center" style="margin-bottom: 0;">Waktu Pesanan Dibuat</label>
                                    </div>
                                    <div class="col-lg-5" style="display: flex; align-items: center;">
                                        <input type="date" name="start_date" id="start_date" class="form-control " placeholder="dd/mm/yyyy"/>
                                        <input type="date" name="end_date" id="end_date" class="form-control mx-2" placeholder="dd/mm/yyyy"/>
                                        <button type="button" class="btn btn-success" id="apply_filter" name="apply_filter">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-12 d-flex mx-2" style="cursor:pointer;">
                            <input type="hidden" value="" id="status_value">
                            <h4 class="card-label mx-2" style="display: inline-block"><a class="d-block filter-status text-primary pt-2 font-size-sm">Semua</a></h4>
                            <h4 class="card-label mx-2" style="display: inline-block"><a class="d-block filter-status text-muted pt-2 font-size-sm">Order Complete</a></h4>
                            <h4 class="card-label mx-2" style="display: inline-block"><a class="d-block filter-status text-muted pt-2 font-size-sm">Pesanan Dibatalkan</a></h4>
                            @foreach ($status as $item)
                                <h4 class="card-label mx-2" style="display: inline-block"><a class="d-block filter-status text-muted pt-2 font-size-sm">{{$item->description}}</a></h4>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            
            <table id="dt_orders" class="table datatable datatable-bordered datatable-head-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>No.</th>
                    <th>TRX ID</th>
                    <th>Nomor Invoice</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Vendor</th>
                    <th>Jumlah yang harus dibayarkan</th>
                    <th>Status</th>
                    <th>Waktu Pesanan Dibuat</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{url('/custom/admin/order.js')}}" type="application/javascript" ></script>
@endsection
