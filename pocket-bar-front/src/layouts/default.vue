<template>
  <v-app
    :class="[
      this.$store.getters.hasdarkflag === true ? 'black-main' : 'white-main',
    ]"
  >
    <sidebar />
    <v-main class="background">
      <v-container
        fluid
        :class="[
          this.$store.getters.hasdarkflag === true
            ? 'black-main'
            : 'white-main',
        ]"
      >
        <router-view /><!--Renderisa cualquier vista que este activa-->
      </v-container>
    </v-main>
  </v-app>
</template>
 
<script>
import sidebar from "@/components/global/sidebar.vue";
export default {
  name: "defaultLayout", // you can enter any name (optional)
  components: {
    sidebar,
  },
};
</script>
 
<style scoped>
/* your style */
.background {
  z-index: 0;
}
.background:before {
  content: "";
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  height: 0%;
  z-index: -1;
  display: flex;
  flex-direction: column;
  background-color: #ffffff;
  background-position: top left;
  background-repeat: repeat;
  background-size: auto;
  background-attachment: auto;
  border-bottom: 1px solid #eaeaeb;
}
.black-main {
  background-color: #272727;
  min-height: 100vh;
}
.white-main {
  background-color: #fff;
  min-height: 100vh;
}
</style>