<template>
  <div class="tabla" id="app">
    <v-row>
      <v-col cols="12" sm="6" md="4">
        <v-text-field
          v-model="search"
          label="Buscar usuario"
          placeholder="Nombre, correo y rol"
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
        :items="usersArray"
        class="elevation-1"
        :search="search"
        :custom-filter="filterOnlyCapsText.toUpperCase"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Tabla usuarios</v-toolbar-title>
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
                      <v-col cols="12">
                        <v-text-field
                          v-model="editedItem.name"
                          label="Nombre"
                        ></v-text-field>
                      </v-col>

                      <v-col cols="12">
                        <v-select
                          v-model="selectrol"
                          :items="itemsrol"
                          v-on="usersync()"
                          item-text="name_rol"
                          item-value="rol_id"
                          label="Rol"
                        ></v-select>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field
                          :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                          v-model="editedItem.password"
                          :type="show3 ? 'text' : 'password'"
                          hint="Minimo 8 caracteres"
                          :counter="8"
                          @click:append="show3 = !show3"
                          label="Contraseña"
                          placeholder="Contraseña"
                        >
                        </v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions v-on:keyup.enter="save">
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">
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
                <v-card-actions v-on:keyup.enter="deleteItemConfirm">
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
        <template v-slot:[`item.name_rol`]="{ item }">
          <v-chip :color="getColor(item.name_rol)" dark>
            {{ item.name_rol }}
          </v-chip>
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
      </v-data-table>
    </v-app>
  </div>
</template>

<script>
import axios from "axios";
import store from "@/store";
import { upperConverter } from "@/special/uppercases-converter.js";
//axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";
export default {
  name: "tabla-usuarios",
  data: () => ({
    dialog: false,
    dialogDelete: false,
    search: "",
    password: "",
    cargando: true,
    show3: false,

    headers: [
      {
        text: "Nombre",
        align: "start",
        sortable: false,
        value: "name",
      },

      { text: "Rol", value: "name_rol" },

      { text: "Acciones", value: "actions", sortable: false },
    ],

    usersArray: [],
    //variable en la que se deposita la posicion en el selector
    selectrol: null, //Rol

    //Array en el que se deposita de los selectores.
    itemsrol: [], //Rol

    editedIndex: -1,
    editedItem: {
      id: "",
      name: "",

      password: "",
      name_rol: "",
    },
    defaultItem: {
      id: "",
      name: "",

      password: "",
      name_rol: "",
    },
  }),

  mounted() {
    this.onFocus();
    window.Echo.channel("users").listen("userCreated", (e) => {
      this.usersArray = e.users;
    });
    window.Echo.channel("roles").listen("rolCreated", (e) => {
      this.itemsrol = e.roles;
    });

    axios
      .get("api/user")
      .then((response) => {
        let user = response.data;

        user.forEach((element) => {
          let datos = {
            id: element.id,
            name: element.name,

            name_rol: element.name_rol,
          };
          if (!datos) return;
          this.usersArray.push(datos);
        });
        this.cargando = false;
      })
      .catch((error) => console.log(error));

    axios
      .get("api/rol")
      .then((response) => {
        let categorias = response.data;

        categorias.forEach((element) => {
          let datos = {
            rol_id: element.id,
            name_rol: element.name_rol,
          };

          if (!datos) return;
          this.itemsrol.push(datos);
        });
        this.cargando = false;
      })
      .catch((e) => {
        console.log(e.message);
      });
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Editar usuario";
    },
    progress() {
      return Math.min(100, this.editedItem.password.length * 13);
    },
    color() {
      return ["error", "warning", "success"][Math.floor(this.progress / 40)];
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
    getColor(status) {
      console.log("dato de status:", status);
      if (status == "Administrativo") {
        return "amber lighten-1";
      } else if (status == "Meser@") {
        return "cyan darken-1";
      } else if (status == "Cajer@") {
        return "purple darken-1";
      } else if (status == "Bartender") {
        return "pink darken-1";
      } else if (status == "Intendencia") {
        return "indigo lighten-1";
      }
    },
    filterOnlyCapsText(value, search) {
      return (
        value != null &&
        search != null &&
        typeof value === "string" &&
        value.toString().toLocaleUpperCase().indexOf(search) !== -1
      );
    },
    usersync(recived) {
      var tempid = null;
      var tempname = null;
      tempname;
      if (this.itemsrol) {
        let rol = this.itemsrol;
        rol.forEach((element) => {
          let datos = {
            rol_id: element.rol_id,
            name_rol: element.name_rol,
          };
          if (datos.name_rol === recived) {
            tempid = datos.rol_id;
            tempname = datos.name_rol;

            this.selectrol = tempid;
          }
        });
      }

      return tempid;
    },

    editItem(item) {
      this.editedIndex = this.usersArray.indexOf(item);
      this.editedItem = Object.assign({}, item);

      if (this.editedItem.name_rol) {
        //categoria
        this.usersync(this.editedItem.name_rol);
      }

      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.usersArray.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
      let id = this.editedItem.id;
      axios.delete("api/user/" + id).catch((error) => console.log(error));
    },

    deleteItemConfirm() {
      this.usersArray.splice(this.editedIndex, 1);
      this.closeDelete();
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
        Object.assign(this.usersArray[this.editedIndex], this.editedItem);
        let send = this.editedItem;
        send.name = upperConverter(send.name);
        let url = "api/user/";

        url = url + send.id;
        url = `${url}?${"name=" + send.name}&${"password=" + send.password}&${
          "rol_id=" + this.selectrol
        }`;

        axios
          .put(url)
          .then((response) => {
            response;
            store.commit("increment", 1);
          })
          .catch((error) => console.log(error));
        window.Echo.channel("users").listen("userCreated", (e) => {
          this.usersArray = e.users;
        });
        window.Echo.channel("roles").listen("rolCreated", (e) => {
          this.itemsrol = e.roles;
        });
      } else {
        this.usersArray.push(this.editedItem);
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