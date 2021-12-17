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
            v-shortkey="['ctrl', 'shift', 't']"
            @shortkey="dialogtipo = !dialogtipo"
            elevation="0"
            dark
            rounded
            @click="dialogtipo = !dialogtipo"
          >
            <v-icon left large dark>mdi-plus</v-icon>
            Tipo
          </v-btn>
        </v-row>
      </v-col>
    </v-row>

    <creartipo :dialogtipo.sync="dialogtipo" />
  </div>
</template>

<script>
  import creartipo from "../cruds/creartipo.vue";

  export default {
    name: "crearlist",

    components: {
      creartipo,
    },
    data: () => ({
      dialogtipo: false,
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