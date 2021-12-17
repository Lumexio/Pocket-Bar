<template>
  <v-dialog
    content-class="elevation-0"
    v-model="dialogproveedor"
    max-width="35rem"
    persistent
  >
    <v-card v-on:keyup.enter="submit()" class="cont-card" elevation="2">
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
        <v-toolbar-title>Crear proveedor</v-toolbar-title>
      </v-toolbar>
      <v-row>
        <v-col>
          <v-text-field
            v-model="nombre_proveedor"
            :counter="10"
            label="Nombre proveedor"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row
        ><v-col>
          <v-textarea
            v-model="descripcion"
            label="Descrpción"
            type="text"
          ></v-textarea> </v-col
      ></v-row>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="grey darken-2" @click="clear" outlined> Limpiar </v-btn>
        <v-btn
          color="yellow darken-2"
          class="mr-4"
          v-shortkey="['enter']"
          @shortkey="submit"
          v-on:click="submit"
          outlined
        >
          Guardar proveedor
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from "@/store";
  import { postProveedores } from "@/api/proveedores.js";
  import { upperConverter } from "@/special/uppercases-converter.js";
  export default {
    name: "crearproveedor",
    props: {
      dialogproveedor: { type: Boolean },
    } /*data de llegado de componente padre creacion*/,
    data: () => ({
      nombre_proveedor: "",
      descripcion: "",
    }),

    methods: {
      onClose() {
        /*Envia parametro de cierre a componente creación*/
        this.$emit("update:dialogproveedor", false);
      },
      submit() {
        //this.$emit("dialogFromChild", false);
        store.commit("setsuccess", false); //para resetear el valor de la notificion en una nueva entrada
        store.commit("setdanger", false);
        let enviar = {
          nombre_proveedor: this.nombre_proveedor,
          descripcion: this.descripcion,
        };
        const formdata = new FormData();
        formdata.append("nombre_proveedor", this.nombre_proveedor);
        formdata.append("descripcion", this.descripcion);
        enviar.nombre_proveedor = upperConverter(this.nombre_proveedor);

        postProveedores(formdata);
        this.clear();
      },
      clear() {
        (this.nombre_proveedor = ""), (this.descripcion = "");
      },
    },
  };
</script>

<style scoped>
  .cont-card {
    padding: 1rem;
  }
</style>