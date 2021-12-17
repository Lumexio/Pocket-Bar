<template>
  <v-dialog
    content-class="elevation-0"
    v-model="dialogarticulo"
    persistent
    max-width="40rem"
  >
    <v-card v-on:keyup.enter="submit()" class="cont-card">
      <v-toolbar light flat>
        <v-btn
          v-shortkey="['esc']"
          icon
          color="dark"
          @shortkey="onClose"
          @click="onClose"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Crear {{ nameat }}</v-toolbar-title>
      </v-toolbar>
      <form>
        <v-row>
          <v-col sm="3" md="6">
            <v-text-field
              :rules="nameRules"
              v-model="name"
              type="text"
              :counter="100"
              label="Nombre artículo"
              required
            ></v-text-field>
          </v-col>
          <v-col sm="3" md="6">
            <v-text-field
              :rules="numberRules"
              v-model="cant"
              type="number"
              :counter="10"
              label="Cantidad artículo"
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row align="center">
          <v-col sm="6" md="12">
            <v-select
              v-model="selectc"
              :items="itemsc"
              item-text="nombre_categoria"
              item-value="id"
              label="Categoría"
            >
            </v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col sm="6" md="6">
            <v-select
              v-model="selectt"
              :items="itemstt"
              item-text="nombre_tipo"
              item-value="id"
              label="Tipo"
            >
            </v-select>
          </v-col>

          <v-col sm="6" md="6">
            <v-select
              v-model="selectp"
              :items="itemsp"
              item-text="nombre_proveedor"
              item-value="id"
              label="Proveedor"
              required
            ></v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col sm="6" md="6">
            <v-select
              v-model="selectm"
              :items="itemstm"
              item-text="nombre_marca"
              item-value="id"
              label="Marca"
              required
            ></v-select>
          </v-col>

          <v-col sm="6" md="6">
            <v-select
              v-model="selectst"
              :items="itemstst"
              item-text="nombre_status"
              item-value="id"
              label="Estatus"
              required
            ></v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col sm="3" md="6">
            <v-select
              v-model="selectr"
              :items="itemsr"
              item-text="nombre_rack"
              item-value="id"
              label="Ubicación rack"
              required
            ></v-select>
          </v-col>
          <v-col sm="3" md="6">
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
        <v-row>
          <v-col>
            <v-file-input
              label="Subir fotografìa"
              v-model="photo"
              prepend-icon="mdi-camera"
            ></v-file-input
          ></v-col>
        </v-row>
        <v-row
          ><v-col>
            <v-textarea
              v-model="descripcion_articulo"
              label="Descrpción"
              type="text"
            ></v-textarea> </v-col
        ></v-row>
      </form>
      <v-card-actions
        ><v-spacer></v-spacer>
        <v-btn color="grey darken-1" @click="clear" outlined> Limpiar </v-btn>
        <v-btn
          color="yellow darken-2"
          class="mr-4"
          v-shortkey="['enter']"
          @shortkey="submit"
          @click="submit"
          outlined
        >
          Guardar {{ nameat }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import axios from "axios";
  import store from "@/store";
  import { postArticulos } from "@/api/articulos.js";
  import { getCategorias } from "@/api/categorias.js";
  import { getMarcas } from "@/api/marcas.js";
  import { getProveedores } from "@/api/proveedores.js";
  import { getRack } from "@/api/racks.js";
  import { getTravesano } from "@/api/travesanos.js";
  import { getTipos } from "@/api/tipos.js";

  import { upperConverter } from "@/special/uppercases-converter.js";

  export default {
    name: "creararticulos",
    props: {
      dialogarticulo: { default: false },
    } /*data de llegado de componente padre creacion*/,
    data: () => ({
      name: "",
      descripcion_articulo: "",
      cant: "",
      nameat: "artículo", //nombre variable en componente
      cargando: false,
      selectc: "", //*categoria
      selectt: "", //*tipo
      selectp: "", //*proveedor
      selectm: "", //*marca
      selectst: "", //*status
      selectr: "", //*rack
      selectT: "", //*travesaño
      photo: null, //*Imagen de articulo

      itemsc: [], //*categoria [array]
      itemstt: [], //*tipo [array]
      itemsp: [], //*proveedor [array]
      itemstm: [], //*marca [array]
      itemstst: [], //*status [array]
      itemsr: [], //*rack [array]
      itemsT: [], //*travesaño [array]
      //Reglas de entradas del formulario
      nameRules: [
        (v) => !!v || "Este campo es requerido",
        (v) =>
          (v && v.length <= 100) ||
          "El Nombre debe tener menos de 100 caracteres",
        (v) => /[A-Z Ñáéíóúü]+/gi.test(v) || "Solo se permiten letras",
      ],
      numberRules: [
        (v) => !!v || "Este campo es requerido",
        (v) => /[0-9]/.test(v) || "Este campo solo acepta numeros",
      ],
    }),
    mounted() {
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
      getCategorias(this.itemsc)
        .then((response) => {
          if (response.stats === 200) {
            this.cargando = false;
          }
        })
        .catch((e) => {
          console.log(e);
          this.cargando = true;
        });
      getMarcas(this.itemstm)
        .then((response) => {
          if (response.stats === 200) {
            this.cargando = false;
          }
        })
        .catch((e) => {
          console.log(e);
          this.cargando = true;
        });
      getProveedores(this.itemsp)
        .then((response) => {
          if (response.stats === 200) {
            this.cargando = false;
          }
        })
        .catch((e) => {
          console.log(e);
          this.cargando = true;
        });
      axios
        .get("api/status")
        .then((response) => {
          let status = response.data;

          status.forEach((element) => {
            let datos = {
              id: element.id,
              nombre_status: element.nombre_status,
            };

            if (!datos) return;
            this.itemstst.push(datos);
          });
        })
        .catch((e) => {
          console.log(e.message);
        });

      getTipos(this.itemstt)
        .then((response) => {
          if (response.stats === 200) {
            this.cargando = false;
          }
        })
        .catch((e) => {
          console.log(e);
          this.cargando = true;
        });
      getRack(this.itemsr)
        .then((response) => {
          if (response.stats === 200) {
            this.cargando = false;
          }
        })
        .catch((e) => {
          console.log(e);
          this.cargando = true;
        });
      getTravesano(this.itemsT)
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
      onClose() {
        /*Envia parametro de cierre a componente creación*/
        this.$emit("update:dialogarticulo", false);
        //this.$emit("dialogFromChild", false);
        //store.commit("increment", 1);
      },
      submit() {
        store.commit("setsuccess", false); //para resetear el valor de la notificion en una nueva entrada
        store.commit("setdanger", false); //para resetear el valor de la notificion en una nueva entrada
        this.name = upperConverter(this.name);
        const formdata = new FormData();
        var tempPhoto = "";
        if (this.photo === null) {
          this.photo = tempPhoto;
        }
        formdata.append("nombre_articulo", this.name);
        formdata.append("cantidad_articulo", this.cant);
        formdata.append("categoria_id", this.selectc);
        formdata.append("proveedor_id", this.selectp);
        formdata.append("tipo_id", this.selectt);
        formdata.append("status_id", this.selectst);
        formdata.append("marca_id", this.selectm);
        formdata.append("rack_id", this.selectr);
        formdata.append("travesano_id", this.selectT);
        formdata.append("descripcion_articulo", this.descripcion_articulo);
        formdata.append("foto_articulo", this.photo);
        // const enviar = {
        //   nombre_articulo: this.name,
        //   cantidad_articulo: this.cant,
        //   categoria_id: this.selectc,
        //   proveedor_id: this.selectp,
        //   tipo_id: this.selectt,
        //   status_id: this.selectst,
        //   marca_id: this.selectm,
        //   rack_id: this.selectr,
        //   travesano_id: this.selectT,
        //   foto_articulo: this.photo,
        // };

        postArticulos(formdata);
        this.clear();
      },
      clear() {
        (this.name = ""),
          (this.cant = ""),
          (this.selectc = ""),
          (this.selectp = ""),
          (this.selectt = ""),
          (this.selectst = ""),
          (this.selectr = ""),
          (this.selectT = ""),
          (this.selectm = ""),
          (this.photo = null);
      },
    },
  };
</script>
<style scoped>
  .cont-card {
    padding: 1rem;
  }
</style>