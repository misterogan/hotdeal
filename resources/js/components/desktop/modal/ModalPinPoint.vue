<template>
    <div id="pinpoint_address_modal" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <!-- <span class="close-modal" @click="close_modal"></span> -->
                <div class="content-modal">
                    <address-pin-point></address-pin-point>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import AddressPinPoint from '../component/profile/AddressPinPoint.vue'

    export default {
        
        name: "ModalPinPoint.vue",
        data() {
            return {
            }
        },
        methods: { 
            close_modal(){
                this.$emit('change',{});
            }
        },
        mounted(){
            
        },
        components:{
            AddressPinPoint
        }
    }
</script>

<style>

</style>
