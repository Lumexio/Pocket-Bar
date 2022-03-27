<template>
  <v-expansion-panels popout>
    <v-expansion-panel v-for="(item, index) in items" :key="index">
      <v-expansion-panel-header>
        <span> Titular:{{ item.titular }} Mesa:{{ item.mesa }} </span>
        <span>Toma de orden:{{ item.fecha_de_creacion }}</span>
      </v-expansion-panel-header>
      <v-expansion-panel-content>
        Subtotal:{{ item.precio_total }}
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
import { getTicketsPWA } from "@/api/tickets.js";
export default {
  name: "ordenesBarra",
  data: () => ({
    ticketsPWAArray: [],
    items: [
      {
        id: 1,
        estatus: "pendiente",
        titular: "Jorge",
        mesa: 13,
        fecha_de_creacion: "10/03/22",
        productos: [{ id: 1, nombre: "coca cola", precio: 20 }],
        precio_total: 200.0,
        precio_subtotal: 200.01,
      },
      {
        id: 2,
        estatus: "pendiente",
        titular: "Ericka",
        mesa: 2,
        fecha_de_creacion: "10/03/22",
        productos: [{ id: 1, nombre: "coca cola", precio: 20 }],
        precio_total: 200.0,
        precio_subtotal: 200.01,
      },
      {
        id: 3,
        estatus: "pendiente",
        titular: "Manuel",
        mesa: 3,
        fecha_de_creacion: "10/03/22",
        productos: [{ id: 1, nombre: "coca cola", precio: 20 }],
        precio_total: 200.0,
        precio_subtotal: 200.01,
      },
      {
        id: 4,
        estatus: "pendiente",
        titular: "Monica",
        mesa: 4,
        fecha_de_creacion: "10/03/22",
        productos: [{ id: 1, nombre: "coca cola", precio: 20 }],
        precio_total: 200.0,
        precio_subtotal: 200.01,
      },
      {
        id: 5,
        titular: "Diana",
        mesa: 5,
        fecha_de_creacion: "10/03/22",
        productos: [{ id: 1, nombre: "coca cola", precio: 20 }],
        precio_total: 200.0,
        precio_subtotal: 200.01,
      },
    ],
  }),
  mounted() {
    getTicketsPWA(this.ticketsPWAArray);
  },
};
</script>

<style>
</style>