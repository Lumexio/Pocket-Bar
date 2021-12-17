<template>
  <v-dialog
    content-class="elevation-0"
    v-model="dialogcategoria"
    max-width="40rem"
    persistent
  >
    <v-card v-on:keyup.enter="submit()" class="cont-card">
      <v-toolbar light flat>
        <v-btn
          v-shortkey="['esc']"
          icon
          color="dark"
          @shortkey="onClose()"
          @click="onClose()"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Crear categorías</v-toolbar-title>
      </v-toolbar>
      <v-row
        ><v-col>
          <v-text-field
            v-model="nombre_categoria"
            :counter="10"
            label="Nombre categoría"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-textarea
            v-model="descripcion_categoria"
            :counter="120"
            color="teal"
          >
            <template v-slot:label>
              <div>Descripción categoría <small>(opcional)</small></div>
            </template>
          </v-textarea>
        </v-col>
      </v-row>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="grey darken-2" @click="clear" outlined> Limpiar </v-btn>
        <v-btn
          color="yellow darken-2"
          class="mr-4"
          v-shortkey="['enter']"
          @shortkey="submit"
          @click="submit"
          outlined
        >
          Guardar categoria
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from "@/store";
  import { postCategorias } from "@/api/categorias.js";
  import { upperConverter } from "@/special/uppercases-converter.js";
  export default {
    name: "crearcategoria",
    props: {
      dialogcategoria: { dafault: false },
    } /*data de llegado de componente padre creacion*/,
    data: () => ({
      nombre_categoria: "",
      descripcion_categoria: "",
    }),

    methods: {
      onClose() {
        /*Envia parametro de cierre a componente creación*/
        this.$emit("update:dialogcategoria", false);
      },
      submit() {
        store.commit("setsuccess", false);
        store.commit("setdanger", false);
        this.nombre_categoria = upperConverter(this.nombre_categoria);
        const formdata = new FormData();
        formdata.append("nombre_categoria", this.nombre_categoria);
        formdata.append("descripcion_categoria", this.descripcion_categoria);
        postCategorias(formdata);
        this.clear();
      },
      clear() {
        (this.nombre_categoria = ""), (this.descripcion_categoria = "");
      },
    },
  };
</script>

<style scoped>
  .cont-card {
    padding: 1rem;
  }
</style>