<template>
    <div class="col-20p">
        <!-- <div class="mbottom-10" v-if="is_loading">
            <div class="ph-row">
                <div class="ph-col-12 ph-h250 bg-placeholder mright-10"></div>
            </div>
        </div> -->
        <div class="leftside-menu">
            <h4>Wishlist</h4>
            <ul class="leftside-submenu">
                <li style="font-size: 0.9rem;" class="leftside-content" @click="checkAll">Pilih Semua</li>
                <li class="leftside-content">
                    <a href="javascript:void(0)" @click="deleteAll">Hapus Semua</a>
                </li>
                <!-- <li class="leftside-content">Beli Semua</li> -->
                <li class="leftside-content">
                    <a href="javascript:void(0)" @click="deleteSelectedItem">Hapus Yang Diseleksi</a>
                </li>
                <!-- <li class="leftside-content">Beli yang Diseleksi</li> -->
            </ul>
        </div>
        <modal-delete-wishlist :status="status" @dodelete="dodelete($event)"></modal-delete-wishlist>
    </div>
</template>


<script>
    import User from '../../../../apis/User'
    import CustomerAPi from "../../../../apis/Customer";
    import ModalDeleteWishlist from '../../modal/ModalDeleteWishlist.vue';
    import Message from '../../../../utils/Message'
    export default {
        components: { ModalDeleteWishlist },
        name: "ProfileMenu.vue",
        props :['items'],
        data(){
            return {
              incheck_All : false,
              is_loading : true,
              status : ''
            }
        },
        methods: {
            logout(){
                User.logout();
            },
            deleteAll(){
                this.status = 'all'
                $("#confirmation_delete_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            deleteSelectedItem(){
                this.status = 'selected_items_'
                let item = this.items
                if(item.length < 1){
                    return Message.alert('pilih minimal 1 produk yang mau dihapus' , 'Informasi' , 1500)
                }
                $("#confirmation_delete_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
          checkAll(){
              this.$emit('checkedAll' ,'pilih')
          },
          dodelete(event){
              this.$emit('action' , event);
              $('.close-modal').click();
          }
        }
    }
</script>

<style scoped>

</style>
