<template>
    <div class="news">
        <div class="mobile">
            <div class="row mbottom-30">
                <div class="banner">
                    <img :src="image" alt="">
                </div>
            </div>
            <div class="row mbottom-30">
                <div class="body-text">
                    <div class="title">{{title}}</div>
                    <div class="desc" v-html="description"></div>
                </div>
            </div>
            <div class="row btn justify-right">
                <button @click="show_modal_share" class="mbottom-30" :data-link='"/news-detail/" + slug'><img src="/img/ic_share.svg" alt=""></button>
            </div>
        </div>
        <modal-share :url=url></modal-share>
    </div>
</template>

<script>
    import Customer from '../../apis/Customer'
    import ModalShare from '../desktop/modal/ModalShare.vue'    

    export default {
        data(){
            return {
                title: '',
                image: '',
                description: '',
                url:'https://hotdeal.id/news-detail/'
            }
        },
        name: "News.vue",
        mounted(){
            this.get_page_by_slug();
        },
        methods:{
            get_page_by_slug(){
                Customer.get_page_by_slug({ slug : this.$route.params.pathMatch}).then(response => {
                    this.title = response.data.data.title;
                    this.image = response.data.data.image;
                    this.description = response.data.data.description;
                    this.slug = response.data.data.slug;
                    this.url = this.url + this.slug;
                })
            },
            show_modal_share(){
                $("#modal_share").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            }
        },
        components:{
            ModalShare
        }
    }
</script>

<style scoped>

</style>
