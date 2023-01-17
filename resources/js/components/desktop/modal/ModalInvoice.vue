<template>
    <div id="invoice_modal" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal" v-if="Object.keys(invoice).length > 0">
                    <div class="invoice">
                        <div class="logo">
                            <img src="/img/logo.svg" alt="">
                        </div>
                        <div class="top-invoice">
                            <div class="row info">
                                <div class="col-field">
                                    <h6>no. invoice</h6>
                                </div>
                                <div class="col-view d-flex">
                                    <h6>: {{invoice.invoice_number}}</h6>
                                    <div class="status">{{invoice.status_order.description}}</div>
                                </div>
                            </div>
                            <div class="row info">
                                <div class="col-field">
                                    <h6>penjual</h6>
                                </div>
                                <div class="col-view">
                                    <h6>: {{ invoice.vendor.name }}</h6>
                                </div>
                            </div>
                            <div class="row info">
                                <div class="col-field">
                                    <h6>pembeli</h6>
                                </div>
                                <div class="col-view">
                                    <h6>: {{ invoice.order.user.name }}</h6>
                                </div>
                            </div>
                            <div class="row info">
                                <div class="col-field">
                                    <h6>tanggal pembelian</h6>
                                </div>
                                <div class="col-view">
                                    <h6>: {{ invoice.transaction_date }}</h6>
                                </div>
                            </div>
                        </div>
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
                                    <tr v-for="(product, index) in invoice.productswithdetail" :key="index">
                                        <td>
                                            {{ product.product_detail_with_product.name }}
                                            <br>
                                            <h6 v-if="product.notes !== null">Catatan : {{ product.notes }}</h6>
                                        </td>
                                        <td>{{ product.quantity}}</td>
                                        <td>{{ product.fix_price | RupiahFormat }}</td>
                                        <td>{{ parseInt(product.fix_price) * parseInt(product.quantity) | RupiahFormat }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="calculation">
                                <div class="row total">
                                    <div class="col-field">
                                        <h6 class="fw-600">Total belanja</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{ invoice.invoice_total_payment | RupiahFormat }}</h6>
                                    </div>
                                </div>
                                <div class="row total">
                                    <div class="col-field">
                                        <h6 class="fw-600">total ongkir</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{ invoice.shipping.shipping_cost | RupiahFormat }}</h6>
                                    </div>
                                </div>
                                 <div class="row total">
                                    <div class="col-field">
                                        <h6 class="fw-600">Asuransi pengiriman</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{ invoice.insurance_fee | RupiahFormat }}</h6>
                                    </div>
                                </div>

                                <div class="row total">
                                    <div class="col-field">
                                        <h6 class="fw-600">Voucher</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6> - {{ invoice.order.total_discount | NumberFormat }}</h6>
                                    </div>
                                </div>
                                <div class="row total">
                                    <div class="col-field">
                                        <h6 class="fw-600">Penggunaan poin</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6> - {{ invoice.order.point | NumberFormat }}</h6>
                                    </div>
                                </div>
                                <hr>
                                <div class="row total">
                                    <div class="col-field">
                                        <h6 class="fw-600">total tagihan</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{ parseInt(invoice.invoice_total_payment) + parseInt(invoice.shipping.shipping_cost) + parseInt(invoice.insurance_fee) - parseInt(invoice.order.point) - parseInt(invoice.order.total_discount)| RupiahFormat }}</h6>
                                    </div>
                                </div>
                            </div>
                            <h6 class="fw-700 mbottom-20">info pengiriman :</h6>
                            <div class="row flex mbottom-15">
                                <div class="col-field">
                                    <h6>nama penerima</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ invoice.shipping.consignee | JSONSTRINGIFY('name') }}</h6>
                                </div>
                            </div>
                            <div class="row flex mbottom-15">
                                <div class="col-field">
                                    <h6>telepon penerima</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ invoice.shipping.consignee | JSONSTRINGIFY('phone_number') }}</h6>
                                </div>
                            </div>
                            <div class="row flex mbottom-30">
                                <div class="col-field">
                                    <h6>alamat penerima</h6>
                                    <!-- <h6>Tag <strong>: Rumah</strong> </h6> -->
                                </div>
                                <div class="col-view">
                                    <h6>{{ invoice.shipping.consignee | JSONSTRINGIFY('address') }}</h6>
                                </div>
                            </div>

                            <h6 class="fw-700 mbottom-15">metode pembayaran :</h6>
                            <div class="row flex mbottom-30">
                                <div class="col-field">
                                    <h6>pembayaran</h6>
                                </div>
                                <div class="col-view">
                                    <h6 class="uppercase" v-if="invoice.order.payment.payment_method != null">{{ invoice.order.payment.payment_method.label }}</h6>
                                    <h6 v-else class="uppercase"> Hot Point </h6>
                                </div>
                            </div>

                            <h6 class="fw-700 mbottom-15">metode pengiriman :</h6>
                            <div class="row flex mbottom-30">
                                <div class="col-field">
                                    <h6>pengiriman</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ invoice.shipping.rate | JSONSTRINGIFY_LOGISTIC('name') }}</h6>
                                </div>
                            </div>

                            <div class="disclaimer">
                                <h6>Invoice ini sah dan di proses dengan komputer.
                                    <br>
                                    Silahkan hubungi call center Hotdeal apabila membutuhkan bantuan.
                                </h6>
                                <p class="italic">
                                    Terakhir di update : {{ invoice.updated_at | DateFormat }}
                                </p>
                            </div>

                            <div class="download">
                                <button href="javascript:void(0)" @click="downloadInvoice">download invoice</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import CloseModal from '../component/CloseModal.vue'
    import apiCustomer from '../../../apis/Customer'

    export default {
        name: "ModalInvoice.vue",
        data() {
            return {
                data : {
                    invoice_number: this.invoice.invoice_number
                }
            }
        },
        props :['invoice'],
        components:{
                CloseModal
        },
        methods : {
            downloadInvoice() {
                var params = 'invoice_number=' + this.invoice.invoice_number;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/transaction/invoice/download', true);
                xhr.responseType = 'arraybuffer';
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function(e) {
                    if (this.status == 200) {
                        var blob=new Blob([this.response], {type:"application/pdf"});
                        var link=document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "invoice.pdf";
                        link.click();
                    }
                };
                xhr.send(params);
            }
        }
    }
</script>

<style>

</style>
