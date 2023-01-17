<template>
    <router-link to="/rejeki-nomplok">
         <div id="ob_banner_lucky" class="banner-lucky">
             <div class="col-12">
                 <div class="lucky-buyer" id="has-banner">
                     <div class="container-img has-banner">
                         <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/files/6360c35acc2a9-files1667285850.webp" alt="">
                     </div>
                 </div>
             </div>
         </div>
     </router-link>
 </template>
 
 <script>
     export default {
         name: "RejekiNomplokBannerDetail.vue",
         data(){
             return {
                 banner : {},
                 observer: null,
                 intersected: false,
             }
         },
         mounted(){
         },
         methods: {
             isMobile() {
                 if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                     return true
                 } else {
                     return false
                 }
             },
         }
     }
 </script>
 
 <style scoped>
 
 </style>
 