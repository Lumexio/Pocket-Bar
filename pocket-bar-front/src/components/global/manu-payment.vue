<template>
	<div color="transparent" class="menu-payment-box">
		<v-card class="card-payment" :dark="this.$store.getters.hasdarkflag">
			<v-card-title
				>Cortes y pagos <v-spacer></v-spacer> Ganancia actual: ${{
					data_resp.total_night
				}}</v-card-title
			>
			<v-list flat>
				<v-list-item-group v-model="selectedItem" color="primary" flat>
					<v-list-item v-for="(item, i) in items" :key="i" :to="item.path">
						<v-list-item-icon>
							<v-icon v-text="item.icon"></v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title v-text="item.text"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list-item-group>
			</v-list>
		</v-card>
	</div>
</template>

<script>
import { getCotizado } from "@/api/cortes.js";

export default {
	name: "menuPayment",
	data: () => ({
		data_resp: {},
		selectedItem: 1,

		items: [
			{ text: "Pagar nominas", icon: "mdi-account-cash", path: "/nominas" },
			{ text: "Corte de la noche", icon: "mdi-cash-100", path: "/" },
		],
	}),
	whatch: {},
	methods: {
		getusers() {},
	},
	mounted() {
		getCotizado()
			.then((response) => {
				this.data_resp = response.data;
			})
			.catch((e) => {
				console.log(e);
			});
	},
};
</script>

<style scoped>
.menu-pament-box {
	width: max-content;
	height: max-content;
}
.card-payment {
	max-width: 40rem;
}
</style>