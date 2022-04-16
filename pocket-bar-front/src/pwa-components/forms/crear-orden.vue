<template>
  <v-dialog
    v-model="dialogorden"
    fullscreen
    hide-overlay
    transition="dialog-bottom-transition"
  >
    <v-card>
      <v-toolbar dark>
        <v-btn icon dark @click="close()">
          <v-icon>mdi-close</v-icon>
        </v-btn>

        <v-toolbar-title>Nueva orden</v-toolbar-title>

        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-tab>
            <v-badge
              bottom
              left
              overlap
              color="green"
              v-if="countproductos != 0"
              :content="countproductos"
            >
            </v-badge>
          </v-tab>
        </v-toolbar-items>
      </v-toolbar>
      <v-stepper fill-height v-model="e6" editable>
        <v-stepper-items>
          <v-stepper-header style="box-shadow: none">
            <v-stepper-step :complete="e6 > 1" step="1">
              Seleccionar productos
            </v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step step="2"> Lista de productos </v-stepper-step>
          </v-stepper-header>

          <v-stepper-content class="pa-0" step="1">
            <v-btn dark v-if="pedidoArray.length > 0" @click="cancelarPedido()">
              cancelar pedido
            </v-btn>
            <v-btn
              dark
              v-if="pedidoArray.length > 0"
              class="boton"
              color="primary"
              @click="e6 = 2"
            >
              <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
            <v-data-iterator
              :items="articulosArray"
              :items-per-page.sync="itemsPerPage"
              :page.sync="page"
              :search="search"
              :sort-by="sortBy.toLowerCase()"
              :sort-desc="sortDesc"
              hide-default-footer
            >
              <template v-slot:header>
                <v-toolbar flat light class="mb-1">
                  <v-text-field
                    v-model="search"
                    clearable
                    flat
                    hide-details
                    prepend-inner-icon="mdi-magnify"
                    label="Buscar"
                  ></v-text-field>
                  <!-- <v-select
                    v-model="sortBy"
                    flat
                    solo-inverted
                    hide-details
                    :items="keys"
                    prepend-inner-icon="mdi-magnify"
                    label="Sort by"
                  ></v-select>

                  <v-btn-toggle v-model="sortDesc" mandatory>
                    <v-btn large depressed color="blue" :value="false">
                      <v-icon>mdi-arrow-up</v-icon>
                    </v-btn>
                    <v-btn large depressed color="blue" :value="true">
                      <v-icon>mdi-arrow-down</v-icon>
                    </v-btn>
                  </v-btn-toggle> -->
                </v-toolbar>
              </template>

              <template v-slot:default="props">
                <v-row>
                  <v-col v-for="item in props.items" :key="item.name" cols="6">
                    <v-card @click="cajaProductos(item)">
                      <v-img
                        v-bind:lazy-src="item.foto_articulo"
                        max-height="500"
                        max-width="600"
                        v-bind:src="item.foto_articulo"
                      ></v-img>
                      <v-card-title class="subheading font-weight-bold">
                        {{ item.nombre_articulo }}
                      </v-card-title>
                      <v-card-text class="text font-weight-light">
                        {{ item.precio_articulo }}
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </template>

              <template v-slot:footer>
                <v-row class="mt-2" align="center" justify="center">
                  <span class="mr-4 grey--text">
                    Page {{ page }} of {{ numberOfPages }}
                  </span>
                  <v-btn dark class="mr-1" @click="formerPage">
                    <v-icon>mdi-chevron-left</v-icon>
                  </v-btn>
                  <v-btn dark class="ml-1" @click="nextPage">
                    <v-icon>mdi-chevron-right</v-icon>
                  </v-btn>
                </v-row>
              </template>
            </v-data-iterator>
            <v-spacer class="pb-6"></v-spacer>
          </v-stepper-content>

          <v-stepper-content step="2">
            <v-text-field
              label="Titular"
              class="ma-2"
              v-model="titular"
              outlined
            ></v-text-field>
            <v-card
              class="ma-6"
              v-for="(item, index) in pedidoArray"
              :key="index"
            >
              <v-row
                ><v-col
                  ><span>{{ item.nombre_articulo }}</span></v-col
                ><v-spacer></v-spacer
                ><v-col
                  ><span>{{ item.precio_articulo }}</span></v-col
                >
                <v-col>
                  <v-btn icon @click="deleteProduct(index)">
                    <v-icon>mdi-close</v-icon>
                  </v-btn></v-col
                ></v-row
              >
              <v-row :key="refresher"
                ><v-btn text @click="sumaresta('resta', item, index)"
                  ><v-icon>mdi-chevron-left</v-icon></v-btn
                ><span class="ma-2">{{ item.piezas }} </span
                ><v-btn text @click="sumaresta('suma', item, index)"
                  ><v-icon>mdi-chevron-right</v-icon></v-btn
                ></v-row
              >
            </v-card>
            <v-btn text @click="e6 = 1">
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-btn dark color="red" class="boton-prcess" @click="crearTicket()">
              Procesar pedido
            </v-btn>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>
    </v-card>
  </v-dialog>
