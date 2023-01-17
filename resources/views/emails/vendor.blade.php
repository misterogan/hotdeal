<div style="background-color: #FFFFFF">
    <div style="padding: 0; margin: 0 auto; width: 100%!important; font-family: Open Sans, sans serif;box-sizing: border-box; scroll-behavior: smooth;">
        <div class="email-user" style="max-width: 500px; margin: 0 auto; box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.1); border-radius: 20px; padding-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; display:table; box-sizing: border-box; border-spacing:0;">
                <tbody>
                    <tr>
                        <td style="margin:0; padding:0;">
                            <div style="width: 100%; margin-bottom: 15px;">
                                <img style="width: 100%; border-radius: 15px 15px 0 0; height: 225px; object-fit: cover;" src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/HeaderEmail.png" alt="">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="border-spacing:0;">
                                    <tr>
                                        <td style="margin:0; padding:0; font-family: 'Open Sans';">
                                            <div style="margin-bottom: 30px">
                                                <h5 style="color:#44454F; margin: 0; font-size:13px;">Dear {{$transaction['vendor']['name']}}</h5>
                                                <h5 style="color: #44454F; margin: 0; font-size:13px; text-align:left;">{{$body}}
                                                </h5>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="display: -webkit-box; width: 100%; text-transform: capitalize; margin-bottom:15px; font-family: 'Open Sans';">
                            <h5 style="display: inline-table; background-color: #88C71A; border-radius: 4px; padding: 2px 10px; color: #FFFFFF; margin 0; font-size: 13px;">pesanan diterima</h5>
                            <h5 style="color: #44454F;  display: inline-block; margin: 0 0 0 15px; font-size: 13px;">transaksi tanggal {{date( 'Y-m-d H:i:s' , strtotime($transaction['created_at'])) }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: 'Open Sans';">
                            <div style="width: 90%; margin: 0 auto;">
                            <table style="width: 100%; padding: 10px 0 12px; border-bottom: 1px solid #B3B3B3; border-bottom-width: 0.1px; border-spacing:0;">
                                    <tr style="display: flex;">
                                        <td style="margin:0; padding:0; width:40%; vertical-align: top; font-family: 'Open Sans';">
                                            <ul style="padding: 0; margin-block-start: 0; margin-block-end: 0;">
                                                <?php $quantity = 0;?>
                                                @foreach ($transaction['productswithdetailwithimage'] as $item)
                                                    <?php $quantity  += $item['quantity'];?>
                                                    <li style="list-style :none; margin-bottom: 10px; margin-left: 0;">
                                                        <h5 style="color:#44454F; margin: 0; font-size: 12px;">{{$item['product_detail_with_product']['name']}} {{number_format($item['fix_price'],0,".",".")}}</h5>
                                                        <h5 style="color:#44454F; margin: 0; font-size: 12px; font-weight:300;">Jumlah {{$item['quantity']}} pc</h5>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="margin:0; padding:0; width:60%; vertical-align: top; font-family: 'Open Sans';">
                                            <ul style="display: -webkit-box; padding: 0; margin: 0;">
                                                @foreach ($transaction['productswithdetailwithimage'] as $item)   
                                                    <li style="width: 90px; height: 90px; list-style :none; margin: 0 10px 10px 0;">
                                                        <img style="width: 100%; height: 100%; object-fit:cover; border-radius: 10px;" src="{{$item['product_detail_with_product']['link']}}" alt="">
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="border-bottom: 1px solid #B3B3B3; padding: 15px 0 20px 0; border-bottom-width: 0.1px; border-spacing:0;">
                                    <tr>
                                        <td style="margin:0; padding:0; width:80%;">
                                            <div style="display: -webkit-inline-box; width:100%;">
                                                <h6 style="color:#B3B3B3; margin: 0; width:30%;">Total {{$quantity}} Produk</h6>
                                                <h6 style="color:#FF1D53; margin: 0; width:70%;">Rp {{number_format($transaction['invoice_total_payment'],0,".",".")}}</h6>
                                            </div>
                                            <div style="display: -webkit-inline-box; width:100%;">
                                                <h6 style="color:#B3B3B3; margin: 0; width:30%;">Ongkir</h6>
                                                <h6 style="color:#44454F; margin: 0; width:70%;">Rp {{number_format($transaction['shipping']['shipping_cost'],0,".",".")}}</h6>
                                            </div>
                                        </td>
                                        <td style="margin:0; padding:0; width:20%; vertical-align: top; ">
                                            <h5 style="color: #44454F; margin: 0;">TOTAL {{number_format(($transaction['invoice_total_payment'] + $transaction['shipping']['shipping_cost']),0,".",".")}}</h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>   
                    <tr>
                        <td>
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="padding: 30px 0; border-spacing:0;">
                                    <tr>
                                        <td style="margin:0; padding:0; width:80%;">
                                            <h6 style="color:#B3B3B3; margin: 0; font-weight:400; font-size: 10px;">Invoice ini sah dan di proses dengan komputer</h6>
                                            <h6 style="color:#B3B3B3; margin: 0; font-weight:400; font-size: 10px; margin-bottom: 30px;">Silahkan hubungi call center Hotdeal apabila membutuhkan bantuan.</h6>
                                            <h6 style="color:#B3B3B3; margin: 20px 0 0 0; font-weight:400; font-style:italic; font-size: 10px;">Terakhir di update : {{date('d M Y')}}</h6>
                                        </td>
                                        <td style="margin:0; padding:0; width:20%; vertical-align: top; ">
                                            <!-- <button style="margin: 0 auto; background-color:#46278A; border-radius: 20px; color: #ffffff; border:none; font-weight: 600; height: 2.25rem; width: 155px; padding:0; cursor:pointer;">
                                                Pesanan Diterima
                                            </button> -->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>