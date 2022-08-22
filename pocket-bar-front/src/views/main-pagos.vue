<template>
	<div class="row">
		<pagosoptionscard />
	</div>
</template>

<script>
import pagosoptionscard from "../components/global/pagos-options-card.vue";

export default {
	name: "main-pagos",
	components: { pagosoptionscard },
};
</script>

<style scoped>
.row {
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>