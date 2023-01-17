<template>
     <div>
        <div v-if="Object.keys(items).length > 0">
            <div class="row-100 mbottom-20" v-for="(item, index) in items" :key="index">
                <div class="col-95p">
                    <div class="card-img">
                        <div class="content-img">
                            <img :src="item.image.link" :alt="item.name">
                        </div>
                    </div>
                    <div class="card-wishlist">
                        <div class="col1-wishlist">
                            <router-link :to="'/product-detail/'+item.slug"><h5>{{item.name}}</h5></router-link>
                            <h6><b>In Stock</b> | Berat : <b>{{item.weight}} gram</b></h6>
                            <div class="tag mtop-10">
                                <div class="discount mright-5" v-if="item.discount.value != 0">
                                    {{parseInt(item.discount.value)}}%
                                </div>
                                <div class="free-shipping"></div>
                                <div class="voucher-available"></div>
                                <div class="badge-rejeki-nomplok " v-if="item.in_rejeki_nomplok == true"></div>
                            </div>
                        </div>
                        <div class="col2-wishlist">
                            <div class="row-100">
                                <div class="harga-qty">
                                    <div class="col-title">
                                        <h5>Harga</h5>
                                    </div>
                                    <div class="col-field">
                                        <h4>{{item.detail.price}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col3-wishlist">
                            <wish-list-button-buy-now :product="item"></wish-list-button-buy-now>
                        </div>
                    </div>
                </div>
                <div class="col-5p">
                    <div class="btn-col">
                        <button class="check">
                            <input type="checkbox" :id="index" @change="mark_checkout()" v-model="checked_item" :value="item.id"  style="z-index: 100000;" />
                            <label :for="'check'+index"></label>
                        </button>
                        <delete-button @delete_wish="removeItem($event)" :index="index" :product="item" :action="'wishlist'"></delete-button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <blank-page :message="'Wishlist Anda Kosong'" :image="'img/animation_empty_wishlist.svg'"></blank-page>
        </div>
     </div>
</template>
<script>
    import WishListButtonBuyNow from '../../component/wishlist/WishListButtonBuyNow.vue'
    import DeleteButton from '../WishListButtonDelete.vue'
    import BlankPage from '../../BlankPage.vue'
    export default {
        components: { WishListButtonBuyNow, DeleteButton, BlankPage },
        name: "LargeCardProduct",
        data(){
            return {
                is_loading : true,
            }
        },
        props :['items','checked_item'],
        mounted (){

        },
        methods : {
            video_data(item){
                this.$emit('video_data' , item)
            },
            mark_checkout(){
                 this.$emit('selected' , this.checked_item)
            },
            removeItem(item){
                this.items.splice(item, 1);
            }
        }
    }
</script>

<style scoped>

</style>
