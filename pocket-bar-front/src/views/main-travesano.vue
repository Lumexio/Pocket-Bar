<template>
  <div>
    <v-row>
      <v-col sm="10" md="10">
        <tablatravesano :key="count" />
      </v-col>
      <v-col sm="1" md="2">
        <creacionlist />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import tablatravesano from "../components/table-lists/table-travesano.vue";
  import store from "@/store";
  import creacionlist from "../components/global/creacion-travesano.vue";
  export default {
    name: "travesaño",
    components: { tablatravesano, creacionlist },
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