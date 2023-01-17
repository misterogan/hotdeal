<template>
    <div>
        <list-skeleton v-if="is_loading" :count="3"></list-skeleton>
        <div class="row col3-flex-cart checkout"  v-if="!is_loading">
            <div class="col-20p">
                <div class="box-filter-purple">
                    <div class="content">
                        <h4>Pusat Bantuan</h4>
                        <ul v-if="Object.keys(faqlist.menu).length > 0">
                            <li v-for="(item , index) in faqlist.menu" :key="index" v-bind:class="active_class == index ? 'active': ''" @click="active_class = index">{{item}}</li>
                        </ul>
                    </div>
                </div> 
            </div>
            <!-- <div v-if="Object.keys(faqlist.menu).length > 0"> -->
                <div class="col-55p"  v-for="(item , index) in faqlist.menu" :key="index" v-bind:class="active_class == index ? 'show' : 'hide'" >
                    <div class="row-100">
                        <div class="box-100p">
                            <h2 class="mbottom-25 text-center">{{item}}</h2>
                            <div class="col-12 accordion-tab" v-for="(item, index2) in faqlist.faqs[index]" :key="index2">
                                <div class="accordion-item">
                                    <input :id="'tab-'+index+index2" type="checkbox">
                                    <label :for="'tab-'+index+index2">
                                        <h4>{{item.question}}</h4>
                                    </label>
                                    <div class="tab-content">
                                        <div class="w-95" v-html="item.answer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
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
    import LargeSkeleton from '../skeleton/LargeSkeleton.vue'
    import ListSkeleton from '../skeleton/ListSkeleton.vue'

    export default {
        name: "HelpCenter.vue",
        data(){
            return {
                faqlist : {},
                is_loading : true,
                active_class : 'Akun_Saya'
            }
        },
        mounted(){
            this.faqList()
        },
        methods: {
            faqList(){
                Customer.get_faqs().then( response => {
                    this.is_loading = false;
                    this.faqlist = response.data.data;
                });
            },
        },
        components: {
            LargeSkeleton, ListSkeleton
        }
    }
</script>

<style scoped>
    .hide{
        display: none !important;
    }
</style>