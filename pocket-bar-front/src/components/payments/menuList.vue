<template>
	<v-col cols="12" sm="12" md="3" lg="4">
		<v-card :dark="this.$store.getters.hasdarkflag">
			<v-card-title
				>Cortes y pagos <v-spacer></v-spacer> Ganancia actual: ${{
					ingresos.total
				}}</v-card-title
			>
			<v-card-text>
				<v-row>
					<v-col cols="12" sm="12" md="6" lg="6">
						<v-btn @click.prevent="dialogConfirmation = true" color="purple darken-4">Inciar turno</v-btn>
					</v-col>
					<v-col cols="12" sm="12" md="6" lg="6">
						<v-btn color="pink darken-4">Terminar turno</v-btn>
					</v-col>
				</v-row>
			</v-card-text>
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
	<modalConfirmation :dialogConfirmation.sync="dialogConfirmation" />
	</v-col>
</template>

<script>
import modalConfirmation from '../global/modal-confirmation.vue';

export default {
	name: "menuList",
	components: {
		modalConfirmation,
	},
	props: {
		ingresos: {
			type: Object,
			default: () => {},
		},
	},
	data: () => ({
		dialogConfirmation: false,
		selectedItem: 1,

		items: [
			{ text: "Pagar nominas", icon: "mdi-account-cash", path: "/nominas" },
			{ text: "Corte de la noche", icon: "mdi-cash-100", path: "/cortes" },
		],
	}),
};
</script>

<style>
</style>