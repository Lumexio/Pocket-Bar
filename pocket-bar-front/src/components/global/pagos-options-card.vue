<template>
	<v-card> cerrar </v-card>
</template>

<script>
import { getCotizado } from "@/api/cortes.js";

export default {
	name: "cortes-structure",
	data: () => ({
		items: [
			{ text: "Real-Time", icon: "mdi-clock" },
			{ text: "Audience", icon: "mdi-account" },
		],
	}),
	mounted() {
		getCotizado();
	},
};
</script>

<style scoped>
</style>