<template>
  <v-data-table
    :dark="this.$store.getters.hasdarkflag"
    id="tabla"
    :headers="headers"
    show-expand
    :expanded.sync="expanded"
    :items="activitylogArray"
    sort-by="cantidad_articulo"
    class="elevation-1"
    :search="search"
    :custom-filter="filterOnlyCapsText.toUpperCase"
  >
    <template v-slot:top>
      <v-toolbar flat color="transparent">
        <v-toolbar-title>Tabla historial</v-toolbar-title>
        <v-divider class="ml-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          label="Buscar actividad"
          class="mt-4"
          id="onsearch"
        ></v-text-field>
      </v-toolbar>
      <v-progress-linear
        height="6"
        indeterminate
        color="cyan"
        :active="cargando"
      ></v-progress-linear
    ></template>
    <template v-slot:no-data>
      <span>Datos no disponibles.</span>
    </template>
    <template v-slot:expanded-item="{ headers, item }">
      <td :colspan="headers.length">
        {{ item.properties }}
      </td>
    </template>
  </v-data-table>
</template>

<script>
import { getActivitylog } from "@/api/activitylog.js";

export default {
  name: "tabla-activitylog",
  data: () => ({
    search: "",
    cargando: true,
    expanded: [],
    headers: [
      {
        text: "Actividad",
        align: "start",
        sortable: false,
        value: "accion",
      },
      {
        text: "Responsable",
        align: "start",
        sortable: false,
        value: "causername",
      },
      {
        text: "Creado en",
        align: "start",
        sortable: false,
        value: "createdat",
      },
      {
        text: "Actualizado en",
        align: "start",
        sortable: false,
        value: "updatedat",
      },
      { text: "Descripción", align: "start", value: "data-table-expand" },
    ],

    activitylogArray: [],
  }),
  mounted() {
    this.onFocus();
    // window.Echo.channel("activitylog").listen("activitylogCreated", (e) => {
    //   this.activitylogArray = e.activitylog;
    // });
    getActivitylog(this.activitylogArray)
      .then((response) => {
        if (response.stats === 200) {
          this.cargando = false;
        }
      })
      .catch((e) => {
        console.log(e);
        this.cargando = true;
      });
  },
  methods: {
    onFocus() {
      let stext = document.getElementById("onsearch");
      stext;
      stext = addEventListener("keydown", (e) => {
        if (e.altKey) {
          document.getElementById("onsearch").focus();
        }
      });
    },
    filterOnlyCapsText(value, search) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value.toString().toLocaleUpperCase().indexOf(search) !== -1
      );
    },
  },
};
</script>

<style scoped>
#tabla {
  width: 100%;
}
.tabla {
  width: 100%;
}
</style>