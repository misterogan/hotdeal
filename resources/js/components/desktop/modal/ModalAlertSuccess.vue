<template>
     <div id="success_alert_modal" class="modal">
        <div class="modal-dialog w-350 alert">
            <div class="modal-body bg-orange">
                <div class="absolute bg-ilustration"></div>
                <div class="content-modal">
                    <div class="alert animation">
                        <img src="" alt="" id="image">
                        <h5 class="alert-text" id="message"></h5>
                        <input type="hidden" class="get-link-val" id="link">
                        <button class="btn-primary" id="keyword" @click="route_to"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CloseModal from '../../desktop/component/CloseModal.vue'

    export default {
        name : 'ModalAlert',
        data(){
            return {
                data_link: ''
            }
        },
        mounted(){
            this.data_link = $('#link').val()
        },
        methods: {
            route_to(){
                this.data_link = $('#link').val()
                this.$router.push({name:this.data_link});
            },
        },
        components:{
            CloseModal
        }
    }
</script>