<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-60 margin-none">
                <div class="box3-shadow-white box3-noshadow-mobile" id="profile-menu" >
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
                        <div class="banner invite">
                            <img src="/img/banner_invite.svg" alt="">
                        </div>
                        <div class="d-flex align-center justify-between mbottom-15">
                            <h2>Riwayat Ajak Teman</h2>
                            <!-- <div class="d-flex align-center fp-purple fw-700">
                                <img class="mright-7" width="22" src="/img/assets_filter.svg" alt="">
                                Filter
                            </div> -->
                        </div>
                        <div class="tab-content-invite w-100">
                            <div class="content">
                                <div class="history-referral detail" v-if="history_transaction.length > 0">
                                    <div class="head-table" >
                                        <div class="col-3">Email</div>
                                        <div class="col-3">Tanggal Daftar</div>
                                        <div class="col-3">Tanggal Transaksi</div>
                                        <div class="col-3">Hot Point Diperoleh</div>
                                    </div>
                                    <div class="body-table" v-for="(item , index) in history_transaction" :key="index" >
                                        <div class="col-3 fw-600">{{item.user.email}}</div>
                                        <div class="col-3 fw-400">{{item.user.register}}</div>
                                        <div class="col-3 fw-400">{{item.created_at}}</div>
                                        <div class="col-3 fw-400">{{item.value | NumberFormat}}</div>
                                    </div>
                                    <button class="mobile-button mtop-15">
                                        <router-link to="/history-refferal">Lihat Semua Riwayat</router-link>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    
    export default {
        name: "HotPoint.vue",
        data(){
            return{
                pagination : {
                    total : 0,
                    current_page : 0
                },
                history_transaction : {},
                is_loading : true,
            }
        },
        components : {
           
        },
        mounted(){
            apiCustomer.inviteHistory().then( response => {
                if(response.data.data.total > 0){
                    this.history_transaction = response.data.data.data
                    this.pagination.current_page = response.data.data.current_page
                    this.pagination.total = response.data.data.total
                }
            });
        },
        methods: {
            copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $temp.remove();
            },
            tab(id){
               this.menu_active = id;
            },
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
            }
        },
    }
</script>