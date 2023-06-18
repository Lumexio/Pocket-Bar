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
						<v-btn @click.prevent="toggleBox('open')" color="purple darken-4"
							>Abrir caja</v-btn
						>
					</v-col>
					<v-col cols="12" sm="12" md="6" lg="6">
						<v-btn @click.prevent="toggleBox('close')" color="pink darken-4"
							>Cerrar caja</v-btn
						>
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
		<modalConfirmation
			:dialogConfirmation.sync="dialogConfirmation"
			:title.sync="title"
			:flagBottonCreate="flagBottonCreate"
		>
			<template v-slot:textmoneygeneral>
				<v-text-field
					prepend-inner-icon="mdi-cash"
					v-model="dinero_inicial"
					max-width="80%"
					clearable
					:label="placeholdertext"
				></v-text-field>
			</template>
			<template v-slot:buttonsuccess>
				<v-btn
					:disabled="moneyCheck(dinero_inicial)"
					@click="openBox()"
					color="success"
					>Aceptar</v-btn
				>
			</template>
		</modalConfirmation>
	</v-col>
</template>

<script>
import modalConfirmation from "../global/modal-confirmation.vue";
import { postBoxOpen, putBoxClose } from "@/api/cortes.js";
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
		flagBottonCreate: true,
		activityIdentifier: "",
		title: "",
		placeholdertext: "",
		selectedItem: 1,
		dinero_inicial: null,
		items: [
			{ text: "Pagar nominas", icon: "mdi-account-cash", path: "/nominas" },
			{ text: "Corte de la noche", icon: "mdi-cash-100", path: "/cortes" },
		],
	}),
	watch: {
		activityIdentifier(val) {
			console.log(val);
		},
	},
	methods: {
		moneyCheck(val) {
			if (val === null && val <= 0) {
				return true;
			} else if (val >= 0) {
				return false;
			}
		},
		toggleBox(param) {
			this.dialogConfirmation = true;
			if (param === "open") {
				this.activityIdentifier = "open";
				this.title = "Abrir caja";
				this.placeholdertext = "Dinero inicial en caja";
			} else if (param === "close") {
				this.activityIdentifier = "close";
				this.title = "Cerrar caja";
				this.placeholdertext = "Dinero final en caja";
			}
		},
		openBox() {
			if (this.activityIdentifier === "open") {
				postBoxOpen({ start_money: this.dinero_inicial }).then((response) => {
					console.log(response);
				});
			} else if (this.activityIdentifier === "close") {
				
				putBoxClose({ end_money: this.dinero_inicial }).then((response) => {
					console.log(response);
				});
			}
		},
	},
};
</script>

<style>
</style>