<template>
    <div id="modal-for-all-message" class="modal">
            <div class="modal-dialog w-350">
                <div class="modal-body">
                    <h3 style="color:#44454F; padding-top:20px; text-align:center; font-weight: 600;" id="title"></h3>
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <h5 id="message"></h5>
                        <div class="alert text-center">
                            <button class="btn-close" onclick="document.getElementsByClassName('close-modal')[0].click()">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "ModalMessage.vue",
        data() {
            return {
            }
        },
    }
</script>

<style>

</style>
