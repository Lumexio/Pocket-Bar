<template>
  <v-dialog
    content-class="elevation-0"
    v-model="dialograck"
    max-width="20rem"
    persistent
  >
    <v-card class="cont-card" v-on:keyup.enter="submit()" elevation="1">
      <v-toolbar light flat>
        <v-btn
          v-shortkey="['esc']"
          icon
          color="dark"
          @shortkey="onClose"
          @click="onClose"
        >
          <v-icon> mdi-close </v-icon>
        </v-btn>
        <v-toolbar-title>Crear rack</v-toolbar-title>
      </v-toolbar>
      <v-row>
        <v-col sm="6" md="12" lx="13">
          <v-text-field
            v-model="nombre_rack"
            :counter="10"
            label="Rack"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-spacer></v-spacer>
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
          Guardar rack
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import store from "@/store";
  import { postRack } from "@/api/racks.js";

  export default {
    name: "crearrack",
    props: {
      dialograck: { dafault: false },
    } /*data de llegado de componente padre creacion*/,
    data: () => ({
      nombre_rack: "",
    }),

    methods: {
      onClose() {
        /*Envia parametro de cierre a componente creación*/
        this.$emit("update:dialograck", false);
      },
      submit() {
        store.commit("setsuccess", false); //para resetear el valor de la notificion en una nueva entrada
        store.commit("setdanger", false);
        const formdata = new FormData();
        formdata.append("nombre_rack", this.nombre_rack);
        postRack(formdata);
        this.clear();
      },
      clear() {
        this.nombre_rack = "";
      },
    },
  };
</script>

<style scoped>
  .cont-card {
    padding: 1rem;
  }
</style>