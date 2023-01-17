<template>
    <button @click="closeModal()">
        <img src="img/y.svg" alt="">
        <h6 >Tidak</h6>
    </button>
</template>

<script>
    export default {
        name: "Rating",
        props :['attribute'],
        methods : {
            closeModal(){
                $('#'+this.attribute).fadeOut();
                $('.close-modal').click();
            }
        }
    }
</script>