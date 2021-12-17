<template>
  <div class="tabla" id="app">
    <v-row>
      <v-col cols="12" sm="6" md="4">
        <v-text-field
          v-model="search"
          label="Buscar categoria"
          class="mx-4"
          id="onsearch"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-app id="inspire">
      <v-progress-linear
        height="6"
        indeterminate
        color="cyan"
        :active="cargando"
      ></v-progress-linear>
      <v-data-table
        id="tabla"
        :headers="headers"
        show-expand
        :expanded.sync="expanded"
        :items="categoriaArray"
        sort-by="cantidad_articulo"
        class="elevation-1"
        :search="search"
        :custom-filter="filterOnlyCapsText.toUpperCase"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Tabla categoria</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="500px">
              <v-card>
                <v-card-title>
                  <h1 class="headline">{{ formTitle }}</h1>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col>
                        <v-text-field
                          v-model="editedItem.nombre_categoria"
                          label="Nombre"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row
                      ><v-col>
                        <v-textarea
                          v-model="editedItem.descripcion_categoria"
                          label="Descrpción"
                          type="text"
                        ></v-textarea> </v-col
                    ></v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="blue darken-1"
                    v-on:keyup.enter="save"
                    text
                    @click="close"
                  >
                    Cancelar
                  </v-btn>
                  <v-btn color="blue darken-1" text @click="save">
                    Guardar
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="headline"
                  >¿Estas seguro de querer eliminarlo?</v-card-title
                >
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="closeDelete"
                    >Cancelar</v-btn
                  >
                  <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                    >Aceptar</v-btn
                  >
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">
            mdi-pencil
          </v-icon>
          <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
        </template>
        <template v-slot:no-data>
          <span>Datos no disponibles.</span>
        </template>
        <template v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length">
            {{ item.descripcion_categoria }}
          </td>
        </template>
      </v-data-table>
    </v-app>
  </div>
</template>

<script>
  import {
    getCategorias,
    deleteCategoria,
    editCategoria,
  } from "@/api/categorias.js";
  import { upperConverter } from "@/special/uppercases-converter.js";
  export default {
    nombre_categoria: "tabla-categoria",
    data: () => ({
      dialog: false,
      dialogDelete: false,
      search: "",
      cargando: true,
      expanded: [],
      headers: [
        {
          text: "Categorias",
          align: "start",
          sortable: false,
          value: "nombre_categoria",
        },
        { text: "Acciones", value: "actions", sortable: false, align: "center" },
        { text: "Descripción", align: "start", value: "data-table-expand" },
      ],

      categoriaArray: [],
      //variable en la que se deposita la posicion en el selector
      selectrol: null, //Rol

      //Array en el que se deposita de los selectores.
      itemsrol: [], //Rol

      editedIndex: -1,
      editedItem: {
        id: "",
        nombre_categoria: "",
        descripcion_categoria: "",
      },
      defaultItem: {
        id: "",
        nombre_categoria: "",
        descripcion_categoria: "",
      },
    }),
    mounted() {
      this.onFocus();
      window.Echo.channel("categorias").listen("categoriaCreated", (e) => {
        this.categoriaArray = e.categorias;
      });
      getCategorias(this.categoriaArray)
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

    computed: {
      formTitle() {
        return this.editedIndex === -1 ? "New Item" : "Editar categoria";
      },
    },

    watch: {
      dialog(val) {
        val || this.close();
      },
      dialogDelete(val) {
        val || this.closeDelete();
      },
    },

    created() {},

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

      editItem(item) {
        this.editedIndex = this.categoriaArray.indexOf(item);
        this.editedItem = Object.assign({}, item);

        this.dialog = true;
      },

      deleteItem(item) {
        this.editedIndex = this.categoriaArray.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialogDelete = true;
      },

      deleteItemConfirm() {
        this.categoriaArray.splice(this.editedIndex, 1);
        let id = this.editedItem.id;
        deleteCategoria(id);

        this.closeDelete();
        window.Echo.channel("categorias").listen("categoriaCreated", (e) => {
          this.itemsc = e.categorias;
        });
      },

      close() {
        this.dialog = false;
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem);
          this.editedIndex = -1;
        });
      },

      closeDelete() {
        this.dialogDelete = false;
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem);
          this.editedIndex = -1;
        });
      },

      save() {
        if (this.editedIndex > -1) {
          Object.assign(this.categoriaArray[this.editedIndex], this.editedItem);
          let send = this.editedItem;
          send.nombre_categoria = upperConverter(send.nombre_categoria);
          let url = "api/categoria/";
          url = url + send.id;
          url = `${url}?${"nombre_categoria=" + send.nombre_categoria}&${
            "descripcion_categoria=" + send.descripcion_categoria
          }`;
          editCategoria(url);
          window.Echo.channel("categorias").listen("categoriaCreated", (e) => {
            this.itemsc = e.categorias;
          });
        } else {
          this.categoriaArray.push(this.editedItem);
        }
        this.close();
      },
    },
  };
</script>

<style scoped>
  #tabla {
    width: 60rem;
  }
  .tabla {
    width: 60rem;
  }
</style>