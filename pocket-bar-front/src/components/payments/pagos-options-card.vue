<template>
	<v-card width="100%" height="100%" :dark="this.$store.getters.hasdarkflag">
		<v-toolbar color="trasnparent"
			><v-btn text to="/pagos"
				><v-icon>mdi-arrow-left-bold-box-outline</v-icon> volver a menu</v-btn
			></v-toolbar
		>

		<v-stepper fill-height v-model="e1">
			<v-stepper-header>
				<v-stepper-step :complete="e1 > 1" step="1">
					Seleccionar nominas
				</v-stepper-step>
				<v-divider></v-divider>
				<v-stepper-step :complete="e1 > 2" step="2"
					>Resumen de la noche y Confirmar totales
				</v-stepper-step>
			</v-stepper-header>

			<v-stepper-items>
				<v-stepper-content class="pa-0" step="1">
					<v-card-actions>
						<v-btn large @click="selectedUser = []"> Cancel </v-btn>
						<v-spacer />
						<v-btn
							:disabled="emptyCheck"
							color="success"
							@click.prevent="e1 = 2"
							large
						>
							Ver resumen nominal
						</v-btn>
					</v-card-actions>
					<v-data-table
						dense
						v-model="selectedUser"
						:headers="headers"
						:items="usersArray"
						item-key="name"
						show-select
						class="elevation-1"
					>
					</v-data-table>
				</v-stepper-content>

				<v-stepper-content step="2">
					<v-card-actions>
						<v-btn large @click.prevent="(e1 = 1), (selectedUser = [])">
							Cancel
						</v-btn>
						<v-spacer />
						<v-btn
							color="success"
							@click.prevent="postNominas(selectedUser)"
							large
							>Confirmar nominas
						</v-btn>
					</v-card-actions>
					<v-card class="mb-12" color="transparent" elevation="0">
						<v-card-text>
							<v-data-table
								:headers="headers"
								:items="selectedUser"
								class="elevation-0"
							>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-stepper-content>
			</v-stepper-items>
		</v-stepper>
	</v-card>
</template>

<script>
import { getCotizado, postNominas } from "@/api/cortes.js";
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
	computed: {
		emptyCheck() {
			if (this.selectedUser.length === 0) {
				return true;
			} else {
				return false;
			}
		},
	},
	methods: {
		postNominas(nominas) {
			var total_nominas = 0;
			nominas.forEach((item) => {
				total_nominas = total_nominas + item.nominas;
			});
			let payrol = { payroll: nominas, total_nominas: total_nominas, tips: 0 };
			postNominas(payrol)
				.then((response) => {
					if (response.response.status === 200) {
						this.e1 = 1;

						this.selectedUser = [];
						this.$store.commit("setsuccess", true);
					}
				})
				.catch((e) => {
					console.log(e);
				});
		},
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

