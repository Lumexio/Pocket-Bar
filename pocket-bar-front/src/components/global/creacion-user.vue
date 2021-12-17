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
            v-shortkey="['ctrl', 'shift', 'u']"
            @shortkey="dialogusuarios = !dialogusuarios"
            elevation="0"
            dark
            rounded
            @click="dialogusuarios = true"
          >
            <v-icon left large>mdi-plus</v-icon>
            Usuarios
          </v-btn>
        </v-row>
        <!-- <v-row>
          <v-btn color="primary" text @click="dialogrol = !dialogrol">
            Rol
          </v-btn>
        </v-row>-->
      </v-col>
    </v-row>

    <crearusuario :dialogusuarios.sync="dialogusuarios" />
    <!--
    <crearrol
      :parentdialog="dialogrol"
      v-on:dialogFromChild="syncFromRol($event)"
     
    />-->
  </div>
</template>

<script>
  import crearusuario from "../cruds/crearusuario.vue";
  //import crearrol from "../cruds/crearrol.vue";
  import store from "@/store";
  export default {
    name: "crearlist",

    components: {
      crearusuario,
      //crearrol,
    },
    computed: {
      count() {
        return store.getters.counter;
      },
    },
    methods: {
      syncFromRol(updatedDialog) {
        this.dialogrol = updatedDialog;
      },
    },
    data: () => ({
      dialogusuarios: false,
      dialogrol: false,
    }),
  };
</script>
<style scoped>
</style>