<template>
    <div class="col-55p">
        <div class="row col3-flex-cart checkout">
            <div class="col-20p">
                <div class="box-filter-purple">
                    <div class="content">
                        <h4>Pusat Bantuan</h4>
                        <ul>
                            <li v-for="(item , index) in policylist.menu" :key="index" v-bind:class="active_class == index ? 'active': ''" @click="active_class = index">{{item}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-55p"  v-for="(item , index) in policylist.privacy" :key="index" v-bind:class="active_class == index ? 'show' : 'hide'" >
                <div class="row-100">
                    <div class="box-100p">
                        <div class="col-12 accordion-tab" v-for="(item, index2) in policylist.privacy[index]" :key="index2">
                            <h2 class="mbottom-25 text-center">{{item.title}}</h2>
                            <!-- <h2>{{link}}</h2> -->
                            <div v-html="item.description"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-25p">
                <div class="box-pink">
                    <div class="content">
                        <h4>perlu bantuan lain</h4>
                        <h4>hubungi customer</h4>
                        <h4 class="mbottom-30">service kami</h4>
                        <h5>email</h5>
                        <h5 class="lowercase">info@hotdeal.id</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- <modal></modal> -->
    </div>
    
</template>

<script>
    import Customer from '../../apis/Customer'

    export default {
        name: "HelpCenter.vue",
        data(){
            return {
                policylist : {},
                is_loading : true,
                active_class : !this.$route.query.index ? 'Privacy_Policy' : this.$route.query.index
            }
        },
        mounted(){
            this.policyList()
        },
        watch:{
            $route (to, from){
                this.active_class  = this.$route.query.index
            }
        },
        methods: {
            policyList(){
                Customer.get_privacy().then( response => {
                    this.is_loading = false;
                    this.policylist = response.data.data;
                    // this.link = this.$route.query.index;
                });
            },
        },
    }
</script>

<style scoped>
    .hide{
        display: none !important;
    }
</style>