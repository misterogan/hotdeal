<template>
    <div>
        <div class="main-content">
            <div class="center-menu" style="width: 100%">
                <div class="main-menu">
                    <div class="box notification">
                        <h3>Notifikasi</h3>
                        <div class="row mtop-30">
                            <div class="row-100">
                                <div class="w-100 notif-list">
                                    <div class="row-100">
                                        <div class="filter">
                                            <img src="/img/assets_hotdeal_filter.svg" alt="">
                                            <ul>
                                                <li v-bind:class="pagination.filter == 'all' ? 'active' : '' " @click="notification(pagination.filter = 'all' , pagination.page=1 )">Semua Notifikasi</li>
                                                <li v-bind:class="pagination.filter == 'unread' ? 'active' : '' " @click="notification(pagination.filter = 'unread',pagination.page = 1)">Belum dibaca</li>
                                                <li v-bind:class="pagination.filter == 'read' ? 'active' : '' " @click="notification(pagination.filter = 'read',pagination.page = 1)">Sudah dibaca</li>
                                                <li v-bind:class="pagination.filter == 'day' ? 'active' : '' " @click="notification(pagination.filter = 'day',pagination.page = 1)">1 Hari terakhir</li>
                                                <li v-bind:class="pagination.filter == 'week' ? 'active' : '' " @click="notification(pagination.filter = 'week',pagination.page = 1)">Seminggu Terakhir</li>
                                                <li v-bind:class="pagination.filter == 'month' ? 'active' : '' " @click="notification(pagination.filter = 'month',pagination.page = 1)">Sebulan Terakhir</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="notif-list" v-if="Object.keys(notifications).length > 0 ">
                                        <div class="list-notif" v-for="(item , index) in notifications" :key="index" href="javascript:void(0)" @click="mark_as_read(item.id , item.notification.url ,item.is_read)">
                                            <div class="col-5p">
                                                <div class="dot-red" v-if="!item.is_read"></div>
                                            </div>
                                            <div class="col-95p">
                                                <div class="row-100">
                                                    <div class="title">
                                                        <h5>{{item.notification.title}}</h5>
                                                        <h5 class="date">{{format_date(item.created_at)}}</h5>
                                                    </div>
                                                </div>
                                                <div class="row-100">
                                                    <div class="detail-notif">
                                                        <div class="col-5p" v-if="item.notification.image != ''">
                                                            <div class="container-img">
                                                                <img src="img/50x50.svg" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-90p">
                                                            <h6>{{item.notification.body}}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-right" v-if="pagination.current_page < pagination.total && pagination.total > 1 ">
                                            <button class="more" @click="notification(pagination.page++)">Selanjutnya</button>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <blank-page :message="'Belum Ada Notifikasi Untuk Anda'" :image="'/img/animation_empty_notif.svg'"></blank-page>
                                    </div>
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
    import BlankPage from "../desktop/BlankPage.vue"
    import Notification from "../../apis/Notification";
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
        name: "VendorNotification.vue",
        mounted(){
            this.notification();
        },
        methods: {
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
                    this.$router.go()
                }
                Notification.markAsRead({id:id}).then(response => {
                    if(response.data.code == 200){
                        this.$router.go()
                    }
                })
            },
            format_date(date){
                if(date){
                    return moment(String(date)).format('DD / MM / YYYY')
                }
            }
        },
        components:{
            BlankPage
        },
    }

</script>

<style scoped>

</style>