<template>
  <div>
    <v-row
      ><v-col md="12"><tableCampos /></v-col
    ></v-row>
  </div>
</template>
<script>
  import tableCampos from "../components/table-lists/table-campos.vue";
  // @ is an alias to /src
  export default {
    name: "home",
    components: { tableCampos },
  };
</script>
<style scoped>
  #table-article {
    padding-left: 15rem;
  }
</style>
