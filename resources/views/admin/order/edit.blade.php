@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Invoice Info
                            </h3>
                        </div>
                        <!--begin::Form-->
                        <form class="form" id="edit_status_form" enctype="multipart/form-data">
                            <input type="hidden" name="detail_id" id="detail_id" value="{{ $order->id }}">
                            <div class="card-body">
                                <div class="form-group row transaction-content-center">
                                    <label class="col-lg-4 col-form-label">Transaction Number:</label>
                                    <div class="col-lg-8">
                                        <a href="/admin/transaction/edit/{{ $order->transaction_number }}" class="text-muted text-hover-primary"><span class="text-primary"> {{ $order->transaction_number }}</span></a>
                                    </div>
                                </div>

                                <div class="form-group row transaction-content-center">
                                    <label class="col-lg-4 col-form-label">Total Diskon:</label>
                                    <div class="col-lg-8 transaction-mid">
                                        <span class="text-muted"><h6> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($order->total_discount) }}</span> </h6> </span>
                                    </div>
                                </div>
                                <div class="form-group row transaction-content-center">
                                    <label class="col-lg-4 col-form-label">Total Hotpoint:</label>
                                    <div class="col-lg-8 transaction-mid">
                                        <span class="text-muted"><h6> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($order->point) }}</span> </h6> </span>
                                    </div>
                                </div>
                                <div class="form-group row transaction-content-center">
                                    <label class="col-lg-4 col-form-label">Total Pembayaran:</label>
                                    <div class="col-lg-8 transaction-mid">
                                        <span class="text-muted"><h6> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($order->total_payment) }}</span> </h6> </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Invoice Number:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="" value="{{ $order->invoice_number }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Status:</label>
                                    <div class="col-lg-8">
                                        <select name="status" id="select_status" class="form-control">
                                            <option value="{{ $order->master_status->id }}" selected>{{ $order->master_status->description }}</option>
                                            @foreach ($status as $item)
                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ ucfirst($order->master_status->description) }}" disabled/> --}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Tanggal Transaksi:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="created_at" id="created_at" class="form-control" placeholder="" value="{{ $order->created_at }}" disabled/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label"></label>
                                    <div class="col-lg-8">
                                        <button type="submit" class="btn btn-success" id="btn_edit_status" disabled>Edit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>

                <div class="col-lg-6">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Vendor Info
                            </h3>
                        </div>
                        <!--begin::Form-->
                        <form>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Name:</label>
                                    <div class="col-lg-8">
                                        <a href="/admin/vendor/edit/{{ $order->vendor->id }}"><input type="text" name="name" id="name" class="form-control" style="color: blue;" placeholder="" value="{{ $order->vendor->name }}" disabled/></a>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Description:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="description" id="description" class="form-control" placeholder="" value="{{ $order->vendor->description }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Address:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="address" id="address" class="form-control" placeholder="" value="{{ $order->vendor->address }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Status:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="status" id="status" class="form-control" placeholder="" value="{{ ucfirst($order->vendor->status) }}" disabled/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Vendor Province:</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="province" id="province" class="form-control" placeholder="" value="{{ $order->vendor->province->name }}" disabled/>
                                    </div>
                                </div>


                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title">
                                Invoice Details
                            </h3>
                        </div>
                        <div class="card-body">     
                            <div class="row">               
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="d-flex align-items-center justify-content-left mb-2">
                                            <span class="font-weight-bold mr-2"> Nomor Invoice  : </span>
                                            <a class="text-muted text-hover-primary" href="/admin/order/edit/{{str_replace('/' , '-' ,  strtolower($order->invoice_number))}}" ><span class="text-primary"><b> {{ $order->invoice_number }}</b></span></a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-left mb-2">
                                            <span class="font-weight-bold mr-2">Nomor Resi :</span>
                                        <span class="text-secondary"> <b>{{ $order->awb_number }}</b></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-left mb-2">
                                            <span class="font-weight-bold mr-2">Vendor :</span>
                                            <a href="/admin/vendor/edit/{{$order->vendor->id}}" class="text-muted text-hover-primary"><span class="text-primary"><b> {{ $order->vendor->name }} </b></span></a>
                                        </div>
                                        <table class="table table-borderless table-vertical-center p-5">
                                        <thead>
                                                <tr>
                                                    <th class="p-0" style="width: 10px"></th>
                                                    <th class="p-0" > Nama Produk</th>
                                                    <th class="p-0" >Jumlah</th>
                                                    <th class="p-0" style="min-width: 100px">Harga</th>
                                                    <th class="p-0" style="min-width: 80px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($order->productswithdetail != 'null')
                                                    
                                                    @foreach($order->productswithdetail as $dt)
                                                        <tr>
                                                            <td class="pl-0 py-5">
                                                                <div class="symbol symbol-50 symbol-light mr-2">
                                                                    <span class="symbol-label">
                                                                        <img src="{{$dt->product_detail_with_product->link}}" class="h-100 align-self-center" alt="">
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="pl-0">
                                                                <a href="/admin/product/edit/{{$dt->product_detail_with_product->product_id}}" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">{{$dt->product_detail_with_product->name}}</a>
                                                                <br>
                                                                @if ($dt->product_detail_with_product->variant_value_1 != '')
                                                                    <span class="text-secondary font-weight-bold">Variant : </span>
                                                                    <span class="text-muted font-weight-bold">{{$dt->product_detail_with_product->variant_value_1.' '.$dt->product_detail_with_product->variant_value_2 }}</span>
                                                                @endif
                                                                <br>
                                                                @if ($dt->notes != null)
                                                                    <span class="text-secondary font-weight-bold">Catatan : </span>
                                                                    <span class="text-muted font-weight-bold">{{ $dt->notes }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="text-muted font-weight-bold">{{$dt->quantity}}</span>
                                                            </td>
                                                            <td >
                                                                <span class="text-muted font-weight-bold">{{\App\Helpers\Utils::currency_convert($dt->fix_price)}}</span>
                                                            </td>
                                                            <td >
                                                                <span class="text-secondary font-weight-bold">{{ \App\Helpers\Utils::currency_convert(($dt->quantity * $dt->fix_price))}}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                        <tr>
                                                            <td colspan="3" class="text-right" >
                                                            
                                                            </td>
                                                            <td  >
                                                                <span class="text-muted font-weight-bold">Ongkos Kirim : </span>
                                                            </td>
                                                            <td>
                                                                <span class="text-secondary font-weight-bold">{{ \App\Helpers\Utils::currency_convert($order->shipping_cost)}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-right" >
                                                            
                                                            </td>
                                                            <td  >
                                                                <span class="text-muted font-weight-bold">Asuransi Pengirim : </span>
                                                            </td>
                                                            <td>
                                                                <span class="text-secondary font-weight-bold">{{ \App\Helpers\Utils::currency_convert($order->insurance_fee)}}</span>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td colspan="3" class="text-right" >
                                                            
                                                            </td>
                                                            <td  >
                                                                <span class="text-muted font-weight-bold">Total Transaksi : </span>
                                                            </td>
                                                            <td>
                                                                <span class="text-secondary font-weight-bold">{{ \App\Helpers\Utils::currency_convert($order->invoice_total_payment + $order->shipping_cost + $order->insurance_fee)}}</span>
                                                            </td>
                                                        </tr>
                                                @endif    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>    
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-4">
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    <h6> Info Tracking Pengiriman</h6>
                                    <div class="separator separator-dashed separator-border-1 mb-2"></div>
                                    @if($order->tracking)
                                        <div class="timeline timeline-6 mt-3">
                                            @foreach($order->tracking as $track)
                                            <!--begin::Item-->
                                            <div class="timeline-item align-items-start">
                                                <!--begin::Label-->
                                                <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">{{$track['time']}}</div>
                                                <!--end::Label-->
                                                <!--begin::Badge-->
                                                <div class="timeline-badge">
                                                    <i class="fa fa-genderless text-success icon-xl"></i>
                                                </div>
                                                <!--end::Badge-->
                                                <!--begin::Text-->
                                                <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">{{$track['description']}}<br/><span>{{$track['date']}}</span></div>
                                                <!--end::Text-->
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                    <div> - Tracking Belum Tersedia </div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <h6> Info Pengiriman</h6>
                                    <?php $consigner = json_decode($order->consigner , true);
                                        $consignee = json_decode($order->consignee , true);
                                        $rate = json_decode($order->rate , true);
                                    ?>
                                    <div class="separator separator-dashed separator-border-1 mb-2"></div>

                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class=" mr-2">Logistik  :</span>
                                        <span class="text-muted"><span class="text-secondary"> {{$rate['logistic']['name'] ?? $rate['logistic']['name']}} - {{$rate['logistic']['code'] ?? $rate['logistic']['code']}}</span></span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Company Name:</span>
                                        <span class="text-muted"> <span class="text-secondary"> {{ isset($rate['logistic']['company_name']) ? $rate['logistic']['company_name'] :' - '}}</span> </span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Status Pengiriman :</span>
                                        <span class="text-muted"> <span class="text-secondary"><b>{{$order->status_order->description}}</b></span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Rate Id:</span>
                                        <span class="text-muted"> <span class="text-secondary"> {{ isset($rate['logistic']['id']) ? $rate['logistic']['id'] : ' - '}}</span> </span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Kode PickUp:</span>
                                        <span class="text-muted"> <span class="text-secondary"> {{ $order->pickup_code == '' ? '-' : $order->pickup_code }}</span> </span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Kode Order:</span>
                                        <span class="text-muted"> <span class="text-secondary"> {{ $order->order_logistic_id == '' ? '-' : $order->order_logistic_id }}</span> </span>
                                    </div> 
                                
                                    <div class="separator separator-dashed separator-border-1 mb-2"></div>
                                    <div class="d-flex align-items-center justify-content-left mb-2">
                                        <span class="font-weight-bold mr-2"> Informasi Pengirim : </span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class=" mr-2">Nama Penerima:</span>
                                        <span class="text-muted"><span class="text-secondary"> {{$consigner['name']}}</span></span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Nomor Telepon:</span>
                                        <span class="text-muted"> <span class="text-secondary"> {{$consigner['phone_number']}}</span> </span>
                                    </div> 
                                    <div class="">
                                        <span class="font-weight-bold mr-2">Alamat:</span>
                                        <p class="text-muted"><span class="text-secondary">{{$consigner['address']}}</p>
                                    </div> 

                                    <div class="separator separator-dashed separator-border-1 mb-2"></div>

                                    <div class="d-flex align-items-center justify-content-left mb-2">
                                        <span class="font-weight-bold mr-2"> Informasi Penerima : </span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class=" mr-2">Nama Penerima:</span>
                                        <span class="text-muted"><span class="text-secondary"> {{$consignee['name']}}</span></span>
                                    </div> 
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="mr-2">Nomor Telepon:</span>
                                        <span class="text-muted"> <span class="text-secondary"> {{$consignee['phone_number']}}</span> </span>
                                    </div> 
                                    <div class="">
                                        <span class="font-weight-bold mr-2">Alamat:</span>
                                        <p class="text-muted"><span class="text-secondary">{{$consignee['address']}}</p>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Invoice Log
                            <span class="d-block text-muted pt-2 font-size-sm">Log Data</span></h3>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table id="dt_logs" class="table datatable datatable-bordered datatable-head-custom">
                        <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Status</th>
                            <th>Modified By</th>
                            <th>Date Modified</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!--end::Entry-->
@endsection
@section('js')
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/transaction.js') }}" type="application/javascript" ></script>
@endsection

