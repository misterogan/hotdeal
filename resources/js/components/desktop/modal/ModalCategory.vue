
<template>
   <div id="category_modal" class="modal">
        <div class="modal-dialog" style="width:1000px">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <h4>Kategori</h4>
                    <div class="search mbottom-10">
                        <div class="row-100">
                            <div class="col-12">
                                <form v-on:submit.prevent="show_modals()">
                                    <input name="search" class="input-search" type="search" id="search" v-model="search" autocomplete="off" value="" placeholder="Search Category"/>
                                    <img class="ic_search" src="/img/ic_search.svg" alt="">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row-100">
                        <div class="col2">
                            <div class="kategori">
                                <ul v-if="Object.keys(parent_list).length > 0">
                                    <li v-for="(item , index) in parent_list" :key="index" @click="set_category(item.id ,item.category , item.is_parent )">
                                        <a tabindex="-1" href="#">{{item.category}}
                                            <span v-if="item.is_parent">
                                                <img src="/img/ic_category_arrow2.svg" alt="">
                                                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z"/>
                                                </svg> -->
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col2">
                            <div class="kategori">
                               <ul v-if="Object.keys(child_list_1).length > 0">
                                    <li style="padding-left: 12px;" v-for="(item1, index1) in child_list_1" :key="index1" @click="set_category(item1.id ,item1.category , item1.is_parent)">{{item1.category}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiVendor from '../../../apis/Vendor'
import apiMaster from '../../../apis/Master'
export default {
    name: "ModalCategory.vue",
    data() {
        return {
            parent : 0,
            parent_list : {},
            child_list_1 : {},
            child_list_2 : {},
            child_list_3 : {},
            search : ''
        }
    },
    mounted(){
        this.get_category()
    },
    methods: {
        set_category(id , name , is_parent){
            if(is_parent == 1){
                this.get_category(id);
                
            }else{
                this.$emit('setcategory' , {id:id , name:name})
                $('.close-modal').click();
            }
            
        },
        show_active_voucher(){
            $("#voucher_active").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
            $("#voucher_card_modal").fadeOut(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        show_modals(id=0){
            //setTimeout(function() {
                this.child_list_1 = {}
                apiMaster.Categories({parent_id : id , search : this.search}).then( response => {
                    
                    this.parent_list = response.data.data
                    $("#category_modal").fadeIn(function () {
                        $("body").addClass('overflow-hidden');
                    });
                });
           // }, 3000);
            
        },
        get_category(parent_id , index){

            apiVendor.CategoryByParent({parent_id : parent_id}).then( response => {
                this.child_list_1 = response.data.data
            });  
        },
        show_detail(){
            $("#promo_detail_modal").fadeIn(function () {
                    $("#promo_detail_modal").addClass('overflow-scroll');
                $("body").addClass('overflow-hidden');
            });
        }
    },
    mounted(){
        
    },
    components:{
    }
}
</script>

<style>

</style>
