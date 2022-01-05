<template>
  <v-dialog
    content-class="elevation-0"
    v-model="dialogusuarios"
    max-width="25rem"
    persistent
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
        <v-toolbar-title>Crear usuario</v-toolbar-title>
      </v-toolbar>
      <v-row
        ><v-col sm="6" md="12" lx="13">
          <v-text-field
            v-model="name"
            :counter="10"
            label="Nombre"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <!-- <v-row
        ><v-col sm="6" md="12" lx="13">
          <v-text-field v-model="email" label="Correo" required></v-text-field>
        </v-col>
      </v-row> -->
      <v-row
        ><v-col sm="6" md="12" lx="13">
          <v-text-field
            v-model="password"
            :counter="8"
            :type="'password'"
            label="Contraseña"
            required
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row align="center">
        <v-col sm="6" md="12" lx="13">
          <v-select
            v-model="selectrol"
            :items="itemsrol"
            item-text="name_rol"
            item-value="rol_id"
            label="Rol"
          >
          </v-select>
        </v-col>
      </v-row>
      <v-card-actions>
        <v-btn class="mr-4" @click="submit" text> Guardar </v-btn>
        <v-btn @click="clear" text> Limpiar </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";
export default {
  name: "crearusuario",
  props: {
    dialogusuarios: { dafault: false },
  } /*data de llegado de componente padre creacion*/,
  data: () => ({
    name: "",
    email: "",
    password: "",

    selectrol: null,
    itemsrol: [],
  }),
  mounted() {
    window.Echo.channel("roles").listen("rolCreated", (e) => {
      this.itemsrol = e.roles;
    });
    axios
      .get("api/rol")
      .then((response) => {
        let rol = response.data;

        rol.forEach((element) => {
          let datos = {
            rol_id: element.id,
            name_rol: element.name_rol,
          };

          if (!datos) return;
          this.itemsrol.push(datos);
        });
      })
      .catch((e) => {
        console.log(e.message);
      });
  },
  methods: {
    onClose() {
      /*Envia parametro de cierre a componente creación*/
      this.$emit("update:dialogusuarios", false);
    },
    submit() {
      //this.$emit("dialogFromChild", false);
      store.commit("setsuccess", false); //para resetear el valor de la notificion en una nueva entrada
      store.commit("setdanger", false);
      let enviar = {
        name: this.name,
        email: this.email,
        password: this.password,
        rol_id: this.selectrol,
      };

      axios
        .post("api/user", enviar)
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
      (this.name = ""), (this.email = ""), (this.password = "");
    },
  },
};
</script>

<style scoped>
.cont-card {
  padding: 1rem;
}
</style>