</template>

<script>
import { getArticulos } from "@/api/articulos.js";
import { postTickets } from "@/api/tickets.js";
import store from "@/store";
export default {
  props: {
    dialogorden: { default: false },
  } /*data de llegado de componente padre creacion*/,
  data() {
    return {
      dialog: false,
      pedidoArray: [],
      countproductos: 0,
      refresher: 0,
      titular: "",
      e6: 1,
      steps: 2,
      itemsPerPageArray: [4, 8, 12],
      search: "",
      filter: {},
      sortDesc: false,
      page: 1,
      itemsPerPage: 15,
      sortBy: "name",
      keys: [
        "Name",
        "Calories",
        "Fat",
        "Carbs",
        "Protein",
        "Sodium",
        "Calcium",
        "Iron",
      ],
      articulosArray: [],
      items: [
        {
          name: "Frozen Yogurt",
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0,
          sodium: 87,
          calcium: "14%",
          iron: "1%",
        },
        {
          name: "Ice cream sandwich",
          calories: 237,
          fat: 9.0,
          carbs: 37,
          protein: 4.3,
          sodium: 129,
          calcium: "8%",
          iron: "1%",
        },
        {
          name: "Eclair",
          calories: 262,
          fat: 16.0,
          carbs: 23,
          protein: 6.0,
          sodium: 337,
          calcium: "6%",
          iron: "7%",
        },
        {
          name: "Cupcake",
          calories: 305,
          fat: 3.7,
          carbs: 67,
          protein: 4.3,
          sodium: 413,
          calcium: "3%",
          iron: "8%",
        },
        {
          name: "Gingerbread",
          calories: 356,
          fat: 16.0,
          carbs: 49,
          protein: 3.9,
          sodium: 327,
          calcium: "7%",
          iron: "16%",
        },
        {
          name: "Jelly bean",
          calories: 375,
          fat: 0.0,
          carbs: 94,
          protein: 0.0,
          sodium: 50,
          calcium: "0%",
          iron: "0%",
        },
        {
          name: "Lollipop",
          calories: 392,
          fat: 0.2,
          carbs: 98,
          protein: 0,
          sodium: 38,
          calcium: "0%",
          iron: "2%",
        },
        {
          name: "Honeycomb",
          calories: 408,
          fat: 3.2,
          carbs: 87,
          protein: 6.5,
          sodium: 562,
          calcium: "0%",
          iron: "45%",
        },
        {
          name: "Donut",
          calories: 452,
          fat: 25.0,
          carbs: 51,
          protein: 4.9,
          sodium: 326,
          calcium: "2%",
          iron: "22%",
        },
        {
          name: "KitKat",
          calories: 518,
          fat: 26.0,
          carbs: 65,
          protein: 7,
          sodium: 54,
          calcium: "12%",
          iron: "6%",
        },
      ],
    };
  },
  methods: {
    crearTicket() {
      let mesa = 2;
      var presend = {
        productos: this.pedidoArray,
        titular: this.titular,
        mesa: mesa,
      };

      postTickets(presend);
    },
    sumaresta(operacion, producto, index) {
      if (operacion == "suma") {
        producto.piezas += 1;
        this.refresher += 1;
      } else if (operacion == "resta") {
        producto.piezas -= 1;
        this.refresher -= 1;
      }
      this.pedidoArray[index] = producto;
    },
    cancelarPedido() {
      return (this.pedidoArray = []), (this.countproductos = 0);
    },
    deleteProduct(producto) {
      this.countproductos -= 1;
      this.pedidoArray.splice(producto, 1);
    },
    close() {
      this.$emit("update:dialogorden", false);
    },
    cajaProductos(producto) {
      producto.piezas = 1;
      this.pedidoArray.push(producto);
      this.countproductos = this.pedidoArray.length;
    },
    nextStep(n) {
      if (n === this.steps) {
        this.e1 = 1;
      } else {
        this.e1 = n + 1;
      }
    },
    nextPage() {
      if (this.page + 1 <= this.numberOfPages) this.page += 1;
    },
    formerPage() {
      if (this.page - 1 >= 1) this.page -= 1;
    },
    updateItemsPerPage(number) {
      this.itemsPerPage = number;
    },
  },
  mounted() {
    getArticulos(this.articulosArray)
      .then((response) => {
        if (response.stats === 200) {
          this.cargando = false;
          store.commit("setsuccess", false); //para resetear el valor de la notificion en una nueva entrada
          store.commit("setdanger", false);
        }
      })
      .catch((e) => {
        console.log(e);
        this.cargando = true;
      });
  },
  computed: {
    numberOfPages() {
      return Math.ceil(this.items.length / this.itemsPerPage);
    },
    filteredKeys() {
      return this.keys.filter((key) => key !== "Name");
    },
  },
  watch: {
    steps(val) {
      if (this.e1 > val) {
        this.e1 = val;
      }
    },
  },
};
</script>

<style scoped>
.boton {
  left: 10%;
  right: 0%;
}
.boton-prcess {
  left: 16%;
  right: 0%;
}
</style>