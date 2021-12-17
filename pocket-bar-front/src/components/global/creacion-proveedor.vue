
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
            v-shortkey="['ctrl', 'shift', 'p']"
            @shortkey="dialogproveedor = !dialogproveedor"
            elevation="0"
            dark
            rounded
            @click="dialogproveedor = !dialogproveedor"
          >
            <v-icon left large dark>mdi-plus</v-icon>
            Proveedor
          </v-btn>
        </v-row>
      </v-col>
    </v-row>

    <crearproveedor :dialogproveedor.sync="dialogproveedor" />
  </div>
</template>

<script>
  import crearproveedor from "../cruds/crearproveedor.vue";

  export default {
    name: "crearlist",
    components: {
      crearproveedor,
    },

    data: () => ({
      dialogproveedor: false,
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