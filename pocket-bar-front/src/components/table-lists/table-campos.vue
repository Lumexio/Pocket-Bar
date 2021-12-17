<template>
  <div id="app">
    <v-app id="inspire">
      <v-container fluid>
        <v-data-iterator :items="items" hide-default-footer>
          <template v-slot:header>
            <v-toolbar class="mb-2" color="indigo darken-5" dark flat>
              <v-toolbar-title>This is a header</v-toolbar-title>
            </v-toolbar>
          </template>

          <template v-slot:default="props">
            <v-row>
              <v-col
                v-for="item in props.items"
                :key="item.name"
                cols="12"
                sm="6"
                md="4"
                lg="3"
              >
                <v-card>
                  <v-card-title class="subheading font-weight-bold">
                    {{ item.name }}
                  </v-card-title>

                  <v-divider></v-divider>

                  <v-list dense>
                    <v-list-item>
                      <v-list-item-content
                        :items="itemsc"
                        item-text="nombre_categoria"
                        item-value="categoria_id"
                      >
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
                </v-card>
              </v-col>
            </v-row>
          </template>

          <template v-slot:footer>
            <v-toolbar class="mt-2" color="indigo" dark flat>
              <v-toolbar-title class="subheading">
                This is a footer
              </v-toolbar-title>
            </v-toolbar>
          </template>
        </v-data-iterator>
      </v-container>
    </v-app>
  </div>
</template>

<script>
  import axios from "axios";
  axios.defaults.withCredentials = true;
  axios.defaults.baseURL = "http://127.0.0.1:8000/";
  export default {
    name: "table-campos",
    data: () => ({
      itemsc: [], //categoria
      itemstt: [], //tipo
      itemsp: [], //proveedor
      itemstm: [], //marca
      itemstst: [], //status
      itemsr: [], //rack
      itemsT: [], //travesaño

      items: [
        {
          name: "Categoria",
        },
        {
          name: "Marca",
        },
        {
          name: "Proveedor",
        },
        {
          name: "status",
        },
        {
          name: "Tipo",
        },
        {
          name: "Rack",
        },
        {
          name: "Travesaño",
        },
      ],
    }),
    mounted() {
      axios
        .get("api/categoria")
        .then((response) => {
          let categorias = response.data;

          categorias.forEach((element) => {
            let datos = {
              categoria_id: element.id,
              nombre_categoria: element.nombre_categoria,
            };

            if (!datos) return;
            this.itemsc.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });
      axios
        .get("api/marca")
        .then((response) => {
          let marcas = response.data;

          marcas.forEach((element) => {
            let datos = {
              marca_id: element.id,
              nombre_marca: element.nombre_marca,
            };

            if (!datos) return;
            this.itemstm.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });
      axios
        .get("api/proveedor")
        .then((response) => {
          let proveedores = response.data;

          proveedores.forEach((element) => {
            let datos = {
              proveedor_id: element.id,
              nombre_proveedor: element.nombre_proveedor,
            };

            if (!datos) return;
            this.itemsp.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });
      axios
        .get("api/status")
        .then((response) => {
          let status = response.data;

          status.forEach((element) => {
            let datos = {
              status_id: element.id,
              nombre_status: element.nombre_status,
            };

            if (!datos) return;
            this.itemstst.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });
      axios
        .get("api/tipo")
        .then((response) => {
          let tipos = response.data;

          tipos.forEach((element) => {
            let datos = {
              tipo_id: element.id,
              nombre_tipo: element.nombre_tipo,
            };

            if (!datos) return;
            this.itemstt.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });

      axios
        .get("api/rack")
        .then((response) => {
          let racks = response.data;

          racks.forEach((element) => {
            let datos = {
              rack_id: element.id,
              nombre_rack: element.nombre_rack,
            };

            if (!datos) return;
            this.itemsr.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });
      axios
        .get("api/travesano")
        .then((response) => {
          let travesaños = response.data;

          travesaños.forEach((element) => {
            let datos = {
              travesaño_id: element.id,
              nombre_travesano: element.nombre_travesano,
            };

            if (!datos) return;
            this.itemsT.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });
    },
    computed: {},
    methods: {},
  };
</script>

<style scoped>
</style>