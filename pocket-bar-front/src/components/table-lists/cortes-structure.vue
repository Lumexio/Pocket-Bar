<template>
	<div><p>aqui va el desgloce del total de la noche</p></div>
</template>

<script>
import { getCotizado } from "@/api/cortes.js";

export default {
	name: "cortes-structure",
	data: () => ({}),
	mounted() {
		getCotizado();
	},
};
</script>

<style scoped>
</style>