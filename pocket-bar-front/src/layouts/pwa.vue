<template>
	<v-app
		id="inspire"
		:class="[$store.getters.hasdarkflag === true ? 'black-main' : 'white-main']"
	>
		<navbar />

		<router-view /><!--Renderisa cualquier vista que este activa-->

		<v-btn
			:dark="$store.getters.hasdarkflag === true"
			class="mb-10 xem"
			fab
			x-large
			fixed
			bottom
			right
			@click="dialogorden = true"
			><v-icon>mdi-plus</v-icon></v-btn
		>

		<tomarorden :dialogorden.sync="dialogorden" />
	</v-app>
</template>

<script>
import tomarorden from "@/pwa-components/forms/crear-orden.vue";
import navbar from "@/pwa-components/nav-pwa.vue";
//import ButtonCreate from "../pwa-components/button-create.vue";
export default {
	name: "PWALayout", // you can enter any name (optional)
	components: {
		tomarorden,
		navbar,
		//ButtonCreate,
	},
	data: () => ({
		dialogorden: false,
	}),
	computed: {
		darkflag() {
			return this.$store.getters.hasdarkflag;
		},
	},
};
</script>

<style scoped>
/* This is for documentation purposes and will not be needed in your application */
/* .fullWidth {
	width: 100vw;
	min-height: 100vh;
	display: flex;
} */
.black-main {
	background-color: #272727 !important;
	min-height: 100vh;
}
.white-main {
	background-color: #fff !important;
	min-height: 100vh;
}
.xem {
	z-index: 1;
}
/* your style */
</style>