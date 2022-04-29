<template>
  <v-app id="inspire">
    <navbar />
    <v-container class="pa-0">
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
};
</script>
 
<style scoped>
/* This is for documentation purposes and will not be needed in your application */

/* your style */
</style>