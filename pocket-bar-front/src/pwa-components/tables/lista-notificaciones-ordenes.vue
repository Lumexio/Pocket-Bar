<template>
  <v-dialog
    v-model="dialoglistorden"
    fullscreen
    hide-overlay
    transition="dialog-bottom-transition"
    ><v-card>
      <v-toolbar dark>
        <v-btn icon dark @click="close()">
          <v-icon>mdi-close</v-icon>
        </v-btn>

        <v-toolbar-title>Pedidos de meseros</v-toolbar-title>
      </v-toolbar>
    </v-card></v-dialog
  >
</template>

<script>
import { getTicketsNotiPWA } from "@/api/tickets.js";
export default {
  name: "listaNotiOrdenes",
  props: {
    dialoglistorden: { default: false },
  } /*data de llegado de componente padre creacion*/,
  data: () => ({
    ticketsPWANotiArray: [],
  }),
  methods: {
    close() {
      this.$emit("update:dialoglistorden", false);
    },
  },
  mounted() {
    getTicketsNotiPWA(this.ticketsPWANotiArray)
      .then((response) => {
        response;
      })
      .catch((e) => {
        console.log(e);
      });
  },
};
</script>

<style>
</style>