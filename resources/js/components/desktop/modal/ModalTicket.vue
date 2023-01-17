<template>
<div>
    <div id="modal_ticket_bundling" class="modal">
      <div class="modal-dialog w-350">
            <div class="modal-body">
                <h3 style="color:#44454F; padding-top:20px; text-align:center; font-weight: 600;" id="title"> Kupon Undian</h3>
                <span class="close-modal"></span>
                <div class="content-modal">
                    <div class="modal-body-content p-20" v-if="Object.keys(ticket).length > 0">
                        <ul class="cs-ul-ticket">
                            <li v-for="(item ,index) in ticket" :key="index"  class="cs-justify-between row">
                                <span>Kode Undian</span>
                                <span class="cs-text-right"><b>{{item.ticket_number}}</b></span>
                            </li>
                        </ul>
                    </div>
                    <div class="alert text-center">
                        <button class="btn-close" @click="modal_close">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>

export default {
    name: "ModalTicket.vue",
    props : ['ticket'],
    methods: { 
        
        show_input_otp(){
            $("#otp_success").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
            $("#input_otp_checkout").fadeOut(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        modal_close(){
            $("#modal_ticket_bundling").fadeOut(function () {
                $("#modal_ticket_bundling").addClass('overflow-scroll');
            });
        }
    },
}
</script>

<style>

</style>