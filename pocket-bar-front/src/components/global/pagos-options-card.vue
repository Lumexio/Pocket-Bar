<template>
	<v-card width="100%" height="100%" :dark="this.$store.getters.hasdarkflag">
		<v-stepper v-model="e1">
			<v-stepper-header>
				<v-stepper-step :complete="e1 > 1" step="1">
					Seleccionar nominas
				</v-stepper-step>
				<v-divider></v-divider>
				<v-stepper-step :complete="e1 > 2" step="2"
					>Resumen de la noche
				</v-stepper-step>
				<v-divider></v-divider>
				<v-stepper-step :complete="e1 > 2" step="3">
					Confirmar totales</v-stepper-step
				>
			</v-stepper-header>

			<v-stepper-items>
				<v-stepper-content class="pa-0" step="1">
					<v-data-table
						v-model="selectedUser"
						:headers="headers"
						:items="usersArray"
						item-key="name"
						show-select
						class="elevation-1"
					>
					</v-data-table>

					<v-btn color="primary" @click="e1 = 2"> Continue </v-btn>

					<v-btn text> Cancel </v-btn>
				</v-stepper-content>

				<v-stepper-content step="2">
					<v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>

					<v-btn color="primary" @click="e1 = 3"> Continue </v-btn>

					<v-btn text> Cancel </v-btn>
				</v-stepper-content>

				<v-stepper-content step="3">
					<v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>

					<v-btn color="primary" @click="e1 = 1"> Continue </v-btn>

					<v-btn text> Cancel </v-btn>
				</v-stepper-content>
			</v-stepper-items>
		</v-stepper>
	</v-card>
</template>

<script>
import { getCotizado } from "@/api/cortes.js";
import { getUsuarios } from "@/api/usuarios.js";

export default {
	name: "cortes-structure",
	data: () => ({
		e1: 1,
		singleSelect: false,
		selectedUser: [],
		usersArray: [],
		headers: [
			{ text: "Nombre", value: "name", sortable: true },
			{ text: "Rol", value: "name_rol" },
			{ text: "Nomina", value: "nominas" },
		],
	}),
	methods: {
		getusers() {},
	},
	mounted() {
		getUsuarios(this.usersArray)
			.then((response) => {
				this.usersArray = response.usersArray;
			})
			.catch((e) => {
				console.log(e);
			});
		getCotizado();
	},
};
</script>

