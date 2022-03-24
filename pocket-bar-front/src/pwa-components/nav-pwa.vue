<template>
  <nav>
    <v-card>
      <v-toolbar ligth flat>
        <v-text-field
          flat
          hide-details
          prepend-inner-icon="mdi-magnify"
          placeholder="Search"
        ></v-text-field>

        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon>mdi-bell</v-icon>
        </v-btn>

        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn text v-bind="attrs" v-on="on">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item
              v-for="(item, index) in itemss"
              :key="index"
              :to="item.path"
            >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
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
              {{ item.title }}
            </v-tab>
            <!-- <v-btn
              @click="type = 1"
              v-bind:color="type === 1 ? 'primary' : 'normal'"
              style="cursor: pointer; padding-left: 15px; padding-right: 0px"
              text
              >Por entregar</v-btn
            >
            <v-btn
              @click="type = 2"
              v-bind:color="type === 2 ? 'primary' : 'normal'"
              style="cursor: pointer"
              text
              >Entregadas</v-btn
            >
            <v-btn
              @click="type = 3"
              v-bind:color="type === 3 ? 'primary' : 'normal'"
              style="cursor: pointer"
              text
              >Cerradas</v-btn
            > -->
          </v-tabs>
          <v-fab-transition>
            <v-btn small fab dark depressed bottom right>
              <v-icon icon>mdi-plus</v-icon>
            </v-btn>
          </v-fab-transition>
        </template>
      </v-toolbar>
    </v-card>
  </nav>
</template>

<script>
export default {
  name: "navbarPwa",
  data() {
    return {
      itemss: [{ title: "Cerrar sessión", path: "/login" }],
      type: null,
      tabs: null,
      items: [
        { id: 1, title: "Por entregar" },
        { id: 2, title: "Entregadas" },
        { id: 3, title: "Cerradas" },
      ],
    };
  },
};
</script>

<style scoped>
</style>