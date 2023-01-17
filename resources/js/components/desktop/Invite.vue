<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-40">
                <profile-menu></profile-menu>
                <profile-point-info @myPoint="mypoint = $event" v-if="Object.keys(profile).length > 0" :profile="profile.users"></profile-point-info>
                <!-- <div class="banner-mobile">
                    <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/Hotdeal-x-Cashtree-Banner-Web-Mobile.webp" alt="">
                </div> -->
            </div>
            <div class="flex-basis-60">
                <div class="box3-shadow-white invite" id="profile-menu" >
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
                        <h2 class="mbottom-15">Ajak teman</h2>
                        <div class="banner invite">
                            <img src="/img/banner_invite.svg" alt="">
                        </div>
                        <div class="box3-purple mbottom-10">
                            <div class="d-flex align-center justify-between">
                                <h5>Teman</h5>
                                <span>{{summary_data.child}}</span>
                            </div>
                            <hr>
                            <div class="d-flex align-center justify-between">
                                <h5>Transaksi</h5>
                                <span>{{summary_data.transaction.transaction}}</span>
                            </div>
                            <hr>
                            <div class="d-flex align-center justify-between">
                                <h5>Cashback</h5>
                                <span>{{summary_data.transaction.total | RupiahFormat}}</span>
                            </div>
                        </div>
                        <div class="box-grey-invite mbottom-20">
                            <div class="d-flex align-center copy-link">
                                <div class="d-flex flex-column align-start">
                                    <h5>Link Invite</h5>
                                    <span class="fp-pink fw-600" id="copy-text">{{summary_data.url}}</span>
                                </div>
                                <button v-if="referal_code != null " class="btn-copy mleft-10" @click="copyToClipboard('#copy-text')" >salin</button>
                                <button v-else class="btn-copy mleft-10" @click="generateCode()" >Generate</button>
                            </div>
                            <div class="d-flex align-center">
                                <h5 class="mright-5">Bagikan link</h5>
                                <div class="icon-share">
                                    <a target="blank" :href="'whatsapp://send?text='+ summary_data.url">
                                        <img src="/img/invite_wa.svg" alt="" width="30">
                                    </a>
                                    <a target="blank" :href="'https://www.facebook.com/sharer/sharer.php?u='+summary_data.url">
                                        <img src="/img/invite_facebook.svg" alt="" width="30">
                                    </a>
                                    <a target="blank" :href="'https://twitter.com/intent/tweet?text='+summary_data.url">
                                        <img src="/img/invite_twitter.svg" alt="" width="30">
                                    </a>
                                    <a target="blank" class="line-btn" :href="'line://msg/text/'+summary_data.url">
                                        <img src="/img/share_line.svg" alt="" width="30">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-tab mbottom-15">
                            <div class="col-4 col-sm-6 tab-joining pointer" v-bind:class="menu_active == 'first' ? 'active' : '' " @click="tab('first')">
                                <h5>Cara ikutan</h5>
                                <hr>
                            </div>
                            <div class="col-4 pointer" v-bind:class="menu_active == 'second' ? 'active' : '' " @click="tab('second')" id="tnc">
                                <h5>Syarat &amp; Ketentuan</h5>
                                <hr>
                            </div>
                            <div class="col-4 col-sm-6 pointer" v-bind:class="menu_active == 'third' ? 'active' : '' " @click="tab('third')" id="referral_history">
                                <h5>Riwayat Referal</h5>
                                <hr>
                            </div>
                        </div>
                        <div class="tab-content-invite">
                            <div class="content" v-show="menu_active == 'first'">
                                <ol class="mbottom-30">
                                    <div class="point-how-to">
                                        <img src="/img/referal-1.svg" alt="">
                                        <li>
                                            <b>Bagikan Link Referral</b>
                                            <br>
                                            Ajak temanmu untuk ikut daftar menjadi member Hotdeal, sebanyak-banyaknya yaaa!
                                        </li>
                                    </div>
                                    <div class="point-how-to">
                                        <img src="/img/referal-2.svg" alt="">
                                        <li>
                                            <b>Belanja di Hotdeal</b>
                                            <br>
                                            Pastikan temanmu sudah mendaftar dan terverifikasi plus melakukan transaksi pembelian di website Hotdeal.
                                        </li>
                                    </div>
                                    <div class="point-how-to">
                                        <img src="/img/referal-3.svg" alt="">
                                        <li>
                                            <b>Dapat Cashback</b>
                                            <br>
                                            Raih komisi untuk setiap pembelian yang teman kamu lakukan. Ulangi lagi dari step 1 biar kamu makin untung!
                                        </li>
                                    </div>
                                </ol>
                            </div>
                            <div class="menu-tab mobile mtop-10">
                                <div class="col-12 d-flex align-center justify-center" @click="term_show()">
                                    <h5 class="mright-5">Syarat &amp; Ketentuan</h5>
                                    <img class="term-arrow pointer" src="/img/arrow_purple.svg" alt="" width="10">
                                </div>
                            </div>
                            <div class="tab-content-invite mobile">
                                <div class="content">
                                    <ol class="point-tnc">
                                        <li>Promo berlaku selama periode tanggal 1-31 Juli 2022.</li>
                                        <li>Promo yang diberikan adalah Gratis Tiket Konser KV Fest 2022.</li>
                                        <li>Promo dilakukan dengan skema tiket undian atau raffle ticket.</li>
                                        <li>Promo berlaku untuk pembelian semua produk di HOTDEAL.</li>
                                        <li>Minimum transaksi dalam 1 (satu) kali checkout sebesar 100K, berlaku untuk setiap kode pesanan.</li>
                                        <li>Setiap pembelian produk spesial bundling, peserta akan mendapatkan raflle ticket dengan rincian sebagai berikut.
                                            <ul>
                                                <li>Min. belanja 100K → Get 1 raffle ticket</li>
                                                <li>Min. belanja 300K → Get 5 raffle ticket</li>
                                                <li>Min. belanja 500K → Get 10 raffle ticket</li>
                                            </ul>
                                        </li>
                                        <li>Untuk pembelian produk non bundling, peserta tetap akan mendapatkan raffle ticket dengan rincian sebagai berikut. Min. belanja 100K → Get 1 raffle ticket</li>
                                        <li>Promo berlaku kelipatan.</li>
                                        <li>Promo ini tidak bisa digabungkan dengan promo lainnya, kecuali promo utama REJEKI NOMPLOK.</li>
                                        <li>Pemenang yang beruntung akan diundi di akhir periode berdasarkan kode raffle ticket yang dikumpulkan.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <modal-share :url="'https://hotdeal.id/about-us'"></modal-share>  -->
        <modal-alert :message="modal_message"></modal-alert>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import ProfileMenu from '../desktop/component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'    
    import BlankPage from './BlankPage.vue';
    import ModalAlert from './modal/ModalAlert.vue'


    export default {
        name: "Invite.vue",
        data(){
            return{
                modal_message : '',
                profile : {},
                summary_data : {},
                is_loading : true,
                menu_active : 'first',
                load_more : false,
                tab_content : '',
                referal_code : null,
                history_transaction : [],
                pagination : {
                    total : 0,
                    page : 1,
                },
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            BlankPage,
            ModalAlert
        },
        mounted(){
            apiCustomer.profile().then( response => {
                this.profile = response.data
                this.referal_code =  response.data.referal_code
            });
            this.summary()
        },
        methods: {
            term_show(){
                $(".tab-content-invite.mobile" ).slideToggle(500);
                $(".term-arrow" ).toggleClass('flip');
            },
            copyToClipboard(element) {
                this.modal_message = 'Berhasil salin link';
                $("#alert_modal").fadeIn(function (){
                    $("body").addClass('overflow-hidden')
                })
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $temp.remove();
                setTimeout(() => {
                    $("#alert_modal").fadeOut(function () {
                        $("body").removeClass('overflow-hidden');
                    });
                }, 1200);
            },
            tab(id){
               this.menu_active = id;
               if(id == 'third'){
                    this.historyTransaction()
               }
            },
            historyTransaction(){
                apiCustomer.inviteHistory(this.pagination).then( response => {
                    if(response.data.data.total > 0){
                        this.push_data(response.data.data.data)
                        this.pagination.page = response.data.data.current_page
                        this.pagination.total = response.data.data.total
                    }
                    if(this.pagination.page < this.pagination.total){
                        this.load_more = true
                    }else{
                        this.load_more = false
                    }
                }); 
            },
            push_data(data){
                if(data.length > 0){
                    data.forEach((element) => {
                        this.history_transaction.push(element)
                    });
                }
            },
            summary(){
                apiCustomer.inviteSummary().then( response => {
                    this.summary_data = response.data.data
                    this.is_loading = false
                });
            },
            generateCode(){
                var text = $('#copy-text').text()
                apiCustomer.inviteGenerateCode().then( response => {
                    this.referal_code =  response.data.data.referal_code
                    $('#copy-text').text(text+this.referal_code)
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