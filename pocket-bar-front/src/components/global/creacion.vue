<template >
  <!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
  <div style="width: 64%" class="pa-3">
    <v-row>
      <v-col align-self="end">
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 'a']"
                @shortkey="dialogarticulo = !dialogarticulo"
                elevation="0"
                dark
                @click="dialogarticulo = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Artículos
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+a</code>
          </v-tooltip>
        </v-row>
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 'c']"
                @shortkey="dialogcategoria = !dialogcategoria"
                elevation="0"
                dark
                @click="dialogcategoria = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Categoría
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+c</code>
          </v-tooltip>
        </v-row>
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 'm']"
                @shortkey="dialogmarca = !dialogmarca"
                elevation="0"
                dark
                @click="dialogmarca = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Marca
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+m</code>
          </v-tooltip>
        </v-row>
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 't']"
                @shortkey="dialogtipo = !dialogtipo"
                elevation="0"
                dark
                @click="dialogtipo = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Tipo
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+t</code>
          </v-tooltip>
        </v-row>
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 'p']"
                @shortkey="dialogproveedor = !dialogproveedor"
                elevation="0"
                dark
                @click="dialogproveedor = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Proveedor
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+p</code>
          </v-tooltip>
        </v-row>
        <!--<v-row class="ma-2">
          <v-btn elevation="0"
                dark  @click="dialogstatus = !dialogstatus">
            Status
          </v-btn>
        </v-row>-->

        <v-row class="ma-2"><v-subheader>Ubicación</v-subheader></v-row>
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 'r']"
                @shortkey="dialograck = !dialograck"
                elevation="0"
                dark
                @click="dialograck = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Rack
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+r</code>
          </v-tooltip>
        </v-row>
        <v-row class="ma-2">
          <v-tooltip open-delay="500" left>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                v-shortkey="['ctrl', 'shift', 'e']"
                @shortkey="dialogtravesaño = !dialogtravesaño"
                elevation="0"
                dark
                @click="dialogtravesaño = true"
                v-bind="attrs"
                v-on="on"
                rounded
              >
                <v-icon left large>mdi-plus</v-icon>
                Travesaño
              </v-btn>
            </template>
            <code>abrir y cerrar:ctrl+shift+e</code>
          </v-tooltip>
        </v-row>
      </v-col>
    </v-row>

    <creararticulo :key="count" :dialogarticulo.sync="dialogarticulo" />
    <crearcategoria :dialogcategoria.sync="dialogcategoria" />
    <crearmarca :dialogmarca.sync="dialogmarca" />
    <creartipo :dialogtipo.sync="dialogtipo" />
    <crearproveedor :dialogproveedor.sync="dialogproveedor" />
    <!--<crearstatus
      :parentdialog="dialogstatus"
      v-on:dialogFromChild="syncFromStatus($event)"
     
    />-->

    <crearrack :dialograck.sync="dialograck" />
    <creartravesaño :dialogtravesaño.sync="dialogtravesaño" />
  </div>
</template>

<script>
import creararticulo from "../cruds/creararticulos.vue";
import crearcategoria from "../cruds/crearcategoria.vue";
import crearmarca from "../cruds/crearmarca.vue";
import creartipo from "../cruds/creartipo.vue";
import crearproveedor from "../cruds/crearproveedor.vue";

//import crearstatus from "../cruds/crearstatus.vue";

import crearrack from "../cruds/crearrack.vue";
import creartravesaño from "../cruds/creartravesaño.vue";
import store from "@/store";

export default {
  name: "crearlist",

  components: {
    creararticulo,
    crearcategoria,
    crearmarca,
    creartipo,
    crearproveedor,
    //crearstatus,
    crearrack,
    creartravesaño,
  },
  computed: {
    count() {
      return store.getters.counter;
    },
  },

  data: () => ({
    dialogarticulo: false,
    dialogcategoria: false,
    dialogmarca: false,
    dialogtipo: false,
    dialogproveedor: false,
    //dialogstatus: false,
    dialograck: false,
    dialogtravesaño: false,
  }),
};
</script>
<style scoped>
</style>