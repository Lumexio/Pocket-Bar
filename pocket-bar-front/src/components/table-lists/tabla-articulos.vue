<template>
  <v-app id="inspire">
    <v-row>
      <v-col sm="8" md="5">
        <v-text-field
          label="Buscar artículo"
          placeholder="Nombre, cantidad, categoria, tipo ...."
          class="mx-4"
          v-model="search"
          id="onsearch"
        />
      </v-col>
    </v-row>
    <v-data-table
      :headers="headers"
      :items="articulosArray"
      :expanded.sync="expanded"
      sort-by="cantidad_articulo"
      class="elevation-2"
      :search="search"
      show-expand
      :custom-filter="filterOnlyCapsText.toUpperCase"
    >
      <v-progress-linear
        height="6"
        indeterminate
        color="cyan"
        :active="cargando"
      ></v-progress-linear>
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Tabla artículos</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>

          <v-dialog v-model="dialog" min-width="300px" max-width="600px">
            <v-card>
              <v-card-title>
                <h1 class="headline">{{ formTitle }}</h1>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col sm="4" md="6">
                      <v-text-field
                        v-model="editedItem.nombre_articulo"
                        label="Nombre"
                      ></v-text-field>
                    </v-col>
                    <v-col sm="1" md="2">
                      <v-text-field
                        v-model="editedItem.cantidad_articulo"
                        type="number"
                        label="Cantidad"
                      ></v-text-field>
                    </v-col>
                    <v-col sm="3" md="4">
                      <v-select
                        v-model="selectc"
                        :items="itemsc"
                        item-text="nombre_categoria"
                        item-value="id"
                        label="Categoría"
                      ></v-select>
                    </v-col>
                    <v-col sm="4" md="6">
                      <v-select
                        v-model="selectt"
                        :items="itemstt"
                        item-text="nombre_tipo"
                        item-value="id"
                        label="Tipo"
                      >
                      </v-select>
                    </v-col>
                    <v-col sm="4" md="6">
                      <v-select
                        v-model="selectm"
                        :items="itemstm"
                        item-text="nombre_marca"
                        item-value="id"
                        label="Marca"
                        required
                      ></v-select>
                    </v-col>
                    <v-col sm="4" md="6">
                      <v-select
                        v-model="selectp"
                        :items="itemsp"
                        item-text="nombre_proveedor"
                        item-value="id"
                        label="Proveedor"
                        required
                      ></v-select>
                    </v-col>
                    <v-col sm="4" md="6">
                      <v-select
                        v-model="selectst"
                        :items="itemstst"
                        item-text="nombre_status"
                        item-value="status_id"
                        label="Estatus"
                        required
                      ></v-select>
                    </v-col>
                    <v-col sm="4" md="6">
                      <v-select
                        v-model="selectr"
                        :items="itemsr"
                        item-text="nombre_rack"
                        item-value="id"
                        label="Ubicación rack"
                        required
                      ></v-select>
                    </v-col>
                    <v-col sm="4" md="6">
                      <v-select
                        v-model="selectT"
                        :items="itemsT"
                        item-text="nombre_travesano"
                        item-value="id"
                        label="Ubicación travesaño"
                        required
                      ></v-select>
                    </v-col>
                  </v-row>
                  <v-row
                    ><v-col>
                      <v-textarea
                        v-model="editedItem.descripcion_articulo"
                        label="Descrpción"
                        type="text"
                      ></v-textarea> </v-col
                  ></v-row>
                </v-container>
              </v-card-text>

              <v-card-actions v-on:keyup.enter="save">
                <v-spacer></v-spacer>
                <v-btn color="grey darken-2" outlined @click="close">
                  Cancelar
                </v-btn>
                <v-btn color="yellow darken-2" outlined @click="save">
                  Guardar cambios
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="headline"
                >Are you sure you want to delete this item?</v-card-title
              >
              <v-card-actions v-on:keyup.enter="deleteItemConfirm">
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDelete"
                  >Cancel</v-btn
                >
                <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                  >OK</v-btn
                >
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog
            v-model="dialogDetail"
            transition="dialog-bottom-transition"
            max-width="600"
          >
            <template v-slot:default="dialogDetail">
              <v-card :key="count">
                <v-card-text>
                  <v-toolbar flat>
                    <v-card-title>
                      Foto {{ editedItem.nombre_articulo }}</v-card-title
                    ></v-toolbar
                  >
                  <v-img
                    :colspan="headers.length"
                    v-bind:lazy-src="editedItem.foto_articulo"
                    max-height="500"
                    max-width="600"
                    v-bind:src="editedItem.foto_articulo"
                  ></v-img>
                </v-card-text>
                <v-card-actions class="justify-end">
                  <v-file-input
                    v-model="photo"
                    prepend-icon="mdi-camera"
                    hide-input
                    label="File input"
                  ></v-file-input>
                  <v-btn
                    v-show="photo != null"
                    text
                    color="orange"
                    @click="photochange()"
                    >Subir Imagen</v-btn
                  >
                  <v-btn text @click="dialogDetail.value = false">Close</v-btn>
                </v-card-actions>
              </v-card>
            </template>
          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:[`item.nombre_status`]="{ item }">
        <v-chip :color="getColor(item.nombre_status)" dark>
          {{ item.nombre_status }}
        </v-chip>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
        <v-icon small @click="detailItem(item)"> mdi-file-eye-outline </v-icon>
      </template>
      <template v-slot:no-data>
        <span>Datos no disponibles.</span>
      </template>
      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">
          {{ item.descripcion_articulo }}
        </td>
      </template>
    </v-data-table>
  </v-app>
