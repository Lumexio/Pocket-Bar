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
            v-shortkey="['ctrl', 'shift', 'e']"
            @shortkey="dialogtravesaño = !dialogtravesaño"
            elevation="0"
            dark
            rounded
            @click="dialogtravesaño = true"
          >
            <v-icon left large dark>mdi-plus</v-icon>
            Travesaño
          </v-btn>
        </v-row>
      </v-col>
    </v-row>

    <creartravesaño :dialogtravesaño.sync="dialogtravesaño" />
  </div>
</template>

<script>
  import creartravesaño from "../cruds/creartravesaño.vue";

  export default {
    name: "crearlist",

    components: {
      creartravesaño,
    },
    methods: {
      syncFromCategoria(updatedDialog) {
        this.dialogtravesaño = updatedDialog;
      },
    },
    data: () => ({
      dialogtravesaño: false,
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