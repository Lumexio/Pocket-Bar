<template>
	<v-row class="align-start">
		<mustbeReport :userTickets="userTickets" />
		<menuList :ingresos="ingresos" />
	</v-row>
</template>

<script>
import { getCotizado } from "@/api/cortes.js";
import mustbeReport from "../payments/report.vue";
import menuList from "../payments/menuList.vue";

export default {
	name: "menuPayment",
	components: { mustbeReport, menuList },
	data: () => ({
		ingresos: {},
		userTickets: [],
	}),
	whatch: {},
	methods: {
		getusers() {},
	},
	mounted() {
		getCotizado()
			.then((response) => {
				console.log(response);
				this.ingresos = response.workshift_report.ingresos;
				this.userTickets = response.workshift_report.userTickets;
			})
			.catch((e) => {
				console.log(e);
			});
	},
};
</script>

<style scoped>
</style>