</template>

<script>
//import axios from "axios";
import store from "@/store";
import { postPhoto } from "@/api/photohandler.js";
import {
  getArticulos,
  deleteArticulos,
  editArticulos,
} from "@/api/articulos.js";

import { getCategorias } from "@/api/categorias.js";
import { getMarcas } from "@/api/marcas.js";
import { getProveedores } from "@/api/proveedores.js";
import { getTipos } from "@/api/tipos.js";
import { getRack } from "@/api/racks.js";
import { getTravesano } from "@/api/travesanos.js";
import { getStatus } from "@/api/status.js";
import { upperConverter } from "@/special/uppercases-converter.js";
import {
  tiposync,
  statusync,
  travesañosync,
  categsync,
  racksync,
  marcasync,
  proveedorsync,
} from "@/special/sync-indexitems.js";

//axios.defaults.baseURL = "http://127.0.0.1:8000/";
export default {
  name: "tabla-articulos",
  data: () => ({
    search: "",
    dialog: false,
    singleExpand: false,
    dialogDelete: false,
    dialogDetail: false,
    cargando: true,
    expanded: [],

    headers: [
      {
        text: "Nombre",
        align: "start",
        sortable: false,
        value: "nombre_articulo",
      },
      { text: "Cantidad", value: "cantidad_articulo", align: "center" },
      { text: "Categoría", value: "nombre_categoria", align: "center" },
      { text: "Tipo", value: "nombre_tipo", align: "center" },
      { text: "Marca", value: "nombre_marca", align: "center" },
      { text: "Proveedor", value: "nombre_proveedor", align: "center" },
      { text: "estatus", value: "nombre_status", align: "center" },
      { text: "Rack", value: "nombre_rack", align: "center" },
      { text: "Travesaño", value: "nombre_travesano", align: "center" },
      { text: "Acciones", value: "actions", sortable: false, align: "center" },
      { text: "", align: "end", value: "data-table-expand" },
    ],

    articulosArray: [],
    //variable en la que se deposita la posicion en el selector
    selectc: "", //categoria
    selectt: "", //tipo
    selectp: "", //proveedor
    selectm: "", //marca
    selectst: "", //status
    selectr: "", //rack
    selectT: "", //travesaño
    //Array en el que se deposita de los selectores.
    itemsc: [], //categoria
    itemstt: [], //tipo
    itemsp: [], //proveedor
    itemstm: [], //marca
    itemstst: [], //status
    itemsr: [], //rack
    itemsT: [], //travesaño
    photo: null,

    editedIndex: -1,
    editedItem: {
      id: "",
      nombre_articulo: "",
      cantidad_articulo: 0,
      nombre_categoria: "",
      nombre_tipo: "",
      nombre_marca: "",
      nombre_proveedor: "",
      nombre_status: "",
      nombre_rack: "",
      nombre_travesano: "",
      foto_articulo: null,
      descripcion_articulo: "",
    },
    defaultItem: {
      id: "",
      nombre_articulo: "",
      cantidad_articulo: 0,
      nombre_categoria: "",
      nombre_tipo: "",
      nombre_marca: "",
      nombre_proveedor: "",
      nombre_status: "",
      nombre_rack: "",
      nombre_travesano: "",
      foto_articulo: null,
      descripcion_articulo: "",
    },
  }),
  created() {
    this.onFocus();
  },
  mounted() {
    window.Echo.channel("articulos").listen("articuloCreated", (e) => {
      this.articulosArray = e.articulos;
    });

    window.Echo.channel("categorias").listen("categoriaCreated", (e) => {
      this.itemsc = e.categorias;
    });
    window.Echo.channel("marcas").listen("marcaCreated", (e) => {
      this.itemstm = e.marcas;
    });
    window.Echo.channel("proveedores").listen("proveedorCreated", (e) => {
      this.itemsp = e.proveedores;
    });
    window.Echo.channel("status").listen("statusCreated", (e) => {
      this.itemstst = e.status;
    });
    window.Echo.channel("tipos").listen("tipoCreated", (e) => {
      this.itemstt = e.tipos;
    });
    window.Echo.channel("racks").listen("rackCreated", (e) => {
      this.itemsr = e.racks;
    });
    window.Echo.channel("travesanos").listen("travesañoCreated", (e) => {
      this.itemsT = e.travesanos;
    });
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
    getCategorias(this.itemsc);
    getMarcas(this.itemstm);
    getProveedores(this.itemsp);
    getTipos(this.itemstt);
    getRack(this.itemsr);
    getTravesano(this.itemsT);
    getStatus(this.itemstst);
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Editar articulo";
    },
    count() {
      return store.getters.counter;
    },
    hasrol() {
      return store.getters.hasrol;
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

  methods: {
    onFocus() {
      let stext = document.getElementById("onsearch");
      stext;
      stext = addEventListener("keyup", (e) => {
        if (e.altKey) {
          document.getElementById("onsearch").focus();
        }
      });
    },
    getColor(status) {
      if (status === "Agotado") return "red lighten-2";
      else if (status === "Disponible") return "orange lighten-2";
      else if (status === "En uso") return "blue lighten-2";
      else return "withe";
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
      this.editedIndex = this.articulosArray.indexOf(item);
      this.editedItem = Object.assign({}, item);
      //categoria
      if (this.editedItem.nombre_categoria) {
        let categoriasync = this.editedItem.nombre_categoria;
        this.selectc = categsync(this.itemsc, this.selectc, categoriasync);
      }
      //tipo
      if (this.editedItem.nombre_tipo) {
        let typosync = this.editedItem.nombre_tipo;
        this.selectt = tiposync(this.itemstt, this.selectt, typosync);
      }
      //proveedor
      let provsync = this.editedItem.nombre_proveedor;
      this.selectp = proveedorsync(this.itemsp, this.selectp, provsync);
      //marca
      let marcsync = this.editedItem.nombre_marca;
      this.selectm = marcasync(this.itemstm, this.selectm, marcsync);

      //status
      let statync = this.editedItem.nombre_status;
      this.selectst = statusync(this.itemstst, this.selectst, statync);
      //rack
      let racsycn = this.editedItem.nombre_rack;
      this.selectr = racksync(this.itemsr, this.selectr, racsycn);
      //travesaño
      let travsync = this.editedItem.nombre_travesano;
      this.selectT = travesañosync(this.itemsT, this.selectT, travsync);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.articulosArray.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },
    detailItem(item) {
      this.editedIndex = this.articulosArray.indexOf(item);
      this.editedItem = Object.assign({}, item);

      this.dialogDetail = true;
    },

    deleteItemConfirm() {
      this.articulosArray.splice(this.editedIndex, 1);
      let id = this.editedItem.id;
      deleteArticulos(id);
      window.Echo.channel("articulos").listen("articuloCreated", (e) => {
        this.articulosArray = e.articulos;
      });
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.dialogDetail = false;
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
    photochange() {
      if (this.editedIndex > -1) {
        Object.assign(this.articulosArray[this.editedIndex], this.editedItem);
        let send = this.editedItem;
        let url = "api/updatephoto/" + send.id;

        if (this.photo != null) {
          const formdata = new FormData();
          formdata.append("foto_articulo", this.photo);
          postPhoto(url, formdata);
        }
      } else {
        this.articulosArray.push(this.editedItem);
      }
      this.close();
    },
    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.articulosArray[this.editedIndex], this.editedItem);
        let send = this.editedItem;
        send.nombre_articulo = upperConverter(send.nombre_articulo);
        let url = "api/articulo/update/" + send.id;

        url = `${url}?${"nombre_articulo=" + send.nombre_articulo}&${
          "cantidad_articulo=" + send.cantidad_articulo
        }&${"descripcion_articulo=" + send.descripcion_articulo}&${
          "categoria_id=" + this.selectc
        }&${"tipo_id=" + this.selectt}&${"marca_id=" + this.selectm}&${
          "proveedor_id=" + this.selectp
        }&${"status_id=" + this.selectst}&${"rack_id=" + this.selectr}&${
          "travesano_id=" + this.selectT
        }`;

        editArticulos(url);
        //Sub sistema de eliminación de photos del articulo

        window.Echo.channel("articulos").listen("articuloCreated", (e) => {
          this.articulosArray = e.articulos;
        });
      } else {
        this.articulosArray.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>

<style scoped>
</style>