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
            v-shortkey="['ctrl', 'shift', 'c']"
            @shortkey="dialogcategoria = !dialogcategoria"
            elevation="0"
            dark
            rounded
            @click="dialogcategoria = !dialogcategoria"
          >
            <v-icon left large>mdi-plus</v-icon>
            Categoria
          </v-btn>
        </v-row>
      </v-col>
    </v-row>

    <crearcategoria :dialogcategoria.sync="dialogcategoria" />
  </div>
</template>

<script>
  import crearcategoria from "../cruds/crearcategoria.vue";

  export default {
    name: "crearlist",

    components: {
      crearcategoria,
    },

    data: () => ({
      dialogcategoria: false,
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