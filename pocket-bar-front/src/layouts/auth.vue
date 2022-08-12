<template>
  <div
    :class="[
      this.$store.getters.hasdarkflag === true ? 'black-main' : 'white-main',
    ]"
  >
    <router-view />
  </div>
</template>
 
<script>
export default {
  name: "auth", // you can enter any name (optional)
  components: {},
};
</script>
 
<style>
/* your style */
.black-main {
  background-color: #272727;
  min-height: 100vh;
}
.white-main {
  background-color: #fff;
  min-height: 100%;
}
</style>