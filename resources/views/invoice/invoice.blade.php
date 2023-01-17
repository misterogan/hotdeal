<div id="invoice_modal">
    <div class="modal-dialog w-800">
        <div>
            <div>
                <div class="invoice">
                    <div class="logo">
                        {{-- <img src="{{url('/img/LogoInvoice.png')}}" alt=""> --}}
                        <img style="    width: 20%;" src="{{public_path('/img/LogoInvoice.png')}}" alt="">
                    </div>
                    <div>
                        <div class="status" style="float: right">{{$data->description}}</div>
                        <table>
                            <tr>
                                <td style="width: 25%">No.Invoice</td>
                                <td style="width: 10%">:</td>
                                <td>{{$data->invoice_number}}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%">Penjual</td>
                                <td style="width: 10%">:</td>
                                <td>{{$data->vendor->name}}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%">Pembeli</td>
                                <td style="width: 10%">:</td>
                                <td>{{ Auth::user()->name}}</td>
                            </tr>
                            <tr>
                                <td style="width: 25%">tanggal pembelian</td>
                                <td style="width: 10%">:</td>
                                <td>{{ date('Y-m-d H:i:s' , strtotime($data->created_at))}}</td>
                            </tr>
                        </table>
                    </div>
                    <br/>
                    <div class="body-invoice">
                        <table class="table-invoice">
                            <thead>
                            <tr class="title-table">
                                <th>info pembelian</th>
                                <th>jumlah</th>
                                <th>harga satuan</th>
                                <th>total harga</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $total = 0;    
                                ?>
                            @foreach($data->productswithdetail as $op)
                                <?php 
                                    $total+= $op->fix_price * $op->quantity;
                                ?>
                                <tr>
                                    <td>{{ $op->product_detail_with_product->name }}</td>
                                    <td>{{ $op->quantity }}</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert($op->fix_price) }}</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert($op->fix_price * $op->quantity) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>
                        <hr/>
                        <div style="display: flex; justify-content: end;">
                            <table class="recap">
                                <tr>
                                    <td>Total Harga</td>
                                    <td>:</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert($total) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Ongkir</td>
                                    <td>:</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert($data->shipping_cost) }}</td>
                                </tr>
                                <tr>
                                    <td>Voucher</td>
                                    <td>:</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert( ( $data->total_discount / $voucher_split)) }}</td>
                                </tr>
                                <tr>
                                    <td>Penggunaan Poin</td>
                                    <td>:</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert($data->point) }}</td>
                                </tr>
                                <tr>
                                    <td  colspan="3">
                                        <hr/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total tagihan</td>
                                    <td>:</td>
                                    <td>{{ \App\Helpers\Utils::currency_convert(($total + $data->shipping_cost) - $data->point - ( $data->total_discount / $voucher_split)) }}</td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <hr/>
                        <h6 class="fw-700 mbottom-20">info pengiriman</h6>
                        <table class="table-information">
                            <?php 
                                $consignee = json_decode($data->consignee)    
                            ?>
                            <tr>
                                <td>Nama penerima</td>
                                <td>:</td>
                                <td>{{$consignee->name}}</td>
                            </tr>
                            <tr>
                                <td >Nomor Telepon</td>
                                <td>:</td>
                                <td>{{$consignee->phone_number}}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $consignee->address}}</td>
                            </tr>
                        </table>

                        <h6 class="fw-700 mbottom-20">Metode pembayaran</h6>
                        <table class="table-information">
                            <tr>
                                <td>Pembayaran</td>
                                <td>:</td>
                                <td>{{$data->label}}</td>
                            </tr>
                        </table>
                        <h6 class="fw-700 mbottom-20">Metode Pengiriman</h6>
                        <?php 
                                $logistic = json_decode($data->logistic_detail);
                            ?>
                        <table class="table-information">
                            <tr>
                                <td>Pengiriman</td>
                                <td>:</td>
                                <td>{{$logistic->courier->rate_id->detail->logistic->name.' - '.$logistic->courier->rate_id->detail->rate->type}}</td>
                            </tr>
                        </table>
                        <div class="disclaimer">
                            <h6>Invoice ini sah dan di proses dengan komputer.
                                <br>
                                Silahkan hubungi call center Hotdeal apabila membutuhkan bantuan.
                            </h6>
                            <p class="italic" style="float: right;
                            margin-top: -16px;">
                                Terakhir di update : {{ date('d , F Y' , strtotime($data->updated_at))}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    td {
        padding: 10px;
        border-collapse: collapse;
    }
    table thead th{
        color: white;
    text-align: center;
    font-weight: 600;
    }
    table th {
        background-color: #2F2F2F;
    height: 52px;
    text-align: center;
    text-transform: capitalize;
    }
table{
    border-spacing: 2px;
    width: 100%;
    border-collapse: collapse;
}
table th:last-child {
    border-top-right-radius: 1em;
    border-bottom-right-radius: 1em;
}
table th:first-child {
    border-top-left-radius: 1em;
    border-bottom-left-radius: 1em;
}
tr{
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}
.table-invoice{
    text-align: center;
}
.recap{
    width: 38% !important;
    float: right;
}
.table-information tr td:first-child{
    width: 20%;
}
.table-information tr td:last-child{
    width: 80%;
}
h6{
    font-weight: 700;
    font-size: 1rem;
    color: #44454F;
    margin: 0px 10px 0px 10px;
}
/* .disclaimer{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 30px;
} */
.italic{
    color: #B3B3B3;
    font-style: italic;
}
.disclaimer h6 {
    color: #B3B3B3 !important;
    font-weight: 400 !important;
}
.status {
    background: #88C71A;
    border: none;
    color: white;
    padding: 4px 0;
    font-weight: 600;
    width: 100px;
    border-radius: 4px;
    text-align: center;
}
</style>