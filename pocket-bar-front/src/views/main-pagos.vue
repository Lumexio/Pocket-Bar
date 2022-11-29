<template>
	
		<manuPayment></manuPayment>
	
</template>

<script>
//import pagosoptionscard from "../components/global/pagos-options-card.vue";
import manuPayment from '../components/global/manu-payment.vue';
export default {
	name: "main-pagos",
	components: { manuPayment },
};
</script>

<style scoped>
.row {
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>