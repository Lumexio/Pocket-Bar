<template>
  <!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
  <div style="width: 64%" class="pa-3">
    <v-row>
      <v-col align-self="end" cols="2">
        <v-row>
          <v-btn
            v-shortkey="['ctrl', 'shift', 'r']"
            @shortkey="dialograck = !dialograck"
            elevation="0"
            dark
            rounded
            @click="dialograck = true"
          >
            <v-icon left large dark>mdi-plus</v-icon>
            Rack
          </v-btn>
        </v-row>
      </v-col>
    </v-row>

    <crearrack :dialograck.sync="dialograck" />
  </div>
</template>

<script>
  import crearrack from "../cruds/crearrack.vue";

  export default {
    name: "creacionrack",

    components: {
      crearrack,
    },
    methods: {},
    data: () => ({
      dialograck: false,
    }),
  };
</script>
<style scoped>
  .list-card {
    display: flex;
    align-content: center;
    justify-content: center;
    padding: 1em;

    width: 7rem;
    height: 3em;
  }
</style>