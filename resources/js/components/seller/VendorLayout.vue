<template>
    <div>
        <header class="header-seller">
            <div class="relative nav-container">
                <div class="navbar-parent">
                    <div class="navbar">
                        <div class="d-flex align-center">
                            <div class="nav-logo"></div>
                            <h2 class="mleft-20">hotdeal seller center</h2>
                        </div>
                        <div class="d-flex align-center">
                            <div class="nav-menu">
                                <li class="notif">
                                    <div class="wrapper-notif">
                                        <img src="/img/assets_hotdeal_notif.svg" alt="" width="22">
                                    </div>
                                    <header-notification></header-notification>
                                    <badge :num="materials.notification"></badge>
                                </li>
                            </div>
                            <hr>
                            <div class="nav-account">
                                <div class="profile" v-if="is_login">
                                    <div v-if="loading_profile" class="ph-row align-center">
                                        <div class="bg-placeholder profile-ph-sm mright-5"></div>
                                        <div style="height:5px" class="bg-placeholder"><p></p></div>
                                    </div>
                                    <div class="profile-flex" v-if="!loading_profile">
                                        <div class="container-img">
                                            <img class="d-block" src="/img/assets_hotdeal_user.svg" alt="">
                                        </div>
                                        <p v-if="profile">{{profile.name}}</p>
                                    </div>
                                    <header-profile></header-profile>
                                </div>
                                <div class="option-button" v-if="!is_login">
                                    <button class="btn-secondary btn-login" v-on:click="openForm('login')">Masuk</button>
                                    <button class="btn-primary" v-on:click="openForm('register')">Daftar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-seller">
            <div class="overlay-primary seller pop-over"></div>
            <div class="row-100">
                <div class="breadcrumb-seller">
                    <div class="col-12 page">
                        <h6 class="prev">hotdeal seller center</h6>
                        <hr>
                        <div v-for="(item, index ) in ulcrumbs" :key="index" v-if="item.link == '' || index === ulcrumbs.length - 1">
                            <h6 class="current" v-if="item.link === ''">{{ item.name }}</h6>
                            <router-link v-if="item.link !== ''" :to="item.link">{{ item.name }}</router-link>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row-100">
                <div class="sidebar">
                    <div class="sidebar-brand"></div>
                    <div class="sidebar-menu">
                        <div class="box-filter-purple">
                            <div class="content">
                                <h4>Hotdeal Seller Center</h4>
                                <div class="side-menu">
                                    <ul class="menu">
                                        <li>
                                            <div class="icon-link">
                                                <img src="/img/ic_overview.svg" alt="">
                                                <router-link to="/vendor/dashboard">gambaran umum</router-link>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon-link">
                                                <img src="/img/ic_order.svg" alt="">
                                                <a class="dropdown close" href="#">pesanan</a>
                                                <img class="arrow" src="/img/ic_arrow_down.svg" alt="">
                                            </div>
                                            <ul class="sub-menu">
                                                <li><router-link to="/vendor/order/list">pesanan saya</router-link></li>
                                                <li><router-link to="/vendor/order/list/refund">pengembalian</router-link></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <div class="icon-link">
                                                <img src="/img/ic_product.svg" alt="">
                                                <a class="dropdown close" href="#">produk</a>
                                                <img class="arrow" src="/img/ic_arrow_down.svg" alt="">
                                            </div>
                                            <ul class="sub-menu">
                                                <li><router-link to="/vendor/product/list">daftar produk</router-link></li>
                                                <li><router-link to="/vendor/product/create">tambah produk</router-link></li>
                                            </ul>
                                        </li>
                                        <!-- <li>
                                            <div class="icon-link">
                                                <img src="/img/ic_overview.svg" alt="">
                                                <router-link to="/vendor/review/list">review</router-link>
                                            </div>
                                        </li> -->
                                        <li>
                                            <div class="icon-link">
                                                <img src="/img/ic_finance.svg" alt="">
                                                <a class="dropdown close" href="#">keuangan</a>
                                                <img class="arrow" src="/img/ic_arrow_down.svg" alt="">
                                            </div>
                                            <ul class="sub-menu">
                                                <li><a href="#">Sub Menu I</a></li>
                                                <li><a href="#">Sub Menu II</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <router-view style="width:80%"></router-view>
            </div>
        </div>

        <div id="myOverlay" class="overlay">
            <transition name="fade" mode="out-in">
                <div id="wrap-homepage">
                    <span class="closebtn" v-on:click="closeForm()" title="Close Overlay"></span>
                    <div class="row-100">
                        <div class="col-2">
                            <img src="/img/549x820.svg" alt="" width="100%">
                        </div>
                        <div class="col-2 form" v-if="auth_active === 'register'">
                            <h3>Daftar Sekarang</h3>
                            <form class="relative" action="" method="POST">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" :class="error.name ? 'error' : '' " id="name" name="name" v-model="form_register.name">
                                    <small class="error" v-if="error.name">{{ error.name[0]}}</small>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" :class="error.name ? 'error' : '' " name="email" v-model="form_register.email">
                                    <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                                </div>
                                <div class="d-flex">
                                    <div class="col-2">
                                        <label>Password</label>
                                        <input class="w-85" type="password" :class="error.name ? 'error' : '' " name="password" v-model="form_register.password">
                                        <small class="error" v-if="error.password">{{ error.password[0]}}</small>
                                    </div>
                                    <div class="col-2 mbottom-20">
                                        <label>Password Confirmation</label>
                                        <input class="w-90" type="password" :class="error.name ? 'error' : '' " name="password_confirmation" v-model="form_register.password_confirmation">
                                        <small class="error" v-if="error.password_confirmation">{{ error.password_confirmation[0]}}</small>
                                    </div>
                                </div>
                                <div class="form-group mtop-20 last">
                                    <button class="btn-darkblue" type="button" @click.prevent="register">Register</button>
                                </div>
                                <a href="javascript:void(0)" @click="change_auth('login')"><h6>Already have an Account?  Login Now</h6></a>
                            </form>
                        </div>
                        <div class="col-2 form" v-if="auth_active === 'login'">
                            <h3>Masuk</h3>
                            <form class="relative" action="" method="POST">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" :class="error.email ? 'error' : '' " name="email" v-model="form_login.email">
                                    <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" :class="error.name ? 'error' : '' " name="password" v-model="form_login.password">
                                    <small class="error" v-if="error.password">{{ error.password[0]}}</small>
                                </div>
                                <div class="form-group mtop-20">
                                    <button class="btn-darkblue" type="button" @click.prevent="login">Login</button>
                                </div>
                                <a href="javascript:void(0)" @click="change_auth('register')"><h6>Don't have an account yet?  Sign Up</h6></a>
                                <h5 class="text-strike relative">Atau Masuk Dengan</h5>
                                <div class="btn-login">
                                    <button>
                                        <img src="/img/google_logo.png" alt="">
                                    </button>
                                    <button>
                                        <img src="/img/facebook_logo.png" alt="">
                                    </button>
                                </div>
                            </form>
                            <a href=""><h6>Kebijakan Privasi</h6></a>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <footer>
            <div class="content-footer">
                <div class="footer-bg">
                    <div class="row-80">
                        <img class="logo-white" src="/img/logo.svg" alt="">
                    </div>
                    <div class="row-80 mbottom-30">
                        <div id="ob_about_us" class="data-footer">
                            <div class="col-4">
                                <div class="row-100">
                                    <h4>Hotdeal Indonesia</h4>
                                </div>
                                <div class="row-100 mbottom-20">
                                    <h6 class="about">Hotdeal adalah video commerce pertama di Indonesia yang menawarkan berbagai produk eksklusif dan berkualitas dengan harga bersaing. Dapatkan pengalaman belanja menyenangkan hanya di hotdeal.id.</h6>
                                </div>
                                <div class="row-100 mbottom-20">
                                    <h6 class="about">Untuk informasi lebih lengkap hubungi kami di <strong>info@hotdeal.id</strong></h6>
                                </div>
                                <div class="row-100">
                                    <h6 class="about">Find Your Lucky, Get Uang Kembali</h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="item-footer">
                                    <h4 class="mbottom-15">Informasi</h4>
                                </div>
                                <div class="item-footer">
                                    <ul class="info-footer">
                                        <li><router-link to="/about-us">Tentang Kami</router-link></li>
                                        <li><router-link to="/help-center">FAQ</router-link></li>
                                        <li v-for="(item, index) in policylist.menu" :key="index" v-bind:class="active_class == index ? 'active': ''" @click="active_class = index">
                                            <router-link :to="'/policies?index=' + index" v-on:click.native= "reloadFunction()" >{{item}}</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-4 newsletter">
                                <div class="item-footer">
                                    <h4 class="white">Newsletter</h4>
                                </div>
                                <div class="item-footer">
                                    <h6 class="white">Jadilah yang pertama mendapatkan berbagai penawaran menarik dari <b>hotdeal.id</b></h6>
                                </div>
                                <div class="item-footer">
                                    <form>
                                        <input type="email" id="email" placeholder="Email anda" v-model="newsletter_email">
                                    </form>
                                </div>
                                <div class="item-footer">
                                    <button class="btn-primary" @click="newsletter">Berlangganan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-80 mbottom-20">
                        <ul class="logo-socmed">
                            <li><a target="blank" href="https://www.facebook.com/Hotdeal-ID-108449628042326"><img src="/img/assets_footer_facebook.svg" alt=""></a></li>
                            <li><a target="blank" href="https://twitter.com/hotdealdotid"><img src="/img/assets_footer_twitter.svg" alt=""></a></li>
                            <li><a target="blank" href="https://www.instagram.com/hotdealindonesia"><img src="/img/assets_footer_instagram.svg" alt=""></a></li>
                            <li><a target="blank" href="https://www.tiktok.com/@hotdealindonesia"><img src="/img/assets_footer_tiktok.svg" alt=""></a></li>
                            <li><a target="blank" href="https://www.youtube.com/channel/UCuixX2jg5Pr8KdhADcKmlWQ/"><img class="yt" src="/img/assets_footer_youtube.svg" alt=""></a></li>
                        </ul>
                    </div>
                    <div class="row-80" v-if="!accepted_cookie">
                        <div class="col-12">
                            <div class="container-cookie">
                                <h5>We use cookies to improve your experience. Happy shopping!</h5>
                                <button v-on:click="accept_cookie">Accept</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <modal-message></modal-message>
        <modal-loading></modal-loading>
    </div>
