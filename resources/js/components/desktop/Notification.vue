<template>
    <div>
        <div class="notification">
            <div class="mbottom-10" v-if="is_loading">
                <div class="ph-row justify-between mbottom-30">
                    <div class="d-block ph-col-12 bg-placeholder ph-h15"></div>
                </div>
                <div class="ph-container justify-between mbottom-20">
                    <div class="d-block ph-col-2 bg-placeholder ph-h15 mbottom-15"></div>
                    <div class="d-block ph-col-12 bg-placeholder ph-h15 mbottom-20"></div>
                </div>
                <div class="ph-container justify-between mbottom-20">
                    <div class="d-block ph-col-2 bg-placeholder ph-h15 mbottom-15"></div>
                    <div class="d-block ph-col-12 bg-placeholder ph-h15 mbottom-20"></div>
                </div>
                <div class="ph-container justify-between mbottom-20">
                    <div class="d-block ph-col-2 bg-placeholder ph-h15 mbottom-15"></div>
                    <div class="d-block ph-col-12 bg-placeholder ph-h15 mbottom-20"></div>
                </div>
            </div>
            <div class="notification-body" v-if="!is_loading">
                <h3 class="title-section mbottom-15">Notifikasi</h3>
                <div class="slider-filter">
                    <div class="filter mbottom-20">
                        <img class="button toggle-filter" src="/img/assets_hotdeal_filter_blue.svg" alt="">
                        <div class="overlay-transparent d-none"></div>
                        <ul class="small-box-filter">
                            <li class="filter-title-mobile">
                                <div class="fs-12 fp-black pl-12">Filter</div>
                                <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                            </li>
                            <li v-bind:class="pagination.filter == 'all' ? 'active' : '' " @click="notification(pagination.filter = 'all' , pagination.page=1 )">Semua Notifikasi</li>
                            <li v-bind:class="pagination.filter == 'unread' ? 'active' : '' " @click="notification(pagination.filter = 'unread',pagination.page = 1)">Belum dibaca</li>
                            <li v-bind:class="pagination.filter == 'read' ? 'active' : '' " @click="notification(pagination.filter = 'read',pagination.page = 1)">Sudah dibaca</li>
                            <li v-bind:class="pagination.filter == 'day' ? 'active' : '' " @click="notification(pagination.filter = 'day',pagination.page = 1)">1 Hari terakhir</li>
                            <li v-bind:class="pagination.filter == 'week' ? 'active' : '' " @click="notification(pagination.filter = 'week',pagination.page = 1)">Seminggu Terakhir</li>
                            <li v-bind:class="pagination.filter == 'month' ? 'active' : '' " @click="notification(pagination.filter = 'month',pagination.page = 1)">Sebulan Terakhir</li>
                        </ul>
                    </div>
                </div>
                <div v-if="Object.keys(notifications).length > 0 ">
                    <div class="list-notif" v-for="(item , index) in notifications" :key="index" href="javascript:void(0)" @click="mark_as_read(item.id , item.notification.url ,item.is_read)">
                        <div class="row-100 align-center mbottom-10">
                            <img class="icon_notif" src="/img/icon_notifikasi.svg" alt="">
                            <h5 class="date">info | {{format_date(item.created_at)}}</h5>
                        </div>
                        <div class="row-100 title mbottom-5">
                            <h5 class="fs-black mright-10">{{item.notification.title}}</h5>
                            <div class="dot-red" v-if="!item.is_read"><span>Baru</span></div>
                        </div>
                        <div class="row-100">
                            <div class="detail-notif">{{item.notification.body}}</div>
                        </div>
                    </div>
                    <div class="row d-flex justify-right" v-if="pagination.current_page < pagination.total && pagination.total > 1 ">
                        <button class="btn-link" @click="notification(pagination.page++)">Selanjutnya</button>
                    </div>
                </div>
                <div v-else>
                    <blank-page :message="'Belum Ada Notifikasi Untuk Anda'" :image="'/img/animation_empty_notif.svg'"></blank-page>
                </div>
            </div>
        </div>

        <high-light-two-column></high-light-two-column>

        <high-light-image-only></high-light-image-only>

        <modal></modal>
    </div>
</template>

<script>
    import Modal from '../../components/desktop/Modal'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightTwoColumn from './component/product/HighLightTwoColumn.vue'
    import Notification from "../../apis/Notification";
    import BlankPage from "../../components/desktop/BlankPage.vue"
    import moment from 'moment'

    export default {
        data(){
            return {
                notifications : {},
                is_loading : true,
                pagination : {
                    current_page : 0,
                    page : 1,
                    total : 0,
                    filter : 'all'
                }
            }
        },
        name: "Notification.vue",
        mounted(){
            this.notification();
        },
        methods: {
            show_modal_wait(){
                $("#detail_modal").fadeIn(function () {
                    $("#detail_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_sent(){
                $("#detail_modal").fadeIn(function () {
                    $("#detail_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_acc(){
                $("#detail_modal").fadeIn(function () {
                    $("#detail_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            notification() {
                Notification.allnotification(this.pagination).then(response => {
                    if(response.data.data.current_page > 1){
                        for (const [i, value] in response.data.data.data) {
                            this.notifications.push(response.data.data.data[i]);
                        }
                    }else{
                        this.notifications = response.data.data.data;
                    }

                    this.pagination.current_page = response.data.data.current_page
                    this.pagination.total = response.data.data.total
                    this.pagination.page = response.data.data.current_page
                    this.is_loading = false
                    
                })
            },
            mark_as_read(id , url , is_read){
                if(is_read){
                    window.location.href = url
                }
                Notification.markAsRead({id:id}).then(response => {
                    if(response.data.code == 200){
                        window.location.href = url
                    }
                })
            },
            format_date(date){
                if (date){
                    return moment(String(date)).format('DD / MM / YYYY')
                }
            }
        },
        components:{
            Modal,
            HighLightTwoColumn,
            HighLightImageOnly,
            BlankPage
        },
    }
</script>

<style scoped>

</style>
