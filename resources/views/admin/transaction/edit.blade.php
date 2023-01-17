@extends('layouts.app')
@section('content')
<div class="card card-custom overflow-hidden">
    <div class="card-header">
        <h3 class="card-title">
            Transaction Info # {{ $data2[0]->transaction_number }}
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="row justify-content-center py-8 px-8 py-md-5 px-md-0">
            <div class="col-md-9">
                <div class="d-flex justify-content-between flex-column flex-md-row">
                    <h1 class="display-4 font-weight-boldest mb-10"></h1>
                    <div class="d-flex flex-column align-items-md-end px-0">
                        <!--begin::Logo-->
                        <a href="/admin/user/edit/{{ $order->user->id }}">
                            <h6><span class="font-weight-bolder mb-2">{{ $order->user->name}}</span></h6>
                        </a>
                        <!--end::Logo-->
                        <span class="d-flex flex-column align-items-md-end opacity-70">
                            <span>Email : {{$order->user->email}}</span>
                            <span>Contact : {{$order->user->phone}}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: Invoice header-->
        <!-- begin: Invoice body-->
        <div class="pl-20 pt-5 pr-20">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                        <h6> Info Transaksi</h6>
                            <div class="separator separator-dashed separator-border-1 mb-2"></div>
                            <div class="py-9">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2"> Nomor Transaksi  :</span>
                                  <span class="text-secondary"> {{ $data2[0]->transaction_number }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Tanggal Pemesanan :</span>
                                    <span class="text-secondary"> {{ $data2[0]->order_created }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Metode Pembayaran :</span>
                                    <span class="text-muted"><span class="text-secondary"> {{$data2[0]->channel}}</span> </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2"> Nomor Rekening :</span>
                                    <span class="text-muted"><span class="text-secondary"> {{ $data2[0]->account_number }}</span> </span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2"> Bank :</span>
                                    <span class="text-muted"><span class="text-secondary"> {{ $data2[0]->payment_channel }}</span> </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2"> Status Transaksi :</span>
                                    <span class="text-muted"><span class="text-secondary"> {{ $status }}</span> </span>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Total Diskon:</span>
                                    <span class="text-muted"><h6> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($data2[0]->total_discount) }}</span> </h6> </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Total Hotpoint:</span>
                                    <span class="text-muted"><h6> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($data2[0]->point) }}</span> </h6> </span>
                                </div> 
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Total Pembayaran:</span>
                                    <span class="text-muted"><h6> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($data2[0]->total_payment) }}</span> </h6> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pb-5 pt-5">
                    @if($data2[0]->detail_voucher != '')
                        <h6> Info Penggunaan Voucher</h6>
                        <?php 
                        $voucher_ = json_decode($data2[0]->detail_voucher , true);
                        ?>
                        <div class="separator separator-dashed separator-border-1 mb-2"></div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class=" mr-2">Nama Voucher:</span>
                            <span class="text-muted"><span class="text-secondary"> {{$voucher_['voucher_name']}}</span>
                        </div> 
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="mr-2">Kode Voucher:</span>
                            <span class="text-muted"> <span class="text-secondary"> {{ $voucher_['voucher_code']}}</span>
                        </div> 
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="mr-2">Diskon Voucher:</span>
                            <span class="text-muted"> <span class="text-secondary"> {{ \App\Helpers\Utils::currency_convert($data2[0]->voucher_value)}}</span>
                        </div> 
                    @else   
                    <h6> Info Penggunaan Voucher</h6>
                    <div class="separator separator-dashed separator-border-1 mb-2"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span>Tidak menggunakan Voucher </span>
                    </div> 
                    @endif
                </div>
            </div>
        </div>
        <!-- end: Invoice body-->
        <!-- begin: Invoice action-->
        <div class="separator separator-dashed separator-border-1 mb-2"></div>
            <div class="">
                <div class="pl-20 pt-5 pr-20">
                <h6>Detail Transaksi</h6>
                <div class="separator separator-dashed separator-border-1 mb-2"></div>
                <div class="row">
                    @foreach($data2 as $tr) 
                    <div class="col-md-7 pb-5 pt-5">
                        <div class="table-responsive">
                            <div class="d-flex align-items-center justify-content-left mb-2">
                                <span class="font-weight-bold mr-2"> Nomor Invoice  : </span>
                                <a class="text-muted text-hover-primary" href="/admin/order/edit/{{str_replace('/' , '-' ,  strtolower($tr->invoice_number))}}" ><span class="text-primary"><b> {{ $tr->invoice_number }}</b></span></a>
                            </div>
                            <div class="d-flex align-items-center justify-content-left mb-2">
                                <span class="font-weight-bold mr-2">Nomor Resi :</span>
                            <span class="text-secondary"> <b>{{ $tr->awb_number }}</b></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-left mb-2">
                                <span class="font-weight-bold mr-2">Vendor :</span>
                                <a href="/admin/vendor/edit/{{$tr->vendor->id}}" class="text-muted text-hover-primary"><span class="text-primary"><b> {{ $tr->vendor->name }} </b></span></a>
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
                                    @if($tr->productswithdetail != 'null')
                                        
                                        @foreach($tr->productswithdetail as $dt)
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
                                                    <span class="text-muted font-weight-bold">{{$dt->product_detail_with_product->variant_value_1.' '.$dt->product_detail_with_product->variant_value_2 }}</span>
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
                                                    <span class="text-secondary font-weight-bold">{{ \App\Helpers\Utils::currency_convert($tr->shipping_cost)}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-right" >
                                                
                                                </td>
                                                <td  >
                                                    <span class="text-muted font-weight-bold">Total Transaksi : </span>
                                                </td>
                                                <td>
                                                    <span class="text-secondary font-weight-bold">{{ \App\Helpers\Utils::currency_convert($tr->invoice_total_payment + $tr->shipping_cost)}}</span>
                                                </td>
                                            </tr>
                                    @endif    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-5 pb-5 pt-5">
                        <h6> Info Pengiriman</h6>
                        <?php $consigner = json_decode($tr->consigner , true);
                              $consignee = json_decode($tr->consignee , true);
                              $rate = json_decode($tr->rate , true);
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
                            <span class="text-muted"> <span class="text-secondary"><b>{{$tr->status_order->description}}</b></span>
                        </div> 
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="mr-2">Rate Id:</span>
                            <span class="text-muted"> <span class="text-secondary"> {{ isset($rate['logistic']['id']) ? $rate['logistic']['id'] : ' - '}}</span> </span>
                        </div> 
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="mr-2">Kode PickUp:</span>
                            <span class="text-muted"> <span class="text-secondary"> {{ $tr->pickup_code == '' ? '-' : $tr->pickup_code }}</span> </span>
                        </div> 
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="mr-2">Kode Order:</span>
                            <span class="text-muted"> <span class="text-secondary"> {{ $tr->order_logistic_id == '' ? '-' : $tr->order_logistic_id }}</span> </span>
                        </div> 
                       
                        <div class="separator separator-dashed separator-border-1 mb-2"></div>
                        <div class="d-flex align-items-center justify-content-left mb-2">
                            <span class="font-weight-bold mr-2"> Informasi Pengirim : </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class=" mr-2">Nama Pengirim:</span>
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
                    <div class="separator separator-dashed separator-border-1 mb-2"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="{{ url('/assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ url('/custom/admin/transaction.js') }}" type="application/javascript" ></script>
@endsection

