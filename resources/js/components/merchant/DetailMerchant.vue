<template>
    <div class="row-hd">
        <div class="col3-flex merchant">
            <div class="flex-basis-40" style="display:inline !important;">
                <div class="flex-basis-50">
                    <div class="box-90p-white">
                        <div class="row-100 profile-pic-user">
                            <img class="d-block photo-profile" :src="details.image" alt="">
                        </div>
                        <div class="id">
                            <div class="row-100 name">
                                <div class="buyer-name">
                                    <img src="/img/icon_profile.svg" alt="">
                                    {{details.name}}
                                </div>
                            </div>
                            <div class="row-100 transaction" v-if="this.total_review > 0">
                                <div class="buyer-transaction">
                                    <img src="/img/assets_rating_merchant.svg" alt="">
                                    <div class="w-100 d-flex align-center justify-between">
                                        <h5>penilaian</h5>
                                        <h5>{{ this.rating }} &nbsp; <span>({{ this.total_review }})</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row-100 point" v-if="this.purchase > 0">
                                <div class="buyer-point">
                                    <img src="/img/icon_product_sold.svg" alt="">
                                    <div class="w-100 d-flex align-center justify-between">
                                        <h5>produk terjual</h5>
                                        <h5>{{ this.purchase }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-basis-60">
                <div class="box3-shadow-white">
                    <h2 class="mbottom-40">Tentang seller</h2>
                    <div class="mbottom-25">
                        <div class="row-100 mbottom-25">
                            <div class="col-12">
                                <h5 class="fw-700">Info Toko</h5>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <div class="col-3 col-sm-5">
                                <h5>nama toko</h5>
                            </div>
                            <div class="col-9 col-sm-7">
                                <h5>{{details.name}}</h5>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <div class="col-3 col-sm-5">
                                <h5>provinsi</h5>
                            </div>
                            <div class="col-9 col-sm-7">
                                <h5>{{province}}</h5>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <div class="col-3 col-sm-5">
                                <h5>jam operasional</h5>
                            </div>
                            <div class="col-9 col-sm-7">
                                <h5>senin - jumat (09.00 - 17.00)</h5>
                            </div>
                        </div>
                        <div class="row-100 mbottom-30">
                            <div class="col-3 col-sm-5">
                                <h5>Tanggal Bergabung</h5>
                            </div>
                            <div class="col-9 col-sm-7">
                                <h5>{{format_date(details.created_at)}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="mbottom-25" v-if="details.description != null">
                        <div class="row-100 mbottom-25">
                            <div class="col-12">
                                <h5 class="fw-700">deskripsi</h5>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20 email">
                            <div class="col-12">
                                <h5 class="lowercase mbottom-0">{{details.description}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiCustomer from "../../apis/Customer"
import moment from 'moment'

    export default {
        name: "DetailMerchant.vue",
        data(){
            return {
                route_active : '',
                vendor_id : '',
                details : {
                    user : {
                        email : '',
                        phone : '',
                    }
                },
                total_review : '',
                rating : '',
                purchase : '',
                province : ''
            }
        },
        mounted() {
            this.route_active = this.$route.name;
            this.vendor_id = this.$route.params.pathMatch//this.$route.query.merchant;
            this.getDetail();
        },
        methods: {
            route(route){
                this.route_active = route
            },
            getDetail() {
                var vendor_id = this.$route.params.pathMatch//this.$route.query.merchant
                const fd = new FormData();
                fd.append('vendor_id' ,vendor_id)
                apiCustomer.merchantDetail(fd).then( response => {
                    if(response.data.code === 200) {
                        this.details = response.data.data.vendor
                        this.total_review = response.data.data.review.total_review
                        this.rating = response.data.data.review.rating
                        this.purchase = response.data.data.purchase
                        this.vendor_id = vendor_id
                        this.province = response.data.data.vendor.province.name
                    }
                })
            },
            format_date(date){
                if (date){
                    return moment(String(date)).format('DD MMMM YYYY')
                }
            }
        },
    }
</script>

<style scoped>
.container .col3-flex {
    width: 100% !important;
    margin: 0 auto 0px !important;
    display: flex;
    justify-content: space-between;
}

</style>