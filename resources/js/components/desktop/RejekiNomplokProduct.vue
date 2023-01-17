<template>
    <div class="row-hd hd-rejeki-nomplok">
        <div class="col-csp20-2">
            <rejeki-nomplok-nav></rejeki-nomplok-nav>
        </div>
        <div class="col-csm40-10 col-sm-12">
            <rejeki-nomplok-menus></rejeki-nomplok-menus>
            <div class="row-hd flex-column rejeki-catalogue">
                <rejeki-nomplok-list-product></rejeki-nomplok-list-product>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import RejekiNomplokListProduct from './RejekiNomplokListProduct.vue'
    import RejekiNomplokNav from '././component/rejekinomplok/RejekiNomplokNav.vue'
    import RejekiNomplokMenus from '././component/rejekinomplok/RejekiNomplokMenus.vue'

    export default {
        name: "RejekiNomplokProduct.vue",
        data(){
            return{
                route_active: '',
                text: ''
            }
        },
        methods: {
            route(route){
                this.route_active = route
            }
        },
        mounted(){
            this.route_active = this.$route.name;
        },
        components : {
            RejekiNomplokBanner,
            RejekiNomplokListProduct,
            RejekiNomplokNav,
            RejekiNomplokMenus
        }
    }
</script>