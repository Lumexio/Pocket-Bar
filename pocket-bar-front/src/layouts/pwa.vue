<template>
  <v-app
    id="inspire"
    :class="[$store.getters.hasdarkflag === true ? 'black-main' : 'white-main']"
  >
    <navbar />
    <v-container
      class="pa-0 fullWidth"
      :class="[
        $store.getters.hasdarkflag === true ? 'black-main' : 'white-main',
      ]"
    >
      <router-view /><!--Renderisa cualquier vista que este activa-->
    </v-container>
  </v-app>
</template>

<script>
import navbar from "@/pwa-components/nav-pwa.vue";
//import ButtonCreate from "../pwa-components/button-create.vue";
export default {
  name: "PWALayout", // you can enter any name (optional)
  components: {
    navbar,
    //ButtonCreate,
  },
  computed: {
    darkflag() {
      return this.$store.getters.hasdarkflag;
    },
  },
};
</script>

<style scoped>
/* This is for documentation purposes and will not be needed in your application */
.fullWidth {
  width: 100vw;
  min-height: 100vh;
  display: flex;
}
.black-main {
  background-color: #272727;
  min-height: 100vh;
}
.white-main {
  background-color: #fff;
  min-height: 100vh;
}

/* your style */
</style>