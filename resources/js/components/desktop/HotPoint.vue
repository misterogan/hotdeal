<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-40">
                <profile-menu></profile-menu>
                <profile-point-info @myPoint="mypoint = $event" v-if="Object.keys(profile).length > 0" :profile="profile.users"></profile-point-info>
                <div class="banner-mobile">
                    <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/Hotdeal-x-Cashtree-Banner-Web-Mobile.webp" alt="">
                </div>
            </div>
            <div class="flex-basis-60">
                <div class="box3-shadow-white" id="profile-menu" >
                    <div v-if="is_loading">
                        <div class="ph-row mtop-20 mbottom-40">
                            <div class="ph-col-2 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mtop-20 mbottom-20">
                            <div class="ph-col-12 ph-h175 bg-placeholder rounded-md"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-4 ph-h75 bg-placeholder mright-20 rounded-sm"></div>
                            <div class="ph-col-4 ph-h75 bg-placeholder rounded-sm"></div>
                        </div>
                        <div class="ph-row mbottom-30">
                            <div class="ph-col-1 bg-placeholder mright-10"></div>
                            <div class="ph-col-11 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-4 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-20">
                            <div class="ph-col-3 bg-placeholder"></div>
                            <div class="ph-col-2 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between">
                            <div class="ph-col-4 bg-placeholder"></div>
                            <div class="ph-col-2 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-4 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-20">
                            <div class="ph-col-3 bg-placeholder"></div>
                            <div class="ph-col-2 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between">
                            <div class="ph-col-4 bg-placeholder"></div>
                            <div class="ph-col-2 bg-placeholder"></div>
                        </div>  
                    </div>
                    <div v-if="!is_loading">
                        <h2 class="mbottom-15">Hot point</h2>
                        <div class="banner hotpoint">
                            <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/files/62da4446a4675-files1658471494.webp" alt="">
                        </div>
                        <div class="d-flex align-stretch mbottom-20 d-column">
                            <div class="box-hotpoint total">
                                hot point
                                <div class="hotpoint">
                                    
                                    <img class="mright-10 d-block" src="/img/assets_icon_hotpoint.svg" alt="">
                                    {{mypoint | NumberFormat}}
                                </div>
                            </div>
                            <div class="box-hotpoint btn-add">
                                <router-link class="d-flex align-center" :to="'/hotpoint-voucher'">
                                    <img class="d-block" src="/img/add_point_ct.svg" alt="">
                                    <img class="d-none" src="/img/assets_arrow_white.svg" alt="">
                                    tambah hot point dari cashtree
                                </router-link>
                            </div>
                        </div>
                        <div class="filter">
                            <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                            <div class="overlay-transparent d-none"></div>
                            <ul class="small-box-filter">
                                <li class="filter-title-mobile">
                                    <div class="fs-12 fp-black pl-12">Filter</div>
                                    <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                                </li>
                                <li :class="pagination.filter == 'all' ? 'active' : ''" @click="pointList(pagination.page = 1 , pagination.filter = 'all')">Semua transaksi</li>
                                <li :class="pagination.filter == 'earn' ? 'active' : ''" @click="pointList(pagination.page = 1 , pagination.filter = 'earn')">Perolehan poin</li>
                                <li :class="pagination.filter == 'use' ? 'active' : ''" @click="pointList(pagination.page = 1 , pagination.filter = 'use')">Poin Digunakan</li>
                            </ul>
                        </div>
                        <div class="hotpoint-history" v-if="Object.keys(hotpoint).length > 0 ">
                            <div class="list" v-for="(item , index) in hotpoint" :key="index">
                                <div class="hotpoint-transaction" v-if="item.type == 'earn'">
                                    <div class="d-flex align-center mbottom-12">
                                        <img width="35" class="mright-10" :src="item.image" alt="">
                                        <h5 class="fw-700">{{item.status}}</h5>
                                    </div>
                                    <div class="detail-transaction">
                                        <div class="item-detail">
                                            <h5 class="fw-700">{{item.title}}</h5>
                                            <span class="fs-black fw-400">{{item.detail}}</span>
                                            <!-- <div class="fp-purple fw-400"><b>Invoice</b> INV/20220304/MPL/2101213326</div> -->
                                        </div>
                                        <div class="point-detail d-flex flex-column align-end">
                                            <div class="point earn mbottom-5">
                                                <img width="18" class="mright-5" src="/img/ic_hotpoint.svg" alt="">
                                                <img width="10" class="mright-5" src="/img/assets_get_hotpoint.svg" alt="">
                                                {{item.value | NumberFormat}}
                                            </div>
                                            <div class="get-time">
                                                {{item.created_at}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hotpoint-transaction" v-if="item.type == 'use'">
                                    <div class="d-flex align-center mbottom-12">
                                        <img width="35" class="mright-10" :src="item.image" alt="">
                                        <h5 class="fw-700">{{item.status}}</h5>
                                    </div>
                                    <div class="detail-transaction">
                                        <div class="item-detail">
                                            <h5 class="fw-700">{{item.title}}</h5>
                                            <span class="fs-black fw-400">{{item.detail}}</span>
                                            <!-- <div class="fp-purple fw-400"><b>Invoice</b> INV/20220304/MPL/2101213326</div> -->
                                        </div>
                                        <div class="point-detail d-flex flex-column align-end">
                                            <div class="point use mbottom-5">
                                                <img width="18" class="mright-5" src="/img/ic_hotpoint.svg" alt="">
                                                <img width="10" class="mright-5" src="/img/assets_use_hotpoint.svg" alt="">
                                                {{item.value | NumberFormat}}
                                            </div>
                                            <div class="get-time">
                                                {{ item.created_at }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <pagination-2 :total="pagination.total" :current="pagination.current" @changeAction="changeActionPagination($event)"></pagination-2>
                        </div>
                        <div class="mtop-20" v-else>
                            <blank-page :message="'Kamu belum memiliki Hot Poin saat ini'" :image="'img/animation_empty_hotpoint.svg'"></blank-page>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import ProfileMenu from '../desktop/component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'
    import BlankPage from '../../components/desktop/BlankPage.vue'
    import Pagination2 from '../../components/pagination/v2/pagination.vue'
    import moment from 'moment'

    export default {
        name: "HotPoint.vue",
        data(){
            return{
                profile : {},
                is_loading : true,
                mypoint : 0,
                hotpoint : {},
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                    status : '',
                    date : '',
                    search : '',
                    filter : 'all',
                    perpage : 10
                }
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            BlankPage,
            Pagination2
        },
        mounted(){
            apiCustomer.profile().then( response => {
                this.profile = response.data
            });
            this.pointList()
        },
        methods: {
            show_pin_modal(){
                $("#create_pin_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_pin_update_modal(){
                $("#change_pin_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            pointList(){
                apiCustomer.pointList(this.pagination).then( response => {
                    this.is_loading = false
                    this.hotpoint = response.data.data.point
                    this.pagination.current = response.data.data.current_page
                    this.pagination.total = response.data.data.total
                });
            },
            show_forget_pin_modal(){
                $("#forget_pin_modal_confirmation").fadeIn();
            },
            changeActionPagination(event){
                 if(event < 1 || event > this.pagination.total){
                    return false;
                }
                this.pagination.page = event
                this.pointList()
            },
            format_date(date){
                if (date){
                    return moment(String(date)).format('DD/MM/YYYY')
                }
            },
        },
    }
</script>