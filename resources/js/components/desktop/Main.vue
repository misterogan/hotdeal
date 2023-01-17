<template>
    <div>
        <header>
            <div class="relative nav-container">
                <div class="navbar-parent">
                    <div class="navbar">
                        <router-link class="link" to="/">
                            <div :class="is_backable ? 'nav-logo nav-back'
                            :'nav-logo'"></div>
                        </router-link>
                        <div class="nav-search">
                            <div class="container-search">
                            <form autocomplete="off" v-on:submit.prevent="searchProduct()">
                                    <input name="search" placeholder="Mau barang apa? Cari di sini ..." class="searching-desktop search"
                                        v-model="searchValue" type="search" autocomplete="off"
                                        v-on:click="searchRecommendation"
                                        @keyup="searchProduct()"
                                        v-on:focusout="focusout"
                                        v-on:keyup.enter = "search"
                                        :search="searchValue"
                                    />
                                    <img class="ic_search" src="/img/assets_hotdeal_search.svg" alt="" @click="search()">
                                    <div class="autocomplete pop-over">
                                        <div class="content-searching">
                                            <div class="autocomplete-search">
                                                <div v-if="search_not_found">
                                                    <i class="mright-5" style="font-weight:600;">{{searchValue}}</i> tidak ditemukan. Gunakan kata kunci yang lain.
                                                </div>
                                                <ul class="mbottom-15" v-if="recommended_keywords">
                                                    <li v-for="(item, index) in recommended_keywords" :key="index">
                                                        <router-link style="padding:5px 0;" class="search-popular btn-link" :to="'/product?search='+item">
                                                        <img class="mright-10" src="/img/ic_search.svg" alt="">{{ item }}</router-link>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="last-search" v-if="Object.keys(history_search).length > 0">
                                                <div class="title-search">
                                                    <h6>terakhir dicari</h6>
                                                    <div class="btn-link pointer" @click="deleteHistory('all', 0 , 'all')">hapus semua</div>
                                                </div>
                                                <ul class="mbottom-15">
                                                    <li v-for="(item, index) in history_search" :key="index">
                                                        <img src="/img/ic_trash_grey.svg" @click="deleteHistory(item.keyword , index , 'keyword')" alt="">
                                                        <router-link style="padding: 5px 10px;" class="search-popular btn-link" :to="'/product?search='+item.keyword">{{ item.keyword }}</router-link>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="popular-search" v-if="Object.keys(search_recommendation).length > 0">
                                                <div class="title-search">
                                                    <h6>pencarian populer</h6>
                                                </div>
                                                <ul>
                                                    <li style="padding:10px 5px;" v-for="(item, index) in search_recommendation.popular_products" :key="index">
                                                        <router-link class="search-popular btn-link" v-if="item.products !== null" :to="'/product-detail/'+item.products.slug">
                                                            <img :src="item.image.link" alt="">
                                                            <div>
                                                                <div class="elipsis-1">{{ item.products.name }}</div><div class="description elipsis-2" v-html="item.products.description"></div>
                                                            </div>
                                                        </router-link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="suggestion">
                                    <p>Pencarian Pilihan :</p>
                                    <ul class="d-flex" v-if="Object.keys(category_popular).length > 0">
                                        <li v-for="(item, index) in category_popular" :key="index">
                                            <router-link :to="'/product?search='+ item.category">{{ item.category }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <ul class="nav-menu">
                            <li class="promo link">
                                <router-link to="/promo-list">
                                    <img src="/img/assets_hotdeal_promo.svg" alt="" width="20">
                                    <badge :num="materials.promotion"></badge>
                                </router-link>
                            </li>
                            <li class="cart link" v-if="is_login">
                                <router-link to="/cart">
                                    <img src="/img/assets_hotdeal_cart.svg" alt="" width="20">
                                    <badge :num="materials.countCart"></badge>
                                </router-link>
                            </li>
                            <li class="notif" v-if="is_login">
                                <div class="wrapper-notif">
                                    <img src="/img/assets_hotdeal_notif.svg" alt="" width="20">
                                    <badge :num="materials.notification"></badge>
                                </div>
                                <header-notification @read_notification="materials.notification--"></header-notification>
                            </li>
                        </ul>
                        <div class="nav-account">
                            <div class="profile" v-if="is_login">
                                <div v-if="loading_profile" class="ph-row align-center">
                                    <div class="bg-placeholder profile-ph-sm mright-5"></div>
                                    <div style="height:5px" class="bg-placeholder"><p></p></div>
                                </div>
                                <div class="profile-flex" v-if="!loading_profile">
                                    <div class="container-img">
                                        <img class="d-block" v-if="profile.image" :src="profile.image">
                                        <img class="d-block" v-else src="/img/dummy_profile.svg" alt="">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <p v-if="profile && !loading_profile">{{profile.name}}</p>
                                        <p class="fp-pink w-100">{{profile.point | PointFormat}} Point</p>
                                    </div>
                                </div>
                                <header-profile></header-profile>
                            </div>
                            <div class="option-button" v-else>
                                <button class="btn-secondary btn-login" v-on:click="openForm('login')">Masuk</button>
                                <button class="btn-primary" v-on:click="openForm('register')">Daftar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="breadcrumb" class="breadcrumb" v-if="ulcrumbs.length > 0">
                    <div v-for="(item, index ) in ulcrumbs" :key="index">
                        <div class="d-flex" v-if="item.link == '' || index === ulcrumbs.length - 1">
                            <p class="breadcrumb-title" v-if="item.link === ''">{{ item.name }}</p>
                            <p class="breadcrumb-link" v-if="item.link !== ''">{{ item.name }}</p>
                        </div>  
                    </div>
                </div>
            </div>
        </header>

        <div id="myOverlay" class="overlay">
            <transition name="fade" mode="out-in">
                <div id="wrap-homepage" class="container-form">
                    <span class="close-btn" v-on:click="closeForm()" title="Close Overlay"></span>
                    <div class="row-100">
                        <div class="col-6 banner">
                            <div class="container-image">
                                <!-- <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/static/login.png" alt="" width="100%"> -->
                                <!-- <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/hotdeal/SNS_THR_4jt_Stories+550x820px.png" alt="" width="100%"> -->
                                <img :src="!this.popup_login_banner ? '/img/banner_login.svg' : this.popup_login_banner" alt="">
                            </div>
                        </div>
                        <!-- <div class="col-6 section" v-if="auth_active === 'register'">
                            <h3>Daftar Sekarang</h3>
                            <form action="" method="POST" v-on:submit.prevent="register()">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <div class="input-data">
                                        <input type="text" :class="error.name ? 'error' : '' " id="name" name="name" v-model="form_register.name" placeholder="Masukkan nama anda">
                                        <small class="error" v-if="error.name">{{ error.name[0]}}</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <div class="input-data">
                                        <input class="email" type="email" :class="error.name ? 'error' : '' " name="email" v-model="form_register.email" placeholder="Masukkan email anda">
                                        <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Kata Sandi</label>
                                    <div class="input-data">
                                        <input class="password" :type="passwordFieldType" :class="error.name ? 'error' : '' " name="password" v-model=" form_register.password" placeholder="Masukkan kata sandi">
                                        <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibility">
                                        <small class="error" v-if="error.password">{{ error.password[0]}}</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Kata Sandi</label>
                                    <div class="input-data">    
                                        <input class="password" :type="passwordFieldTypenew" :class="error.name ? 'error' : '' " name="password_confirmation" v-model="form_register.password_confirmation" placeholder="Konfirmasi kata sandi">
                                        <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibilitynew">
                                        <small class="error" v-if="error.password_confirmation">{{ error.password_confirmation[0]}}</small>
                                    </div>
                                </div>
                                <div class="agreement">
                                    <label class="agreement-check">Saya telah membaca dan menyetujui Kebijakan Privasi &nbsp; <b> hotdeal.id </b>
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group text-forget">
                                    <button class="btn-primary" type="submit" @click.prevent="register">Buat Akun</button>
                                </div>
                                <div class="d-flex justify-right mtop-20 mbottom-20 text-question">
                                    <label class="mright-5">Sudah memiliki akun?</label>
                                    <div class="btn-link" @click="change_auth('login')">masuk di sini</div>
                                </div>
                            </form>
                        </div> -->
                        <div class="col-6 section" v-if="auth_active === 'register'">
                            <form action="" method="">
                                <h3>Daftar Sekarang</h3> 
                                <div class="mtop-80">
                                    <label class="text-option" for="">daftar dengan : </label>
                                </div>
                                <div class="option-button register">
                                    <button class="btn-secondary btn-google" type="button" @click="AuthProvider('google')"></button>
                                    <!-- <button class="btn-secondary btn-facebook" type="button" @click="AuthProvider('facebook')"></button> -->
                                </div>
                                <div class="option-button register">
                                    <button class="btn-primary" type="button" @click="emailRegister()">Email</button>
                                </div>
                                <!-- <div class="mtop-20">
                                    <label class="text-option" for="">atau mendaftar dengan : </label>
                                </div>
                                 <div class="form-group mtop-20">
                                    <label>Email</label>
                                    <div class="input-data">  
                                        <input class="email" type="email" :class="error.email ? 'error' : '' " name="email" v-model="form_login.email" placeholder="Masukkan email anda">
                                        <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                                    </div>
                                </div>
                                <div class="form-group error">
                                    <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                                </div>
                                <div class="form-group">
                                    <button class="btn-primary" type="submit">Buat Akun</button>
                                </div>
                                <div class="box-info" style="background-color: transparent; margin: 0px;">
                                    Saya telah membaca dan menyetujui kebijakan privasi <b class="fp-purple">hotdeal.id</b>
                                </div> -->
                            </form>
                            <div class="d-flex justify-center mtop-40 text-question">
                                <label class="mright-5">Sudah memiliki akun?</label>
                                <div class="btn-link" @click="change_auth('login')">masuk di sini</div>
                            </div>
                        </div>
                        <div class="col-6 section" v-if="auth_active === 'login'">
                            <h3>Masuk</h3>
                            <form action="" method="POST" v-on:submit.prevent="login()">
                                <div class="form-group">
                                    <label>Email</label>
                                    <div class="input-data">  
                                        <input class="email" type="email" :class="error.email ? 'error' : '' " name="email" v-model="form_login.email" placeholder="Masukkan email anda">
                                        <!-- <small class="error" v-if="error.email">{{ error.email[0]}}</small> -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>kata sandi</label>
                                    <div class="input-data"> 
                                        <input class="password" :type="passwordFieldType" :class="error.email ? 'error' : '' " name="password" v-model="form_login.password" placeholder="Masukkan kata sandi anda">
                                        <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibility">
                                        <small class="error" v-if="error.password">{{ error.password[0]}}</small>
                                    </div>
                                </div>
                                <div class="form-group error">
                                    <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                                </div>
                                <div class="form-group text-forget">
                                    <a class="btn-link" href="/forget">Lupa kata sandi?</a>
                                    <button class="btn-primary" type="submit" @click.prevent="login">Masuk</button>
                                </div>
                                <label class="mbottom-55 text-option">Atau Masuk Dengan</label>
                                <div class="option-button">
                                    <!-- <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                                    </fb:login-button>

                                    <div id="status">
                                    </div>
                                     -->
                                    <button class="btn-google" type="button" @click="AuthProvider('google')" style="margin: 20px auto"></button>
                                    <!-- <button class="btn-facebook" type="button" @click="AuthProvider('facebook')"></button> -->
                                </div>
                            </form>
                            <div class="d-flex float-right">
                                <label class="mright-5">Belum memiliki akun?</label>
                                <div class="btn-link" @click="change_auth('register')">daftar sekarang</div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <modal-message></modal-message>
        <modal-loading></modal-loading>
        <modal-searching-mobile 
            :recommended_keywords="recommended_keywords" 
            :search_recommendation = "search_recommendation" 
            :history_search="history_search" 
            :search_not_found="search_not_found" 
            :searchValue="searchValue" 
            @searchProduct="searchProduct(searchValue = $event.key)"
            @searchRecomendation="searchRecommendation(searchValue = $event.key)"
            @deleteHistory="deleteHistory($event.keyword , $event.index , $event.type)"
            >
        </modal-searching-mobile>
        <div :class="is_searchable ? 'container hide-searchbox'
                :'container'">
            <div class="overlay-primary pop-over" id="purple-popover"></div>
            <div class="overlay-primary-mobile pop-over"></div>
            <div class="side-menu-mobile pop-over d-none">
                <h4>Akun</h4>
                <ul>
                    <li class="link" :class="route_active == 'personalinfo' ? 'active' :''"  @click="route('personalinfo')">
                        <router-link class="btn-link" to="/personal-info">
                            <img src="/img/icon_profile.svg" alt=""> profil
                        </router-link>
                    </li>
                    <li class="link hotpoint" :class="route_active == 'hotpoint' ? 'active' :''" @click="route('hotpoint')">
                        <router-link class="btn-link" to="/hotpoint">
                            <img src="/img/ic_hotpoint.svg" alt=""> hot point
                        </router-link>
                        <span>{{profile.point | PointFormat}} Point</span>
                    </li>
                    <li class="link" :class="route_active == 'invite' ? 'active' :''" @click="route('invite')">
                        <router-link class="btn-link" to="/invite-friends">
                            <img src="/img/ic_invite.svg" alt=""> undang teman
                        </router-link>
                    </li>
                    <li class="link" :class="route_active == 'list-transaction' ? 'active' :''" @click="route('list-transaction')">
                        <router-link class="btn-link" to="/transactions/list-transaction">
                            <img src="/img/icon_transaction.svg" alt=""> daftar transaksi
                        </router-link>
                    </li>
                    <li class="link" :class="route_active == 'personalinfoaddress' ? 'active' :''" @click="route('personalinfoaddress')">
                        <router-link class="btn-link" to="/personal-info-address">
                            <img src="/img/icon_address.svg" alt=""> daftar alamat
                        </router-link>
                    </li>
                    <li class="link" :class="route_active == 'kuponrejeki' ? 'active' :''" @click="route('kuponrejeki')">
                        <router-link class="btn-link" to="/kupon-rejeki">
                            <img src="/img/badge_rejeki_nomplok.svg" alt=""> rejeki nomplok
                        </router-link>
                    </li>
                    <li class="link" :class="route_active == 'password' ? 'active' :''" @click="route('password')">
                        <router-link class="btn-link" to="/personal-info-edit/password">
                            <img src="/img/icon_security.svg" alt="">keamanan
                        </router-link>
                    </li>
                    <li class="pending badge link" :class="route_active == 'pending-transaction' ? 'active' :''" @click="route('pending-transaction')">
                        <router-link class="btn-link" to="/transactions/pending-transaction">
                            <img class="icon_pending" src="/img/icon_pending.svg" alt="">
                            menunggu<br>pembayaran
                            <div class="total-pending" v-if="count > 0">{{count}}</div>
                        </router-link>
                    </li>
                    <li class="logout link" @click="logout">
                        <a class="btn-link" @click="logout">
                            <img src="/img/ic_logout.svg" alt="">
                            Keluar
                        </a>
                    </li>
                </ul>
                <button class="btn-close" id="close_side_menu"></button>
            </div>
            <div class="nav-search d-none">
                <div class="container-search mobile">
                    <form class="relative" v-on:submit.prevent="searchProduct()" autocomplete="off">
                        <input name="search" placeholder="Mau barang apa? Cari disini ..." class="hotdeal-searching search"
                                v-model="searchValue" type="search" autocomplete="off"
                                v-on:click="searchRecommendation"
                                @keyup="searchProduct()"
                                v-on:focusout="focusout"
                                v-on:keyup.enter = "search"
                                :search="searchValue"
                        />
                        <img class="ic_search" src="/img/assets_hotdeal_search.svg" alt="" @click="search()">
                        <div class="autocomplete pop-over">
                            <div class="content-searching">
                                <div class="autocomplete-search">
                                    <div v-if="search_not_found">
                                        <i class="mright-5" style="font-weight:600;">{{searchValue}}</i> tidak ditemukan. Gunakan kata kunci yang lain.
                                    </div>
                                    <ul class="mbottom-15" v-if="recommended_keywords">
                                        <li v-for="(item, index) in recommended_keywords" :key="index">
                                            <router-link style="padding:5px 0;" class="search-popular btn-link" :to="'/product?search='+item">
                                            <img class="mright-10" src="/img/ic_search.svg" alt="">{{ item }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="last-search" v-if="Object.keys(history_search).length > 0">
                                    <div class="title-search">
                                        <h6>terakhir dicari</h6>
                                        <div class="btn-link pointer" @click="deleteHistory('all', index , 'all')">hapus semua</div>
                                    </div>
                                    <ul class="mbottom-15">
                                        <li v-for="(item, index) in history_search" :key="index">
                                            <img src="/img/ic_trash_grey.svg" @click="deleteHistory(item.keyword , index , 'keyword')" alt="">
                                            <router-link style="padding: 5px 10px;" class="search-popular btn-link" :to="'/product?search='+item.keyword">{{ item.keyword }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="popular-search" v-if="Object.keys(search_recommendation).length > 0">
                                    <div class="title-search">
                                        <h6>pencarian populer</h6>
                                    </div>
                                    <ul>
                                        <li style="padding:10px 5px;" v-for="(item, index) in search_recommendation.popular_products" :key="index">
                                            <router-link class="search-popular btn-link" v-if="item.products !== null" :to="'/product-detail/'+item.products.slug">
                                                <img :src="item.image.link" alt="">
                                                <div>
                                                    <div class="elipsis-1">{{ item.products.name }}</div><div class="description elipsis-2" v-html="item.products.description"></div>
                                                </div>
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="suggestion">
                        <p>Pencarian Pilihan :</p>
                        <ul class="d-flex" v-if="Object.keys(category_popular).length > 0">
                            <li v-for="(item, index) in category_popular" :key="index">
                                <router-link :to="'/product?search='+ item.category">{{ item.category }}</router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <router-view @updatecart="update_cart($event)" @updatePicture="profile.image = $event.image" @updateCarts="update_cart($event)"></router-view>
            <modal-loading></modal-loading>
        </div>
        <div class="floating-btn-rejeki">
            <div @click="hideBtnRejeki" id="hide_button_rejeki" class="btn-slide rejeki"></div>
            <a :href="'https://hotdeal.id/rejeki-nomplok'">
                <div class="button-img">
                    <img src="/img/floating_rejeki.svg">
                </div>
            </a>
        </div>
        <div class="floating-btn-chat">
            <div @click="hideBtnChat" id="hide_button_chat" class="btn-slide chat"></div>
            <a target="blank" :href="'https://api.whatsapp.com/send/?phone=628997590101&text=Hai,%20MinDeal!%20mau%20tanya%20dong~'">
                <div class="button-img">
                    <img src="/img/mindeal.png">
                </div>
            </a>
        </div>
        <div class="container-cookies mtop-5" v-if="!accepted_cookie">
            We use cookies to improve your experience. Happy shopping!
            <button class="mtop-5" v-on:click="accept_cookie">Accept</button>
        </div>
        <div :class="is_showhidemenu ? 'bottom-menu hide-bottom-menu'
                :'bottom-menu'">
            <ul>
                <li class="active home">
                    <router-link to="/">
                        <img class="bm_home" src="/img/bm_home.svg" alt="" width="25">
                        <div class="ellips"></div>
                    </router-link>
                </li>
                <li class="cart">
                    <router-link to="/cart">
                        <div class="relative">
                            <img class="bm_cart" src="/img/bm_cart.svg" alt="" width="25">
                            <badge :num="materials.countCart"></badge>
                        </div>
                        <div class="ellips"></div>
                    </router-link>
                </li>
                <li class="transaction">
                    <router-link to="/transactions/list-transaction">
                        <div class="relative">
                            <img class="bm_transaction" src="/img/bm_transaction.svg" alt="" width="20">
                            <!-- <badge :num="materials.countCart"></badge> -->
                        </div>
                        <div class="ellips"></div>
                    </router-link>
                </li>
                <li class="wishlist">
                    <router-link to="/wishlist">
                        <div class="relative">
                            <img class="bm_wishlist" src="/img/bm_wishlist.svg" alt="" width="25">
                        </div>
                        <div class="ellips"></div>
                    </router-link>
                </li>
                <li class="hotpoint">
                    <router-link to="/hotpoint">
                        <img class="bm_hotpoint" src="/img/bm_hotpoint.svg" alt="" width="38">
                        <!-- <div class="ellips"></div> -->
                    </router-link>
                </li>
            </ul>
        </div>
        <!-- <button id="scrollToTop"><img src="/img/arrow_scroll_top.svg" alt=""></button> -->
        
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
                                    <h6 class="about">Hotdeal adalah video commerce pertama di Indonesia yang menawarkan berbagai produk eksklusif dan berkualitas dengan harga bersaing. Dapatkan pengalaman belanja menyenangkan hanya di hotdeal.id.
                                    </h6>
                                </div>
                                <div class="row-100 mbottom-20" >
                                    <h6 class="about">Untuk informasi lebih lengkap hubungi kami di
                                    <strong>info@hotdeal.id</strong></h6>
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
                </div>
                <div class="partner-list">
                    <h5>Bank Partner : </h5>
                    <ul>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Mandiri.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+BNI.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+BRI.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+BCA.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Sahabat+Sampoerna.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Permata.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Link+Aja.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Dana.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+OVO.webp"></li>
                    </ul>
                    <h5>Logistic Partner : </h5>
                    <ul>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+JNE.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Lion.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Tiki.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Si+Cepat.webp"></li>
                        <!-- <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Indah.webp"></li> -->
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Shipper.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+SAP.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+JNT.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Gojek.webp"></li>
                        <li><img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/compress+webp/Logo+Grab.webp"></li>
                    </ul>
                </div>
            </div>
        </footer>
        <profile-phone :redirect="'personal-info'" :verified_value="''" :labels="'Nomor Handphone'" :type="'phone'"></profile-phone>

        <div style="width:100%; height:100%; position : fixed; top:0; left:0; z-index:100; display:none;" class="overlay-select"></div>
        <input type="text" style="display:none" id="text-data-for-copy" ref="textforcopy">
    </div>

</template>

<script>
    import Vue from 'vue';
    import User from '../../apis/User'
    import apiCustomer from '../../apis/Customer'
    import Notification from '../../apis/Notification'
    import HeaderProfile from '../../components/desktop/header/Profile'
    import HeaderInbox from '../../components/desktop/header/Inbox'
    import HeaderNofitication from '../../components/desktop/header/Notification'
    import Badge from './component/Badge.vue'
    import Message from '../../utils/Message'
    import Customer from "../../apis/Customer";
    import ModalMessage from './modal/ModalMessage.vue'
    import ModalSearchingMobile from './modal/ModalSearchingMobile.vue'
    import CustomerAPi from "../../apis/Customer";
    import VueAnalytics from 'vue-analytics';
    import VueAnalytics2 from 'vue-analytics';
    import Modal from './Modal.vue';
    import ModalLoading from './modal/ModalLoading.vue';
    import ProfilePhone from './modal/ProfilePhone.vue';
    import ModalAlert from './modal/ModalAlert.vue';

    Vue.use(VueAnalytics, {
        id: 'G-40J5HFEYRC',
    })
    Vue.use(VueAnalytics2, {
        id: 'UA-225458431-1',
    })
    export default {
        data(){
            return{
                footer : true,
                history_search : {},
                policylist : {},
                auth_active:'',
                ulcrumbs :[],
                searchValue : '',
                popup_login_banner : '',
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
                materials : {
                    countCart : 0,
                    notification : 0,
                    message : 0,
                    promotion : 0,
                },
                countCart : 0,
                loading_profile : true,
                is_loading : true,
                strengths: {},
                about_us: '',
                search_recommendation : {},
                recommended_keywords : null,
                product_popular : {},
                category_popular : {},
                newsletter_email: '',
                focus:false,
                search_not_found : false,
                observer:null,
                intersected: false,
                active_class : '',
                passwordFieldType:"password",
                passwordFieldTypenew:"password",
                is_searchable: false,
                is_backable: false,
                is_showhidemenu: false,
                route_active : '',
                slide_position : {current : 0},
            }
        },
        props : ['count'],
        components: {
            'header-profile' : HeaderProfile,
            'header-inbox' : HeaderInbox,
            'header-notification' : HeaderNofitication,
            'notification' : Notification,
            Badge,
            ModalMessage,
            Message,
            Modal,
            ModalLoading,
            ProfilePhone,
            ModalSearchingMobile,
            ModalAlert
        },
        watch: {
            $route(to, from) {
                this.crumbs()
                this.is_searchable = this.isSearchable(this.$route.name);
                this.is_backable = this.isBackable(this.$route.name);
                this.is_showhidemenu = this.isShowhidemenu(this.$route.name);
                this.searchValue = this.$route.query.search;
            }
        },
        created: function () {
            this.footer =  this.$route.name == 'login' ? false: true;

            this.searchValue = this.$route.query.search === 'undefined' ? '' : this.$route.query.search;
            
            if(User.is_login()){
                this.is_login = true;
                this.get_profile();
                this.notification();
                this.menubar_materials();
                this.search_history();
            }
            this.categoryPopular();
            this.crumbs();
            if(this.$route.query.utm_id){
                Vue.$cookies.set('utm_id', this.$route.query.utm_id, '30d')
            }
            if(this.$route.query.utm_source){
                Vue.$cookies.set('utm_source', this.$route.query.utm_source, '30d')
            }
            if(this.$route.query.utm_campaign){
                Vue.$cookies.set('utm_campaign', this.$route.query.utm_campaign, '30d')
            }
            if(this.$route.query.utm_term){
                Vue.$cookies.set('utm_term', this.$route.query.utm_term, '30d')
            }
            if(this.$route.query.utm_medium){
                Vue.$cookies.set('utm_medium', this.$route.query.utm_medium, '30d')
            }
            // Vue.$cookies.set('has_login', false , '6h')
        },
        mounted(){
            this.is_showhidemenu =  this.isShowhidemenu(this.$route.name)
            this.get_about_us();
            this.get_strengths();
            this.get_policy();
            this.get_popup_banner();
            if(!User.checkLoginCookie('LoginModalCookie')){
                this.openForm('login')
            }
            if(User.checkDauCookie('has_login')){
                this.add_dau()
            }
            this.route_active = this.$route.name;
        },
        methods: {
            close_cookies(){
               $(".container-cookie").fadeOut();
            },
            hideBtnChat(){
                $("#hide_button_chat").fadeIn(function() {
                    $(".floating-btn-chat").toggleClass('transform-css');
                })
            },
            hideBtnRejeki(){
                $("#hide_button_rejeki").fadeIn(function() {
                    $(".floating-btn-rejeki").toggleClass('transform-css');
                })
            },
            logout(){
                localStorage.removeItem('auth');
                User.logout().then(()=>{
                    localStorage.removeItem('auth');
                    this.$router.push({name:'login'});
                })
            },
            route(route){
                this.route_active = route
            },
            menubar_materials(){
                apiCustomer.menubar_materials().then( response => {
                    this.materials = response.data.data;
                });
            },
            get_popup_banner(){
                apiCustomer.get_login_banner().then(response => {
                    if(response.data.data != null){
                        this.popup_login_banner = response.data.data.image;
                    }
                });
            },
            openForm(form){
                // if(navigator.userAgent.includes("Instagram")){
                //     return window.open("https://hotdeal.id/login" , '_blank')
                //     //window.location.href = "https://hotdeal.id";
                // }
                this.auth_active = form;
                $("#myOverlay").fadeIn();
                $(".pop-over").removeClass('active')
                $("body").removeClass('overflow-hidden')
                document.querySelector('body').style.overflow = "hidden";
            },
            closeForm(){
                this.error = {};
                $("#myOverlay").fadeOut();
                document.querySelector('body').style.overflow = "auto";
            },
            register(){
                this.error = {};
                User.register(this.form_register).then(response => {
                    if (response.data.code === 200){
                        this.$router.push({name:'login'});
                    }else{
                        this.error = response.data.message;
                    }
                })
            },
            productPopular(){
                apiCustomer.popularProduct().then(response => {
                   this.product_popular = response.data
                })
            },
            categoryPopular(){
                apiCustomer.categoryPopular().then(response => {
                   this.category_popular = response.data
                })
            },
            search_history(){
                apiCustomer.searchHistory().then( response => {
                    this.history_search = response.data.data.options;
                });
            },
            deleteHistory(keyword, index , type){
                apiCustomer.deleteHistory({keyword:keyword , type : type}).then(response => {
                   if(response.data.code == 200){
                       if(type == 'all'){
                           return this.history_search = {}
                       }
                       this.history_search.splice(index , 1);
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
                        this.profile = response.data.data.data
                        User.profile().then(response =>{
                            // if(response.data.is_email_verified === false){
                            //     localStorage.setItem('verify-email',response.data.email);
                            //     return this.$router.push({name:'verified'})
                            // }

                            if(response.data.is_phone_verified === false){
                                $("#sending_otp").fadeIn(function () {
                                    $("body").addClass('overflow-hidden');
                                }); 
                            }
                            this.is_login = true;
                            if(response.data.is_vendor === true) {
                                this.$router.push('/vendor/dashboard')
                            }
                            this.closeForm();
                        })
                    }else{
                        this.modal_message = response.data.errors.email[0];
                        Message.alert(response.data.errors.email[0],'Informasi');
                        this.error = response.data.errors;
                    }
                    this.loading_profile = false
                }).catch((errors) => {
                    this.error = errors.response.data.errors;
                });
            },
            newsletter() {
                this.error = {};
                User.newsletter({email: this.newsletter_email}).then(response => {
                    if (response.data.code === 200){
                        this.newsletter_email = '';
                        Message.alert('Kamu sudah berlangganan email di hotdeal, nantikan promo-promo dari kami', 'Informasi', 1500);
                    }else{
                        Message.alert(response.data.message, 'Informasi', 1000);
                    }
                }).catch((errors) => {
                    this.error = errors.response.data.errors;
                });
            },
            AuthProvider(provider){
               // this.socialLogin(provider);
                var self = this;
                this.$auth.authenticate(provider)
                    .then(response => {
                        if(provider == 'google'){
                            self.socialLogin(provider,response);
                        }else{
                            localStorage.setItem('auth','true');
                            location.reload();
                        }

                    }).catch(error => {
                   // alert("Terjadi kesalahan");
                })
            },
            socialLogin(provider, res){
                User.social_login(provider,res)
                    .then(response => {
                        localStorage.setItem('auth','true');
                        location.reload();
                    }).catch(error => {
                    //alert("Terjadi kesalahan");
                })
            },

            add_dau(){
                User.addDau().then(response => {
                    if(response.code === 200){
                    }
                })
            },
            get_profile(){
              User.profile().then(response => {
                  if(response.status === 401){
                       localStorage.removeItem('auth');
                  }
                  this.profile = response.data;
                  
                  if(this.profile.is_vendor === true){
                        return this.$router.push({name:'VendorDashboard'})
                  }
                  
                    // if(this.profile.is_email_verified === false){
                    //     localStorage.setItem('verify-email',response.data.email);
                    //     this.$router.push({name:'verified'})
                    // }
                    if(response.data.is_phone_verified === false){
                        if(!localStorage.getItem('verify-phone')){
                            $("#sending_otp").fadeIn(function () {
                                $("body").addClass('overflow-hidden');
                                localStorage.setItem('verify-phone' , true);
                            }); 
                        }
                        
                    }
                  this.loading_profile = false;
              })
            },
            get_strengths() {
                apiCustomer.strengths().then(response => {
                    this.strengths = response.data.data;
                    this.is_loading = false;
                    this.intersected = true;
                })
            },
            get_policy(){
                Customer.get_privacy().then( response => {
                    this.policylist = response.data.data;
                });
            },
            reloadFunction(){

                window.location.search();
            },
            get_about_us() {
                apiCustomer.aboutUs().then(response => {
                    this.about_us = response.data.data.message
                    this.intersected = true;
                    //this.observer.disconnect();
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
            update_cart(event){
                this.materials.countCart = event;
            },
            accept_cookie(){
                localStorage.setItem('accepted_cookie','true');
                this.accepted_cookie = true;
            },
            addcountcart(count){
                alert(count);
            },
            crumbs(){
                this.ulcrumbs = this.$route.meta.breadcrumb;
            },
            searchRecommendation() {
                if(!this.focus){
                    apiCustomer.searchRecommendations({search : this.searchValue}).then(response => {
                        console.log(response);
                        if(response.status === 200){
                            this.search_recommendation = response.data.data;
                            this.focus=true;
                        }
                    })
                }
            },
            searchProduct(){
                this.search_not_found = false;
                if (this.timer) {
                    clearTimeout(this.timer);
                    this.timer = null;
                }
                this.timer = setTimeout(() => {
                    apiCustomer.searchProducts({search : this.searchValue}).then(response => {
                        this.recommended_keywords = response.data.data.recommended_keywords;
                        if(response.data.data.recommended_keywords.length === 0){
                            this.search_not_found = true
                        }
                    })
                }, 500);
            },
            focusout(){
                this.focus = false;
            },
            setSearchBar(item) {
                this.searchValue = item
            },
            gotoProduct(slug) {
                location.href = '/product-detail/' + slug
            },
            search() {
                //router.push({ path: '/home', replace: true })
                // this.$router.push({path:'product', query :{ search : this.searchValue}}).catch(()=>{});
                let key = this.searchValue == 'undefined' ? '' : this.searchValue;
                location.href = '/product?search=' + key;
                $(".searching-desktop").blur();
                $(".hotdeal-searching").blur();
                $('.overlay-primary').click();
            },
            switchVisibility(){
                this.passwordFieldType =  this.passwordFieldType === "password" ? "text" : "password";
            },
            switchVisibilitynew(){
                this.passwordFieldTypenew =  this.passwordFieldTypenew === "password" ? "text" : "password";
            },
            isSearchable(routername){
                let arr_routername = ["notification", "promolist", "personalinfo", "editpersonalinfo", "hotpoint", "list-transaction", "personalinfoaddress", "pending-transaction", "kuponrejeki", "password", "historyrefferal", "bundling", "rejeki-nomplok-about","rejeki-nomplok-winner"]
                    if (arr_routername.includes(routername)){
                        return true;
                    }
                    return false;
            },
            isBackable(routername){
                let arr_routername = ["notification", "promolist", "personalinfo", "hotpoint", "list-transaction", "personalinfoaddress", "pending-transaction", "kuponrejeki", "password","historyrefferal","rejeki-nomplok-about","rejeki-nomplok-product","rejeki-nomplok-winner"]
                    if (arr_routername.includes(routername)){
                        return true;
                    }
                    return false;
            },
            isShowhidemenu(routername){
                let arr_routername = ["cart", "productdetail", "checkout", "promolist", "product", 'flashsale', 'results-category','productbundling','product','rejeki-nomplok-prodduct',"rejeki-nomplok-winner"]
                    if (arr_routername.includes(routername)){
                        return true;
                    }
                    return false;
            },
            emailRegister(){
                this.$router.push({path : '/register'})
            }
        },
        computed: {
            // countCart: function(){
            //     return 5;
            // }
        },
    }
</script>

<style>
    button{
        cursor:pointer;
    }
    .cursor-pointer{
        cursor : pointer;
    }
</style>
