<template>
  <v-dialog
    content-class="elevation-0"
    v-model="parentdialog"
    max-width="20rem"
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
        <v-toolbar-title>Crear estatus</v-toolbar-title>
      </v-toolbar>
      <v-row justify-sm="center">
        <v-col md="10rem">
          <v-text-field
            v-model="name"
            :counter="10"
            label="Nombre estatus"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-btn
        class="mr-4"
        v-shortkey="['enter']"
        @shortkey="submit"
        v-on:click="submit"
        text
      >
        Guardar
      </v-btn>
      <v-btn @click="clear" text> Limpiar </v-btn>
    </v-card>
  </v-dialog>
</template>

<script>
  import axios from "axios";
  import store from "@/store";
  axios.defaults.withCredentials = true;
  axios.defaults.baseURL = "http://127.0.0.1:8000/";
  export default {
    name: "crearstatus",
    props: {
      parentdialog: { type: Boolean },
    } /*data de llegado de componente padre creacion*/,
    data: () => ({
      name: "",
    }),

    methods: {
      onClose() {
        /*Envia parametro de cierre a componente creación*/
        this.$emit("dialogFromChild", false);
      },
      submit() {
        //this.$emit("dialogFromChild", false);
        store.commit("setsuccess", false); //para resetear el valor de la notificion en una nueva entrada
        store.commit("setdanger", false);
        let enviar = {
          nombre_status: this.name,
        };

        axios
          .post("api/status", enviar)
          .then((response) => {
            if (response.statusText === "Created") {
              this.name = "";
              store.commit("setsuccess", true);
            }
          })
          .catch((e) => {
            console.log(e.message);
            store.commit("setdanger", true);
          });
      },
      clear() {
        this.name = "";
      },
    },
  };
</script>

<style scoped>
  .cont-card {
    padding: 1rem;
  }
</style>