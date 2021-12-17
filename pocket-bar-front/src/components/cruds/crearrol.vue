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
        <v-toolbar-title>Crear rol</v-toolbar-title>
      </v-toolbar>
      <v-row justify-sm="center">
        <v-col sm="6" md="12" lx="13">
          <v-text-field
            v-model="name_rol"
            :counter="10"
            label="Nombre rol"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-sapacer></v-sapacer>
      <v-btn @click="clear" outlined> Limpiar </v-btn>
      <v-btn
        class="mr-4"
        v-shortkey="['enter']"
        @shortkey="submit"
        v-on:click="submit"
        outlined
      >
        Guardar
      </v-btn>
    </v-card>
  </v-dialog>
</template>

<script>
  import axios from "axios";
  import store from "@/store";
  axios.defaults.withCredentials = true;
  axios.defaults.baseURL = "http://127.0.0.1:8000/";
  export default {
    name: "crearrol",
    props: {
      parentdialog: { type: Boolean },
    } /*data de llegado de componente padre creacion*/,
    data: () => ({
      name_rol: "",
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

        const formdata = new FormData();
        formdata.append("name_rol", this.name_rol);
        axios
          .post("api/rol", formdata)
          .then((response) => {
            if (response.statusText === "Created") {
              store.commit("setsuccess", true);
            }
          })
          .catch((e) => {
            console.log(e.message);
            store.commit("setdanger", true);
          });
      },
      clear() {
        this.name_rol = "";
      },
    },
  };
</script>

<style scoped>
  .cont-card {
    padding: 1rem;
  }
</style>