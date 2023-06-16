<template>
 <v-dialog max-width="40rem" :dark="$store.getters.hasdarkflag" persistent v-model="dialogConfirmation">
  <v-card>
   <v-card-title>{{title}}</v-card-title>
   <v-card-text>
    <v-text-field max-width="80%" clearable :label="placeholdertext"></v-text-field>
   </v-card-text>
   <v-card-actions class="ma-2"><v-btn @click="onClose">{{cancel}}</v-btn>
   <v-spacer></v-spacer>
    <slot name="buttonsuccess"></slot>
   </v-card-actions>
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
  },
  title: {
   type: String,
   default: "Confirmación",
  },
 
   placeholdertext: {
   type: String,
   default: "Dinero en caja",
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
