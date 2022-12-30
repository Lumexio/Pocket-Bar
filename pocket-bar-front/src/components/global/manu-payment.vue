<template>
	<v-row class="align-start">
		<mustbeReport :workshift_report="workshift_report" />
		<menuList :data_resp="data_resp" />
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
		data_resp: {},
		workshift_report: [],
	}),
	whatch: {},
	methods: {
		getusers() {},
	},
	mounted() {
		getCotizado()
			.then((response) => {
				this.data_resp = response.data;
				this.workshift_report = response.workshift_report;
				console.log(this.workshift_report);
			})
			.catch((e) => {
				console.log(e);
			});
	},
};
</script>

<style scoped>
</style>