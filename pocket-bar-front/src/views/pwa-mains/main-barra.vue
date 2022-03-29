<template>
  <div><ordenesBarras :key="count" /></div>
</template>

<script>
import store from "@/store";

import ordenesBarras from "@/pwa-components/tables/ordenes-barras.vue";
export default {
  name: "MainBarra",
  components: { ordenesBarras },
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
.size-table {
  max-width: 90%;
}
</style>