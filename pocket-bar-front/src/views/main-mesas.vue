<template>
  <div>
    <v-row>
      <v-col sm="10" md="10">
        <tablamesa />
      </v-col>
      <v-col sm="1" md="2">
        <creacionlist />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import tablamesa from "../components/table-lists/tabla-mesas.vue";
  import creacionlist from "../components/global/creacion-mesa.vue";
  export default {
    name: "mesas",
    components: { tablamesa, creacionlist },
  };
</script>

<style scoped>
</style>