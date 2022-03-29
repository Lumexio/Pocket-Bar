<template >
  <v-expansion-panels popout>
    <v-expansion-panel v-for="item in ticketsPWAArray" :key="item.id">
      <v-expansion-panel-header>
        <span> Titular:{{ item.titular }} Mesa:{{ item.mesa }} </span>
        <span>Toma de orden:{{ item.fecha }}</span>
      </v-expansion-panel-header>
      <v-expansion-panel-content>
        <v-simple-table dense>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Nombre</th>
                <th class="text-left">Cantidad</th>
                <th class="text-left">Precio</th>

                <th class="text-left">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="producto in item.productos" :key="producto.id">
                <td>{{ producto.nombre }}</td>
                <td>{{ producto.cantidad }}</td>
                <td>{{ producto.precio }}</td>
                <td>{{ producto.total }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        Subtotal:{{ item.total_actual }}
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
import store from "@/store";
import { getTicketsPWA } from "@/api/tickets.js";
export default {
  name: "ordenesBarra",
  data: () => ({
    ticketsPWAArray: [],
  }),
  mounted() {
    getTicketsPWA(this.ticketsPWAArray, store.getters.hasstatus);
  },
};
</script>

<style>
</style>