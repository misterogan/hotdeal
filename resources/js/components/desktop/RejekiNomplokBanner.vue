<template>
   <router-link to="/rejeki-nomplok">
        <div class="mbottom-10" v-if="is_loading">
            <div class="ph-row ph-h350 bg-placeholder mtop-10 rounded"></div>
        </div>
        <div id="ob_banner_lucky" class="banner-lucky">
            <div class="col-12">
                <div class="lucky-buyer" id="has-banner">
                    <div class="container-img has-banner" v-if="Object.keys(banner).length > 0 ">
                        <img :src="banner.banner" alt="">
                        <!-- <img v-if="!isMobile()" :src="banner.banner" alt="">
                        <img v-else :src="banner.banner_mobile" alt=""> -->
                    </div>
                    <!-- <button type="button" class="btn-light">Ikutan Sekarang</button> -->
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import CustomerAPi from "../../apis/Customer";
    export default {
        name: "RejekiNomplokBanner.vue",
        data(){
            return {
                banner : {},
                observer: null,
                intersected: false,
                is_loading: true
            }
        },
        mounted(){
            this.BannersRejeki()
        },
        methods: {
            BannersRejeki(){
              const element = document.querySelector("#ob_banner_lucky");
              this.observer = new IntersectionObserver(entries => {
                const image = entries[0];
                if (image.isIntersecting) {
                  apiCustomer.rejekiNomplokBanner().then( response => {
                    this.banner = response.data.data
                    this.intersected = true;
                    this.is_loading = false
                    this.observer.disconnect();
                  });
                }
              });
              this.observer.observe(element);
            },
            isMobile() {
                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            },
        }
    }
</script>

<style scoped>

</style>
