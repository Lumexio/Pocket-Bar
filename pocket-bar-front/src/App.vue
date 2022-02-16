<template>
  <div id="app">
    <v-snackbar
      :value="hassuccess"
      :timeout="timeout"
      color="#C0CA33"
      rounded="pill"
      top
      ><v-row
        ><b class="mt-2">¡Operación exitosa!</b> <v-spacer></v-spacer
        ><v-icon large>mdi-checkbox-marked-circle</v-icon></v-row
      >
    </v-snackbar>
    <v-snackbar
      :value="hasdanger"
      :timeout="timeout"
      color="#F4511E"
      rounded="pill"
      top
      ><v-row>
        <b class="mt-2">¡Oops hubo un problema!</b> <v-spacer></v-spacer
        ><v-icon large>mdi-alert</v-icon></v-row
      >
    </v-snackbar>
    <vue-extend-layouts />
  </div>
</template>
<script>
import VueExtendLayouts from "vue-extend-layout";
import store from "@/store";
export default {
  name: "App",
  components: {
    VueExtendLayouts,
  },
  data: () => ({
    timeout: 2000,
  }),
  computed: {
    hassuccess() {
      return store.getters.hassuccess;
    },
    hasdanger() {
      return store.getters.hasdanger;
    },
  },
  created() {
    /*para ejecutar lo que yo quiera automaticamente*/
  },
  mounted() {},
  methods: {},
};
</script>
<style >
.v-application--wrap {
  min-height: 0vh !important;
}

p {
  text-transform: capitalize;
  font-size: 14px;
}
.v-data-table > .v-data-table__wrapper > table > tbody > tr > td,
.v-data-table > .v-data-table__wrapper > table > thead > tr > td,
.v-data-table > .v-data-table__wrapper > table > tfoot > tr > td {
  vertical-align: inherit;
}

span {
  text-transform: capitalize;
  font-size: 14px;
}
.v-data-table__expanded__content {
  box-shadow: none !important;
  border-top-style: dotted;
  border-width: 2px 20px;
  border-color: grey;
  text-align: start;
}
#app {
  font-family: Cascadio Code, system-ui, -apple-system, BlinkMacSystemFont,
    Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
    Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #333333;
  height: 100%;
}
/* Scroller classes */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: #eeeeee;
  /* border-radius: 5px; */
}
::-webkit-scrollbar-thumb {
  background: #bdbdbd;
  /* border-radius: 5px; */
  transition: background 250ms ease-out;
}
::-webkit-scrollbar-thumb:hover {
  background: #bdbdbd;
}
</style>
