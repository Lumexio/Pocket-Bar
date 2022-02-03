<template>
  <v-navigation-drawer permanent app>
    <v-list nav expand dense>
      <v-list-item-title style="font-size: 20px" class="text-uppercase">
        <code class="font-weight-light">Pocket</code><strong>stock</strong>
        <v-divider></v-divider>
      </v-list-item-title>
      <v-list-item-group color="primary">
        <v-list v-if="hasrol === 1" flat>
          <v-list-item
            v-for="item in itemsmain"
            :key="item.title"
            link
            :to="item.path"
            v-shortkey="{
              usuarios: ['ctrl', 'u'],
              articulos: ['ctrl', 'a'],
              historial: ['ctrl', 'h'],
            }"
            @shortkey="paths"
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
        <v-list v-else-if="hasrol === 2" flat>
          <v-list-item
            v-for="item in itemsemp"
            :key="item.title"
            link
            :to="item.path"
            v-shortkey="{
              articulos: ['ctrl', 'a'],
            }"
            @shortkey="paths"
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-list-item-group>

      <v-list-group :value="true" no-action sub-group>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title>Catálogos </v-list-item-title>
          </v-list-item-content>
        </template>
        <v-list-item
          v-for="item in itemstable"
          :key="item.title"
          link
          flat
          :to="item.path"
          v-shortkey="{
            categorias: ['ctrl', 'c'],
            marcas: ['ctrl', 'm'],
            proveedores: ['ctrl', 'p'],
            tipos: ['ctrl', 't'],
          }"
          @shortkey="paths"
        >
          <v-list-item-title>{{ item.title }}</v-list-item-title>

          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>
        </v-list-item>
      </v-list-group>
      <v-list-group :value="true" no-action sub-group>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title>Ubicaciones </v-list-item-title>
          </v-list-item-content>
        </template>
        <v-list-item
          v-for="item in itemlocation"
          :key="item.title"
          link
          flat
          :to="item.path"
          v-shortkey="{
            travesano: ['ctrl', 'e'],
            racks: ['ctrl', 'r'],
          }"
          @shortkey="paths"
        >
          <v-list-item-title>{{ item.title }}</v-list-item-title>

          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>
        </v-list-item>
      </v-list-group>
    </v-list>
    <v-divider></v-divider>
    <template v-slot:append>
      <div class="pa-2">
        <v-btn dark rounded class="mr-6" v-on:click="logout()" elevation="0">
          <v-icon left>mdi-logout</v-icon> Cerrar sesión
        </v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>
<script>
import store from "@/store.js";
import router from "@/router";
export default {
  name: "sidebar",
  components: {},
  data: () => ({
    itemsmain: [
      //{ path: "/home", title: "Home", icon: "mdi-home" },
      { path: "/usuarios", title: "Usuarios", icon: "mdi-account-multiple" },
      {
        path: "/articulos",
        title: "Artículos",
        icon: "mdi-folder-multiple",
      },
      {
        path: "/historial",
        title: "Historial",
        icon: "mdi-folder-multiple",
      },
    ],
    itemsemp: [
      {
        path: "/articulos",
        title: "Artículos",
        icon: "mdi-folder-multiple",
      },
    ],
    itemstable: [
      {
        path: "/categorias",
        title: "Categorias",
        icon: "mdi-folder-multiple",
      },
      {
        path: "/marcas",
        title: "Marcas",
        icon: "mdi-folder-multiple",
      },
      {
        path: "/tipos",
        title: "Tipos",
        icon: "mdi-folder-multiple",
      },
      {
        path: "/proveedores",
        title: "Proveedores",
        icon: "mdi-folder-multiple",
      },
    ],
    itemlocation: [
      {
        path: "/racks",
        title: "Racks",
        icon: "mdi-folder-multiple",
      },
      {
        path: "/travesano",
        title: "Travesaños",
        icon: "mdi-folder-multiple",
      },
    ],
  }),
  computed: {
    hasrol() {
      return store.getters.hasrol;
    },
  },
  methods: {
    paths(event) {
      switch (event.srcKey) {
        case "usuarios":
          router.push("/usuarios").catch(() => {});
          break;
        case "articulos":
          router.push("/articulos").catch(() => {});
          break;
        case "historial":
          router.push("/historial").catch(() => {});
          break;
        case "categorias":
          router.push("/categorias").catch(() => {});
          break;
        case "marcas":
          router.push("/marcas").catch(() => {});
          break;
        case "tipos":
          router.push("/tipos").catch(() => {});
          break;
        case "proveedores":
          router.push("/proveedores").catch(() => {});
          break;
        case "racks":
          router.push("/racks").catch(() => {});
          break;
        case "travesano":
          router.push("/travesano").catch(() => {});
          break;
        default:
          break;
      }
    },
    logout() {
      let commit = (store.state.token = null);

      this.$store.dispatch("logout", commit);
      this.$router.push("/");
    },
  },
};
</script>
<style lang="scss">
#app {
  font-family: Verdana, Tahoma, sans-serif, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #396796;
}

#nav {
  padding: 30px;

  a {
    color: #2c3e50;

    &.router-link-exact-active {
      color: #ffffff;
    }
  }
}
</style>
