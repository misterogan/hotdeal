<template>
    <div>
        <div class="mbottom-10" v-if="is_loading">
            <banner-skeleton :count="1"></banner-skeleton>
        </div>
        <div class="row about-us-top" v-if="!is_loading">
            <div class="col-65p">
                <img class="pinterest-img gallery__img" :src="about_us_img" alt="">
            </div>
            <div class="col-35p">
                <h5>Hotdeal Indonesia</h5>
                <div class="about">
                    <div class="text" v-html="about_us"></div>
                </div>
                <button @click="show_modal_share" class="btn-share">
                    <img src="/img/ic_share.svg" alt="">
                </button>
            </div>
        </div>

        <div class="galeri">
            <div class="gallery-item--1">
                <img class="gallery__img" src="https://scdn.ctree.id/f/211105/1636094166566_Dummy 2@2x.webp" alt="">
            </div>
            <div class="gallery-item--2">
                <img class="gallery__img" src="https://scdn.ctree.id/f/211105/1636094256164_Dummy 3@2x.webp" alt="">
            </div>
            <div class="gallery-item--3">
                <img class="gallery__img" src="https://scdn.ctree.id/f/211105/1636094288099_Dummy 4@2x.webp" alt="">
            </div>
            <div class="gallery-item--4">
                <img class="gallery__img" src="https://scdn.ctree.id/f/211105/1636094299590_Dummy 5@2x.webp" alt="">
            </div>
            <div class="gallery-item--5">
                <img class="gallery__img" src="https://scdn.ctree.id/f/211105/1636094367292_Dummy 6@2x.webp" alt="">
            </div>
        </div>

        <modal-share :url="'https://hotdeal.id/about-us'"></modal-share> 
    </div>
</template>

<script>
    import Customer from '../../apis/Customer'
    import ModalShare from './modal/ModalShare.vue'
    import BannerSkeleton from '../skeleton/BannerSkeleton.vue'

    export default {
        data() {
            return {
                about_us: '',
                about_us_img: '',
                is_loading: true
            }
        },
        name: "AboutUs.vue",
        mounted() {
            this.get_about_us()
        },
        methods: {
            get_about_us() {
                Customer.aboutUs().then(response => {
                    this.about_us = response.data.data.message
                    this.about_us_img = response.data.data.image
                    this.is_loading = false
                })
            },
            show_modal_share(){
                $("#modal_share").fadeIn(function() {
                    $("body").addClass('overflow-hidden');
                })
            }
        },
        components: {
            ModalShare, BannerSkeleton
        }
    }
</script>

<style scoped>

</style>
