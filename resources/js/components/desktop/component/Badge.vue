<template>
    <div class="icon-badge" v-if="num > 0">
        <p>{{num}}</p>
    </div>
    <div class="icon-badge" v-else-if="num == '9+'">
        <p>{{num}}</p>
    </div>
</template>

<script>
    export default {
        name: "Badge",
        props :['num'],
        data(){
            return {
            }
        },
        mounted(){  
        }
    }
</script>