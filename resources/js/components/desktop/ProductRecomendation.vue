<template>
    <div class="col box-recommended">
        <div id="ob_recommendation" class="mbottom-30" v-if="is_loading">
            <div class="ph-desktop">
                <div class="ph-row justify-between">
                    <div class="ph-col-4">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                    <div class="ph-col-4">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10 mright-10"></div>
                        </div>
                    </div>
                    <div class="ph-col-4">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ph-mobile">
                <div class="row justify-between">
                    <div class="ph-col-4">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded-sm"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                    <div class="ph-col-4">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded-sm"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10 mright-10"></div>
                        </div>
                    </div>
                    <div class="ph-col-4">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded-sm"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10 mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!is_loading">
            <h3 class="title-section mbottom-15">Rekomendasi Untukmu</h3>
            <div class="similar-product" v-if="Object.keys(recomendation).length > 0">
                <small-card-product-without-stock :item="item" v-for="(item , index) in recomendation"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import SmallCardProductWithoutStock from './component/product/SmallCardProductWithoutStock.vue'
    import Rating from './component/Rating.vue'
    export default {
        data(){
            return {
                is_loading : true,
                recomendation : {},
                observer: null,
                intersected: false,
                slug: ''
            }
        },
        name: "ProductRecomendation.vue",
        mounted(){
            this.recomendationProduct()
        },
        methods: {
            recomendationProduct(){
                const element = document.querySelector("#ob_recommendation");
                this.observer = new IntersectionObserver(entries => {
                    const target = entries[0];
                    if (target.isIntersecting) {
                        const fd = new FormData();
                        this.slug = this.$route.params.pathMatch
                        fd.append('slug' , this.slug)
                        apiCustomer.productRecomedation(fd).then( response => {
                            this.recomendation = response.data.data.product_suggestion;
                            this.is_loading=false;
                            this.intersected = true;
                            this.observer.disconnect();
                        })
                    }
                });
                this.observer.observe(element);
            }
        },
        components:{
            Rating,
            SmallCardProductWithoutStock
        },
    }
</script>
