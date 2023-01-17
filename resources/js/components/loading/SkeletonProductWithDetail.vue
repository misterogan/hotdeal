<template>
  <div v-bind:class="'ph-col-'+classes" class="ph-h300">
      <div class="ph-row">
          <div class="ph-col-12 bg-placeholder ph-h200 mright-10"></div>
      </div>
      <div class="ph-row mtop-10">
          <div class="ph-col-10 bg-placeholder mright-15"></div>
      </div>
      <div class="ph-row mtop-10">
          <div class="ph-col-5 bg-placeholder mright-15"></div>
      </div>
      <div class="ph-row mtop-10">
          <div class="ph-col-12 bg-placeholder mright-15"></div>
      </div>
  </div>
</template>

<script lang="ts">
export default({
  name: "SkeletonProductWithDetail",
  props: ['classes'],
});
</script>
