<template>
  <div class="logcard">
    <div id="app">
      <v-app id="inspire">
        <v-card v-on:keyup.enter="login()" class="cont-card" elevation="2">
          <v-card-title class="fade-in-title" style="font-size: 3rem"
            ><code class="font-weight-light">Pocket</code
            ><strong>bar</strong></v-card-title
          >
          <v-text-field
            v-model="email"
            label="correo"
            :error-messages="emailErrors"
            required
            @input="$v.email.$touch()"
            @blur="$v.email.$touch()"
          ></v-text-field>
          <v-text-field
            :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
            v-model="password"
            :type="show3 ? 'text' : 'password'"
            class="input-group--focused"
            :counter="8"
            label="contraseña"
            :error-messages="passwordErrors"
            required
            @click:append="show3 = !show3"
            @input="$v.password.$touch()"
            @blur="$v.password.$touch()"
            loading
          >
            <template v-slot:progress>
              <v-progress-linear
                :value="progress"
                :color="color"
                absolute
                height="2"
              ></v-progress-linear>
            </template>
          </v-text-field>
          <v-btn class="mr-4" v-on:click="login()" text> Iniciar sesión </v-btn>
          <v-btn @click="clear" text> limpiar </v-btn>
        </v-card>
      </v-app>
    </div>
  </div>
</template>

<script>
const { required, email, minLength } = require("vuelidate/lib/validators");
import axios from "axios";

import store from "@/store";
import router from "@/router";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";
export default {
  email: "crearusuario",
  data: () => ({
    email: "", //a@a.com//b@b.com
    password: "", //12345678
    show3: false,
  }),

  validations: {
    email: { required, email },
    password: { required, minLength: minLength(8) },
  },
  computed: {
    passwordErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.minLength &&
        errors.push("La contraseña debe ser 8 caracteres de largo, minimo.");
      !this.$v.password.required && errors.push("Contaseña requerida.");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.email && errors.push("Debe ingresar un correo valido");
      !this.$v.email.required && errors.push("Ingresar un correo es requerido");
      return errors;
    },
    progress() {
      return Math.min(100, this.password.length * 13);
    },
    color() {
      return ["error", "warning", "success"][Math.floor(this.progress / 40)];
    },
  },

  methods: {
    async login() {
      this.$v.$touch();
      let enviar = {
        email: this.email,
        password: this.password,
      };
      axios
        .get("sanctum/csrf-cookie")
        .then((response) => {
          response;
          axios
            .post("api/login", enviar)
            .then((response) => {
              let rol = response.data.user.rol_id;
              store.commit("setrol", rol);

              let validado = response.request.withCredentials;
              if (validado == true) {
                store.state.token = response.data.token;
                let token = store.state.token;
                store.dispatch("login", { token });
                if (rol === 1) {
                  router.push("/usuarios").catch(() => {});
                } else if (rol === 2) {
                  router.push("/articulos").catch(() => {});
                }
              } else if (validado == false) {
                alert("Cuanta no existe o es incorrecta");
              }
            })
            .catch((e) => {
              console.log(e);
            });
        })
        .catch((e) => {
          console.log(e);
        });
    },

    clear() {
      this.email = "";
      this.password = "";
    },
  },
};
</script>

<style scoped>
.logcard {
  display: flex;
  align-items: center;
  justify-content: center;
  padding-top: 15rem;
}

.cont-card {
  padding: 1rem;
  width: 24em;
}

.fade-in-title {
  animation: fadeIn 5s;
  -webkit-animation: fadeIn 5s;
  -moz-animation: fadeIn 5s;
  -o-animation: fadeIn 5s;
  -ms-animation: fadeIn 5s;
}
@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

@-moz-keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

@-webkit-keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

@-o-keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

@-ms-keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>