<template>
 <v-dialog max-width="40rem" :dark="$store.getters.hasdarkflag" persistent v-model="dialogConfirmation">
  <v-card>
   <v-card-title>{{title}}</v-card-title>
   <v-card-text>{{text}}</v-card-text>
   <v-card-actions><v-btn @click="onClose">{{cancel}}</v-btn>
   <v-btn>{{accept}}</v-btn></v-card-actions>
  </v-card>
 </v-dialog>
</template>
<script>
export default {
 name: "modal-confirmation",
 props: {
  dialogConfirmation: {
   type: Boolean,
   default: false,
  }
  ,
  title: {
   type: String,
   default: "Confirmación",
  },
  text: {
   type: String,
   default: "¿Está seguro de realizar esta acción?",
  },
  accept: {
   type: String,
   default: "Aceptar",
  },
  cancel: {
   type: String,
   default: "Cancelar",
  },
 },
 methods: {
  onClose() {
   this.$emit("update:dialogConfirmation", false);
  }
 }
}
</script>
