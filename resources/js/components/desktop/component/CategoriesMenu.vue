<template>
    <div class="category">  
       <ul class="hotdeal-product-category" v-if="loading">
         <div class="ph-desktop justify-between w-100">
            <icon-skeleton :count="17"></icon-skeleton>
         </div>
         <div class="ph-mobile justify-between w-100">
            <icon-skeleton :count="6"></icon-skeleton>
         </div>
       </ul>

      <ul class="hotdeal-product-category" v-if="!loading && Object.keys(categories).length > 0">
         <router-link class="category-name" v-for="(item , index) in categories" :key="index"  :to="'/product-category?category='+item.id">
            <li>
               <img class="icon_category" :src="item.icon" alt="">
               <p>{{item.category}}</p>
            </li>
         </router-link>
      </ul>
   </div>
</template>

<script>
import IconSkeleton from '../../skeleton/IconSkeleton.vue'
import apiCustomer from '../../../apis/Customer'
export default({

   name : "CategoriesMenu",
   data(){
      return {
         loading : true,
         categories : {}
      }
   },
   components : {
      IconSkeleton
   },
   mounted(){
      this.getCategoriesMenu() 
   }, 
   methods : {
      getCategoriesMenu(){
         
         apiCustomer.categoriesMenu().then( response => {
            if(response.data.code == 200){
               this.loading = false
               this.categories = response.data.data
            }
            
         })
      }

   }
})
</script>
