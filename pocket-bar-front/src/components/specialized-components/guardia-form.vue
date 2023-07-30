<template>
  <v-card>
   <v-card-title>Cobrar entrada</v-card-title>
   <v-card-text>
    <v-text-field
						label="Cover"
            prepend-icon="mdi-cash"
						required
					></v-text-field>
   </v-card-text>
   <v-card-actions><v-btn>Cancelar</v-btn><v-btn>Aceptar</v-btn></v-card-actions>
  </v-card>
</template>

<script>
export default {
name:"guardia-form"
}
</script>

<style>

</style>