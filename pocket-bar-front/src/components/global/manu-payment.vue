<template>
  <div color="transparent" class="menu-payment-box">
   <v-card :dark="this.$store.getters.hasdarkflag">
   <v-card-title>Cortes y pagos   <v-spacer></v-spacer>  Ganancia actual: $</v-card-title>
  </v-card>
  </div>
</template>

<script>
import { getCotizado } from "@/api/cortes.js";
import { getUsuarios } from "@/api/usuarios.js";
export default {
	name: "menuPayment",
	data: () => ({

	}),
	methods: {
		getusers() {},
	},
	mounted() {
		getUsuarios(this.usersArray)
			.then((response) => {
				response.usersArray;
			})
			.catch((e) => {
				console.log(e);
			});
		getCotizado();
	},
};
</script>

<style scoped>
.menu-pament-box{
 width: max-content;
 height: max-content;
}
</style>