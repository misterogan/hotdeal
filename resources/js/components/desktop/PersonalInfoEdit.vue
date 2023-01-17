<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-40">
                <profile-menu></profile-menu>
                <profile-point-info></profile-point-info>
            </div>
           <router-view></router-view>   
        </div>
    </div>
</template>

<script>
    import ProfileMenu from '../desktop/component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'
    export default {
        name: "PersonalInfoEdit.vue",
        data(){
            return {
                page : this.$route.name,
                address : {}
            }
        },
        mounted (){
            
        },
        components : {
            ProfileMenu,
            ProfilePointInfo
        }
    }
</script>

<style scoped>

</style>