<template >
  <v-expansion-panels :dark="darkonchange" popout>
    <v-expansion-panel
      class="sizes"
      v-for="item in ticketsPWAArray"
      :key="item.id"
    >
      <v-expansion-panel-header>
        <span>
          <span> <b>Titular: </b>{{ item.titular }}</span>
          <br />
          <span><b>Mesa: </b>{{ item.mesa }}</span>
          <br />
          <span> <b>Fecha: </b>{{ item.fecha }}</span>
        </span>
      </v-expansion-panel-header>
      <v-expansion-panel-content class="expansion-panel">
        <v-simple-table dense calculate-widths>
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
                <td class="text-left">{{ producto.nombre }}</td>
                <td class="text-left">{{ producto.cantidad }}</td>
                <td class="text-left">{{ producto.precio }}</td>
                <td class="text-left">{{ producto.total }}</td>
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
  name: "ordenesMesero",
  data: () => ({
    ticketsPWAArray: [],
  }),
  mounted() {
    window.Echo.channel("tickets").listen("ticketCreated", (e) => {
      this.ticketsPWAArray = e.tickets;
    });
    getTicketsPWA(this.ticketsPWAArray, store.getters.hasstatus);
  },
  computed: {
    darkonchange() {
      return store.getters.hasdarkflag;
    },
  },
};
</script>

<style scoped>
.row {
  display: flex;
  flex-direction: row;
}
.sizes {
  max-width: calc(100% - 0px) !important;
  min-width: calc(100% - 0px) !important;
}
</style>