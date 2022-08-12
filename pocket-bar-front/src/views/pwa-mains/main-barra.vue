<template>
  <ordenes-barras :key="count" />
</template>

<script>
import store from "@/store";

import OrdenesBarras from "@/pwa-components/tables/ordenes-barras.vue";
export default {
  name: "MainBarra",
  components: { OrdenesBarras },
  data: () => ({}),

  created() {
    /*para ejecutar lo que yo quiera automaticamente*/
  },
  computed: {
    count() {
      return store.getters.counter;
    },
  },

  // hasrol() {
  //   return store.getters.hasrol;
  // },
};
</script>

<style scoped>
</style>