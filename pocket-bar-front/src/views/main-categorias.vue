<template>
  <div>
    <v-row>
      <v-col sm="10" md="10">
        <tablacategoria />
      </v-col>
      <v-col sm="1" md="2">
        <creacionlist />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import tablacategoria from "../components/table-lists/tabla-categoria.vue";
  import creacionlist from "../components/global/creacion-categoria.vue";
  export default {
    name: "categorias",
    components: { tablacategoria, creacionlist },
  };
</script>

<style scoped>
</style>