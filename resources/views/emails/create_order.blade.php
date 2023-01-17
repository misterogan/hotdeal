<?php
$consignee_ = json_decode($transaction['order_detail'][0]['shipping']['consignee']);
$logistic_ = json_decode($transaction['order_detail'][0]['shipping']['rate']);
?>
<div style="background-color: #FFFFFF">
    <div style="padding: 0; margin: 0 auto; width: 100%!important; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif; box-sizing: border-box; scroll-behavior: smooth;">
        <div style="max-width: 500px; margin: 0 auto; box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.1); border-radius: 20px; padding-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; display:table; box-sizing: border-box; border-spacing:0;">
                <tbody>
                    <tr>
                        <td style="margin:0; padding:0;">
                            <div style="width: 100%; margin-bottom: 15px;">
                                <img style="width: 100%; border-radius: 10px 10px 0 0; object-fit: cover;" src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/HeaderEmail.png" alt="">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="border-spacing:0;">
                                    <tr>
                                        <td style="width: 100%; margin:0; padding:0; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <div style="margin-bottom : 15  px">
                                                <h5 style="color:#44454F; margin:0; font-size:14px; font-weight: 600;">Dear {{$transaction['user']['name']}}</h5>
                                                <h5 style="color: #44454F; margin: 0 0 15px 0; font-size:14px; text-align:left; font-weight: 600; letter-spacing: .1px;">
                                                    {{$body}}
                                                </h5>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- {{-- <td style="display: -webkit-box; width: 100%; text-transform: capitalize; margin-bottom:15px; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <h5 style="display: inline-table; background-color: #88C71A; border-radius: 4px; padding: 2px 10px; color: #FFFFFF; font-size: 14px; margin: 0;">{{$transaction['master_status']['description']}}</h5>
                                            <h5 style="color: #44454F; display: inline-block; margin: 0 0 0 15px; font-size: 14px;">transaksi tanggal {{date('Y-m-d H:i:s' ,strtotime($transaction['created_at']))}}</h5>
                                        </td> --}} -->
                                        <td>
                                            <div style="width: 100%; margin: 0 0 15px;">
                                                <table style="border-spacing: 0; width: 100%;">
                                                    <tr>
                                                        <td style="margin:0; padding:0 10px 0 0; vertical-align: top; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                            <h5 style="display: inline-table; white-space:nowrap; background-color: #88C71A; border-radius: 4px; padding: 2px 10px; color: #FFFFFF; font-size:13px; margin: 0;">{{$transaction['master_status']['description']}}</h5>
                                                        </td>
                                                        <td style="margin:0; padding:0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                            <h5 style="color: #44454F; display: inline-block; margin: 0; font-size:14px; word-wrap:break-word; font-weight:600; text-transform:capitalize;">transaksi tanggal {{date('Y-m-d H:i:s' ,strtotime($transaction['created_at']))}}</h5>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0; padding: 0; vertical-align: top; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;          ">
                                            <h5 style="color:#44454f; font-size:14px; margin: 0 0 10px 0; font-weight: 600;">Nomor Transaksi : {{$transaction['transaction_number']}} </h5>
                                            <!-- {{-- <h5 style="color: #44454F; font-size: 14px; margin: 0 0 10px 0; font-weight: 600;">Pesanan Sedang Dikirim Dengan nomor Resi:</h5> --}} -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 65%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <!-- {{-- <h5 style="color: #44454F; width: fit-content; border-radius: 10px; border: 1px solid #C2C2C2; padding: 8px 10px; margin: 10px 0 20px; font-size: 14px;">80777081289838209</h5> --}} -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <h5 style="color: #44454F; font-size: 14px; margin: 0; font-weight: 600;">Info Pengiriman:</h5>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <table style="text-transform: capitalize; width:100%; border-spacing: 0; margin-top: 10px;">
                                               <tr>
                                                    <td style="width: 35%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0;">Nama penerima</h5>
                                                    </td>
                                                    <td style="width: 65%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0; padding:0 0 0 15px;">{{ $consignee_->name}}</h5>
                                                    </td>
                                                </tr>
                                               <tr>
                                                    <td style="width: 35%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0;">Telepon penerima</h5>
                                                    </td>
                                                    <td style="width: 65%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0; padding:0 0 0 15px;">{{ $consignee_->phone_number}}</h5>
                                                    </td>
                                                </tr>
                                                <tr style="margin: 0 0 10px 0">
                                                    <td style="width: 35%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0;">alamat penerima</h5>
                                                        <!-- {{-- <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0;">Tag : <b>Rumah</b></h5> --}} -->
                                                    </td>
                                                    <td style="width: 65%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0; padding:0 0 0 15px;">{{ $consignee_->address}}</h5>
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0; padding:0 0 0 15px;">Kec. Tebet, Kota Jakarta Selatan,</h5>
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px; padding:0 0 0 15px;">Daerah Khusus Ibukota Jakarta 12870</h5>
                                                    </td>
                                                </tr>
                                                <tr style="margin: 0 0 15px 0">
                                                    <td style="width: 35%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0; ">kurir</h5>
                                                    </td>
                                                    <td style="width: 65%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0; padding:0 0 0 15px;">{{$logistic_->logistic->name}} - {{$logistic_->rate->name}}</h5>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <h5 style="color: #44454F; font-size: 14px; margin: 0; font-weight: 600;">Rincian Pembayaran:</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table style="text-transform: capitalize; width:100%; border-bottom: 1px solid #B3B3B3; margin-top: 10px; border-bottom-width: 0.1px; border-spacing: 0; padding-bottom: 25px;">
                                               <tr>
                                                    <td style="width: 35%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; vertical-align: top; font-size: 14px; margin: 0 0 10px 0;">Metode pembayaran</h5>
                                                    </td>
                                                    <td style="width: 65%; margin: 0; padding: 0;  vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; vertical-align: top; text-transform: uppercase; font-size: 14px; margin: 0 0 10px 0; padding:0 0 0 15px;">{{$transaction['payment']['paymentMethod']['channel']}} - {{$transaction['payment']['paymentMethod']['label']}}</h5>
                                                    </td>
                                                </tr>
                                               <tr>
                                                    <td style="width: 35%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0;">Total dibayarkan</h5>
                                                    </td>
                                                    <td style="width: 65%; margin: 0; padding: 0; vertical-align: top; word-wrap:break-word; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                        <h5 style="color: #B3B3B3; font-weight: 400; font-size: 14px; margin: 0 0 10px 0; padding:0 0 0 15px;">{{'Rp '.number_format($transaction['payment']['expected_amount'],0, ',' , '.') }}</h5>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="width: 100%; padding: 10px 0 0; border-bottom: 1px solid #B3B3B3; border-bottom-width: 0.1px; border-spacing:0;">
                                    <tr>
                                        <td style="margin:0; padding:0; width:100%; vertical-align: top; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <ol style="padding: 0; margin: 0;">
                                                @foreach ($transaction['order_detail'] as $item)
                                                        @foreach($item['productswithdetail'] as $value)
                                                            <li style="list-style: none; margin: 0 0 20px 0; font-size: 14px; color: #44454F; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                                                <h5 style="color:#44454F; margin: 0; font-size: 14px; text-transform: capitalize;">{{$value['product_detail_with_product']['name']}} Rp {{number_format($value['fix_price'],0, ',' , '.')}}</h5>
                                                                <h5 style="color:#44454F; margin: 0 0 10px 0; font-size: 14px; font-weight:300;">Jumlah {{$value['quantity']}} pc</h5>
                                                                <div style="width:100px; height:100px;">
                                                                    <img style="width: 100%; height: 100%; object-fit:cover; border-radius: 10px;" src="{{$value['product_detail_with_product']['link']}}" alt="">
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                @endforeach
                                            </ul>
                                        </td>
                                        <!-- {{-- <td style="margin:0; padding:0; width:60%; vertical-align: top; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <ul style="padding: 0; margin: 0;">
                                                @foreach ($transaction['order_detail'] as $item)
                                                        @foreach($item['productswithdetail'] as $value)

                                                        @endforeach
                                                @endforeach
                                            </ul>
                                        </td> --}} -->
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <!-- {{-- <tr>
                        <td>
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="border-bottom: 1px solid #B3B3B3; padding: 15px 0 20px 0; border-bottom-width: 0.1px; border-spacing:0;">
                                    <tr>
                                        <td style="margin:0; padding:0; width:80%;">
                                            <div style="display: -webkit-inline-box; width:100%;">
                                                <h6 style="color:#B3B3B3; margin: 0; width:25%;">Total 5 Produk</h6>
                                                <h6 style="color:#FF1D53; margin :0; width:75%;">Rp 3.246.400</h6>
                                            </div>
                                            <div style="display: -webkit-inline-box; width:100%;">
                                                <h6 style="color:#B3B3B3; margin: 0; width:25%;">Ongkir</h6>
                                                <h6 style="color:#44454F; margin: 0; width:75%;">Rp 60.000</h6>
                                            </div>
                                            <div style="display: -webkit-inline-box; width:100%;">
                                                <h6 style="color:#B3B3B3; margin: 0; width:25%;">Asuransi</h6>
                                                <h6 style="color:#44454F; margin: 0; width:75%;">Rp 2.500</h6>
                                            </div>
                                        </td>
                                        <td style="margin:0; padding:0; width:20%; vertical-align: top; ">
                                            <h5 style="color: #44454F; margin: 0;">TOTAL 3.378.900</h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr> --}} -->
                    <tr>
                        <td>
                            <div style="width: 90%; margin: 0 auto;">
                                <table style="padding: 25px 0; border-spacing: 0;">
                                    <tr>
                                        <td style="margin:0; padding:0; width:80%; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <h6 style="color:#B3B3B3; font-weight:400; margin: 0; font-size: 11px;">Invoice ini sah dan di proses dengan komputer</h6>
                                            <h6 style="color:#B3B3B3; font-weight:400; margin: 0; font-size: 11px;">Silahkan hubungi call center Hotdeal apabila membutuhkan bantuan.</h6>
                                            <h6 style="color:#B3B3B3; font-weight:400; font-style:italic; margin: 20px 0 0 0; font-size: 11px;">Terakhir di update : {{date('Y-m-d')}}</h6>
                                        </td>
                                        <td style="margin:0; padding:0; width:20%; vertical-align: top; font-family: Roboto,RobotoDraft,Helvetica,Arial,sans-serif;">
                                            <?php
                                                $baseurl = env('APP_URL');
                                            ?>
                                            <a href="{{$baseurl}}/transactions/pending-transaction">
                                                <button style="margin: 0 auto; background-color:#46278A; border-radius: 20px; color: #ffffff; border:none; font-weight: 600; height: 2.5rem; width: 125px; padding:0; font-size: 11px; letter-spacing: .2px; cursor:pointer">
                                                    Lihat Pesanan
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="width: 95%; margin: 0 auto;">
                                <table style="border-spacing:0;">
                                    <tr>
                                        <div style="margin-bottom: 10px;">
                                            <img style="width: 100%; border-radius: 15px; height: 180px; object-fit: cover;" src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/RejekiNomplok.png" alt="">
                                        </div>
                                    </tr>
                                    <tr>
                                        <div style="padding: 0 20px; background-color: #fff;">
                                            <img style="width: 120px;" src="{{$baseurl}}/img/logo_email@2x.png" alt="">
                                            <br><br>
                                            <h5 style="color: #8F9096; margin:0; font-size: 14px;">Hotdeal Indonesia</h5>
                                            <h6 style="color: #8F9096; font-weight: 400; margin: 10px 0 0 0; font-size: 12px;">Hotdeal adalah video commerce pertama di Indonesia yang menawarkan berbagai produk eksklusif dan
                                                berkualitas dengan harga bersaing. Dapatkan pengalaman belanja menyenangkan hanya di <a href="" style="color: #484DB1; text-decoration: none; font-weight: 600;">hotdeal.id</a>
                                            </h6>
                                            <h6 style="color: #8F9096; font-weight: 400; margin: 10px 0 0 0; font-size: 12px;">Untuk informasi lebih lengkap hubungi kami di <a href="" style="color: #484DB1; text-decoration: none; font-weight: 600;">info@hotdeal.id</a></h6>
                                            <ul style="display: -webkit-box; padding: 0; margin: 20px 0 0 0;">
                                                <li style="list-style: none; margin-left: 0;">
                                                    <img style="width: 22px" src="{{$baseurl}}/img/footer_email_fb@2x.png" alt="">
                                                </li>
                                                <li style="list-style: none; margin-left:10px;">
                                                    <img style="width: 22px" src="{{$baseurl}}/img/footer_email_twitter@2x.png" alt="">
                                                </li>
                                                <li style="list-style: none; margin-left:10px;">
                                                    <img style="width: 22px" src="{{$baseurl}}/img/footer_email_ig@2x.png" alt="">
                                                </li>
                                                <li style="list-style: none; margin-left:10px;">
                                                    <img style="width: 22px" src="{{$baseurl}}/img/footer_email_tiktok@2x.png" alt="">
                                                </li>
                                                <li style="list-style: none; margin-left:10px;">
                                                    <img style="width: 22px" src="{{$baseurl}}/img/footer_email_fb_yt@2x.png" alt="">
                                                </li>
                                            </ul>
                                        </div>
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
