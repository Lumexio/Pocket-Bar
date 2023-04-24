<template>
	<ordenes-meseros :key="count" />
</template>

<script>
import store from "@/store";

import OrdenesMeseros from "../../pwa-components/tables/ordenes-meseros.vue";
export default {
	name: "MainMesero",
	components: { OrdenesMeseros },
	data: () => ({}),

	created() {
		/*para ejecutar lo que yo quiera automaticamente*/
	},
	computed: {
		count() {
			return store.getters.counter;
		},
	},
};
</script>
<style scoped>
</style>