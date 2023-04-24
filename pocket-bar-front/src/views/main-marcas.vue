<template>
  <div>
    <v-row>
      <v-col sm="8" md="10">
        <tablamarca />
      </v-col>
      <v-col sm="1" md="2">
        <creacionlist />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import tablamarca from "../components/table-lists/tabla-marca.vue";
  import creacionlist from "../components/global/creacion-marca.vue";
  export default {
    name: "marcas",
    components: { tablamarca, creacionlist },
  };
</script>

<style scoped>
</style>



