<template>
    <div id="wrap-notif" class="wrap">
        <div class="notif-dd pop-over">
            <h5 class="title-notif">Notifikasi</h5>
            <div v-if="notifications && Object.keys(notifications.notifications_home).length > 0 ">
                <ul class="notif-ul">
                    <li class="notif-li link" v-for="(notification, index) in notifications.notifications_home" :key="index" @click="mark_as_read(notification.id , notification.notification.url ,notification.is_read , index)">
                        <div class="d-flex align-center mbottom-5">
                            <b>{{ notification.notification.title }}</b>
                            <div v-bind:class="notification.is_read == 0 ? 'notif-'+index+' unread' : 'notif-'+index"></div>
                        </div>
                        <div class="detail-notif">
                            <div class="col-10p" v-if="notification.notification.image != '' ">
                                <div class="container-img">
                                    <img src="img/50x50.svg" alt="">
                                </div>
                            </div>
                            <div class="col-90p">{{ notification.notification.body }}</div>
                        </div>
                    </li>
                </ul>
                <router-link class="link" to="/notification">
                    <h6 class="btn-link link mtop-20">Lihat Semua</h6>
                </router-link>
                <div class="btn-link mtop-10" @click="mark_as_read('all','','','')"><h6>Tandai semua dibaca</h6></div>
            </div>
            <div v-else>
                <blank-page class="notif-blank" :message="'Belum Ada Notifikasi Untuk Anda'" :image="'/img/animation_empty_notif.svg'"></blank-page>
            </div>
        </div>
    </div>
</template>

<script>
    import Notification from "../../../apis/Notification";
    import User from "../../../apis/User";
    import BlankPage from "../BlankPage.vue"

    export default {
        name: "Notification",
        data() {
            return {
                is_login : User.is_login(),
                notifications: null
            }
        },
        components: {
            'notification' : Notification,
            BlankPage
        },
        created: function () {
            if(User.is_login()){
                this.is_login =true;
                this.notification();
            }
        },
        methods: {
            notification() {
                Notification.get().then(response => {
                    this.notifications = response.data.data;
                })
            },
            mark_as_read(id , url , is_read , index){
                if(is_read == true){
                    this.$router.push({path : url})
                }
                Notification.markAsRead({id:id}).then(response => {
                    if(response.data.code == 200){
                        if(id == 'all'){
                            this.$emit('read_notification' , false)
                            this.$router.go()
                        }else{
                            if (this.$route.path !== {path : url}) this.$router.push({path : url});
                            this.notifications.notifications[index].is_read = 1
                            $(".notif-"+index).removeClass("unread");
                            this.$emit('read_notification' , true )
                        }
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
