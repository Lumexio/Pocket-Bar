<template>
	<v-col cols="12" sm="12" md="3" lg="4">
		<v-card :dark="this.$store.getters.hasdarkflag">
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
	</v-col>
</template>

<script>
export default {
	name: "menuList",
	props: {
		data_resp: {
			type: Object,
			default: () => {},
		},
	},
	data: () => ({
		selectedItem: 1,

		items: [
			{ text: "Pagar nominas", icon: "mdi-account-cash", path: "/nominas" },
			{ text: "Corte de la noche", icon: "mdi-cash-100", path: "/" },
		],
	}),
};
</script>

<style>
</style>