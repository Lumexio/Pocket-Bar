<template>
  <div>
    <v-row>
      <v-col sm="10" md="10">
        <tablatipo :key="count" />
      </v-col>
      <v-col sm="1" md="2">
        <creacionlist />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import tablatipo from "../components/table-lists/tabla-tipo.vue";
  import store from "@/store";
  import creacionlist from "../components/global/creacion-tipo.vue";
  export default {
    name: "tipo",
    components: { tablatipo, creacionlist },
    computed: {
      count() {
        return store.getters.counter;
      },
    },
  };
</script>

<style scoped>
  #creation {
    padding: 4.5rem 0 0 0;
  }
</style>