<template>
  <div><ordenes-meseros /></div>
</template>

<script>
//import store from "@/store";

import OrdenesMeseros from "../../pwa-components/tables/ordenes-meseros.vue";
export default {
  name: "MainMesero",
  components: { OrdenesMeseros },
  data: () => ({}),

  created() {
    /*para ejecutar lo que yo quiera automaticamente*/
  },
  //  count() {
  //   return store.getters.counter;
  // },
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