<template>
     <div id="alert_modal" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body">
                <div class="content-modal">
                    <div class="alert">
                        <h5 class="alert-text text-center">{{message}}</h5>
                        <button class="btn-primary" @click="close_modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CloseModal from '../../desktop/component/CloseModal.vue'

    export default {
        props : ['message'],
        name : 'ModalAlert',
        methods: {
            close_modal(){
                $('.modal').fadeOut();
                $("body").addClass('overflow-scroll');
            },
        },
        components:{
            CloseModal
        }
    }
</script>