<template>
  <div
    :class="[
      this.$store.getters.hasdarkflag === true ? 'black-main' : 'white-main',
    ]"
  >
    <div class="container-login">
      <router-view />
    </div>
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
}
.white-main {
  background-color: #fff;
}
</style>