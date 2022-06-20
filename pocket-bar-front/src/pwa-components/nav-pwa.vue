<template>
  <nav>
    <div class="title-user">
      <v-card-title class="fade-in-title" style="font-size: 2rem"
        ><code class="font-weight-light">Pocket</code
        ><strong>bar</strong></v-card-title
      >
      <v-chip class="ml-6" color="pink" label outlined>{{ typeUser }}</v-chip>
    </div>
    <v-toolbar ligth flat>
      <v-text-field
        flat
        hide-details
        prepend-inner-icon="mdi-magnify"
        placeholder="Search"
      ></v-text-field>

      <v-spacer></v-spacer>

      <v-btn @click="dialoglistorden = true" icon>
        <v-icon>mdi-bell</v-icon>
      </v-btn>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on">
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item v-for="item in itemss" :key="item.id" :to="item.path">
            <v-list-item-title
              v-on="hasstatus == item.status"
              @click="clear()"
              >{{ item.title }}</v-list-item-title
            >
          </v-list-item>
        </v-list>
      </v-menu>
      <template v-slot:extension>
        <v-tabs
          style="padding-right: 0px; width: 1%"
          next-icon="mdi-arrow-right-bold-box-outline"
          prev-icon="mdi-arrow-left-bold-box-outline"
          show-arrows
          v-model="tabs"
          grow
          left
        >
          <v-tab style="padding: 2px" v-for="item in items" :key="item.id">
            <v-btn @click="statuschange(item.status)" text>
              {{ item.title }}</v-btn
            >
          </v-tab>
        </v-tabs>
        <v-fab-transition>
          <v-btn
            small
            fab
            dark
            depressed
            bottom
            right
            @click="dialogorden = true"
          >
            <v-icon icon>mdi-plus</v-icon>
          </v-btn>
        </v-fab-transition>
      </template>
    </v-toolbar>

    <tomarorden :dialogorden.sync="dialogorden" />
    <notiorden :dialoglistorden.sync="dialoglistorden" />
  </nav>
</template>

<script>
import tomarorden from "@/pwa-components/forms/crear-orden.vue";
import notiorden from "@/pwa-components/tables/lista-notificaciones-ordenes.vue";
import store from "@/store";
export default {
  name: "navbarPwa",
  components: {
    tomarorden,
    notiorden,
  },
  data() {
    return {
      itemss: [{ title: "Cerrar sessión", path: "/login" }],
      type: null,
      tabs: null,
      dialogorden: false,
      dialoglistorden: false,
      items: [
        { id: 1, title: "Por entregar", status: "Por entregar" },
        { id: 2, title: "Entregadas", status: "Entregado" },
        { id: 3, title: "Cerradas", status: "Cerrado" },
      ],
    };
  },
  methods: {
    clear() {
      store.commit("RESET");
      store.commit("SET_TOKEN", null);
    },
    statuschange(title) {
      store.commit("setstatus", title);
      store.commit("increment", 1);
    },
  },
  computed: {
    typeUser() {
      var rol = "";
      rol;
      store.getters.hasrol;

      console.log(store.getters.hasrol);
      switch (store.getters.hasrol) {
        case 4:
          rol = "Meser@";
          break;
        case 5:
          rol = "Bartender";
          break;

        default:
          break;
      }
      return rol;
    },
    hasstatus() {
      return store.getters.hasstatus;
    },
  },
};
</script>

<style scoped>
.title-user {
  display: flex;
  justify-content: flex-start;
  align-items: center;
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