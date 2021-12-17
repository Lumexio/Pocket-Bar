<template>
  <v-container fluid>
    <v-row>
      <v-col sm="8" md="10">
        <tablausuarios v-show="hasrol === 1" :key="count" />
      </v-col>
      <v-col sm="1" md="2">
        <creacionuserlist v-show="hasrol === 1" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  import tablausuarios from "../components/table-lists/tabla-usuarios.vue";
  import creacionuserlist from "../components/global/creacion-user.vue";
  import store from "@/store";
  export default {
    name: "artículos",
    components: { tablausuarios, creacionuserlist },
    computed: {
      count() {
        /*contador que apoya en actualizar la tabla en cada cambion en la numeracion*/
        return store.getters.counter;
      },
      hasrol() {
        /*para obtener el rol al que pertenece el usuario y dependiendo de eso podra observar el componente */
        return store.getters.hasrol;
      },
    },
  };
</script>

<style scoped>
</style>