</template>

<script>
    import User from '../../apis/User'
    import Notification from '../../apis/Notification'
    import HeaderNofitication from './header/Notification.vue'
    import HeaderProfile from './header/Profile.vue'
    import Badge from '../desktop/component/Badge.vue'
    import apiCustomer from '../../apis/Customer'
    import ModalMessage from '../desktop/modal/ModalMessage.vue'
    import ModalLoading from '../desktop/modal/ModalLoading.vue'
    import ModalAlert from '../desktop/modal/ModalAlert.vue'
    import Message from '../../utils/Message'



    export default {
        data(){
            return{
                auth_active:'',
                form_register : {
                    name : '',
                    email : '',
                    password :'',
                    password_confirmation : ''
                },
                form_login : {
                    email : '',
                    password : '',
                    device_name : navigator.platform
                },
                error : {},
                profile : {},
                is_login : User.is_login(),
                accepted_cookie : localStorage.getItem('accepted_cookie') ? true : false,
                notif : {},
                newsletter_email: '',
                ulcrumbs :{},
                materials : {
                    countCart : 0,
                    notification : 0,
                    message : 0
                },
                policylist : {},
                active_class : '',
                loading_profile : true,
                modal_message : '',
            }
        },
        components: {
            'header-profile' : HeaderProfile,
            'header-notification' : HeaderNofitication,
            'notification' : Notification,
            Badge,
            ModalMessage,
            ModalLoading,
            ModalAlert,
            Message
        },
        created: function () {
            if(User.is_login()){
                this.is_login =true;
                this.get_profile();
                this.notification();
                this.crumbs();
                this.menubar_materials();

            }
        },
        watch: {
            $route(to, from) {
                this.crumbs()
            }
        },
        mounted() {
            this.get_policy()
        },
        methods: {
            crumbs() {
                this.ulcrumbs = {};
                this.ulcrumbs = this.$route.meta.breadcrumb;
            },
            menubar_materials(){
                apiCustomer.menubar_materials().then( response => {
                    this.materials = response.data.data;
                });
            },
            openForm(form){
                this.auth_active = form;
                document.getElementById("myOverlay").style.display="block";
                document.querySelector('body').style.overflow = "hidden";
            },
            closeForm(){
                this.error = {};
                document.getElementById("myOverlay").style.display="none";
                document.querySelector('body').style.overflow = "auto";
            },
            register(){
                this.error = {};
                User.register(this.form_register).then(response => {
                    if (response.code === 200){
                        this.$router.push({name:'login'});
                    }else{
                        this.error = response.data.message;
                    }
                })
            },
            login(){
                this.error = {};
                User.login(this.form_login).then(response => {
                    this.loading_profile = true
                    if (response.data.code === 200){
                        localStorage.setItem('auth','true');
                        this.form_login = {};
                        User.profile().then(response =>{
                            this.is_login = true;
                            this.closeForm();
                        })
                    }else{
                        this.error = response.data.errors;
                    }
                    this.loading_profile = false
                }).catch((errors) => {
                    this.error = errors.response.data.errors;
                });
            },
            get_profile(){
              User.profile().then(response => {
                  this.profile = response.data;
                  this.loading_profile = false;
              })
            },
            notification() {
                Notification.get().then(response => {
                    this.notif = response.data.data;
                })
            },
            change_auth(form){
                this.error = {};
                this.auth_active = form;
            },
            accept_cookie(){
                localStorage.setItem('accepted_cookie','true');
                this.accepted_cookie = true;
            },
            newsletter() {
                this.error = {};
                User.newsletter({email: this.newsletter_email}).then(response => {
                    // alert(response.code)
                    if (response.data.code === 200){
                        this.newsletter_email = '';
                        this.modal_message = response.data.message
                        Message.alert(response.data.message,'Informasi');
                    }else{
                        this.error = response.data.message;
                        this.modal_message = response.data.message
                        Message.alert(response.data.message,'Gagal');
                    }
                })
            },
            get_policy(){
                apiCustomer.get_privacy().then( response => {
                    this.policylist = response.data.data;
                });
            },
        },
    }
</script>


<style>
    /* button{
        cursor:pointer;
    } */
    /* .cursor-pointer{
        cursor : pointer;
    } */
</style>
