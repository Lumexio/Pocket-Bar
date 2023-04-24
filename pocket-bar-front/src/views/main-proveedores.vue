<template>
  <div>
    <v-row>
      <v-col sm="10" md="10">
        <tablaproveedor />
      </v-col>
      <v-col sm="1" md="2">
        <creacionlist />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import tablaproveedor from "../components/table-lists/tabla-proveedor.vue";
  import creacionlist from "../components/global/creacion-proveedor.vue";
  export default {
    name: "marcas",
    components: { tablaproveedor, creacionlist },
  };
</script>

<style scoped>
  #creation {
    padding: 4.5rem 0 0 0;
  }
</style>