<template>
  <v-card :dark="this.$store.getters.hasdarkflag">
    <v-toolbar flat color="transparent">
      <v-toolbar-title>Tabla de ordenes</v-toolbar-title>
      <v-divider inset vertical class="ml-4"></v-divider>
      <v-spacer></v-spacer>
      <v-text-field
        :dark="this.$store.getters.hasdarkflag"
        v-model="search"
        label="Buscar orden"
        class="mt-6"
        id="onsearch"
      ></v-text-field>
    </v-toolbar>

    <v-data-table
      :dark="this.$store.getters.hasdarkflag"
      id="tabla"
      :headers="headers"
      show-expand
      :expanded.sync="expanded"
      :items="ticketsArray"
      sort-by="cantidad_articulo"
      :search="search"
      :custom-filter="filterOnlyCapsText.toUpperCase"
    >
      <template v-slot:top>
        <v-progress-linear
          height="6"
          indeterminate
          color="cyan"
          :active="cargando"
        ></v-progress-linear>
      </template>
      <template v-slot:no-data>
        <span>Datos no disponibles.</span>
      </template>
      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">
          {{ item.properties }}
        </td>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { getTickets } from "@/api/tickets.js";

export default {
  name: "tabla-activitylog",
  data: () => ({
    search: "",
    cargando: true,
    expanded: [],
    headers: [
      {
        text: "Mesero",
        align: "start",
        sortable: false,
        value: "nombre_mesero",
      },
      {
        text: "Total de compra",
        align: "start",
        sortable: false,
        value: "monto_total",
      },
      {
        text: "Estado",
        align: "start",
        sortable: false,
        value: "status_ticket",
      },
      {
        text: "Creado en",
        align: "start",
        sortable: false,
        value: "ticket_date",
      },
      {
        text: "Cerrado en",
        align: "start",
        sortable: false,
        value: "ticket_date",
      },
      { text: "Descripción", align: "start", value: "data-table-expand" },
    ],

    ticketsArray: [],
  }),
  mounted() {
    this.onFocus();
    // window.Echo.channel("activitylog").listen("activitylogCreated", (e) => {
    //   this.ticketsArray = e.activitylog;
    // });
    getTickets(this.ticketsArray)
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