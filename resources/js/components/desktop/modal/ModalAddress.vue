<template>
    <div>
        <div id="change_address_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <address-form class="scroll-overflow h-450"></address-form>
                </div>
            </div>
        </div>
        </div>
        <address-pin-point-edit :item="item_edit" @action="response_after_action($event)" :data_index="data_index"></address-pin-point-edit>
    </div>
</template>

<script>
import Customer from '../../../apis/Customer'
import AddressForm from '../component/profile/AddressForm.vue'
import Message from '../../../utils/Message'
import AddressPinPointEdit from '../component/profile/AddressPinPointEdit.vue'
export default {
    name: "ModalAddress.vue",
    data() {
        return {
            addresses : {},
            data_index : null,
            item_edit : null
        }
    },
    methods: { 
        close_modal(){
            this.$emit('change',{});
        },
        get_address_by_id(){
            Customer.get_address_list().then(response => {
                this.addresses = response.data.data.address;
                this.total_address = this.addresses.length;
            })
        },
        setPrimaryAddress(itemid , index){
            Customer.setAsPrimary({itemid : itemid}).then(response => {
                if(response.data.code == 200){
                    for(const item of this.addresses){
                        item.is_primary_address = false;
                    }
                    this.addresses[index].is_primary_address = true
                    Message.alert("Berhasil mengubah alamat utama" , "Informasi" , 1000);
                }else{
                        Message.alert(response.data.message , "Informasi");
                }
            })
        },
        show_modal_edit(i , item){
            //alert(i)
            this.data_index = i;
            this.item_edit = item
            $("#pinpoint_address_edit_modal").fadeIn(function () {
                // $("#pinpoint_address_edit_modal").addClass('overflow-scroll');
                $("body").addClass('overflow-hidden');
            });
        },
        response_after_action(event){
                if(event.type == 'update'){
                     this.addresses[event.idx].address = event.data.address;
                     this.addresses[event.idx].label_name = event.data.label_name;
                    this.addresses[event.idx].zip_code = event.data.zip_code
                    this.addresses[event.idx] = event.data;
                }
            }
    },
    mounted(){
        this.get_address_by_id()  
    },
    components:{
        AddressForm,
        AddressPinPointEdit
    }
}
</script>

<style>

</style>