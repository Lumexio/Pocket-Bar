<template>
	<v-card :dark="this.$store.getters.hasdarkflag">
		<v-toolbar color="transparent" flat>
			<v-toolbar-title>Tabla mesa</v-toolbar-title>
			<v-divider class="ml-4" inset vertical></v-divider>
			<v-spacer></v-spacer>
			<v-text-field v-model="search" label="Buscar mesa" class="mt-4" id="onsearch"></v-text-field>
		</v-toolbar>

		<v-data-table :headers="headers" show-expand :expanded.sync="expanded" :items="mesaArray" class="elevation-1"
			:search="search" :custom-filter="filterOnlyCapsText.toUpperCase">
			<template v-slot:top>
				<v-progress-linear height="6" indeterminate color="cyan" :active="cargaTabla"></v-progress-linear>
				<v-dialog :dark="$store.getters.hasdarkflag" v-model="dialog" max-width="500px">
					<v-card>
						<v-card-title>
							<h1 class="headline">{{ formTitle }}</h1>
						</v-card-title>

						<v-card-text>
							<v-container>
								<v-row>
									<v-col>
										<v-text-field v-model="editedItem.name" label="Nombre"></v-text-field>
									</v-col>
								</v-row>
								<v-row><v-col>
										<v-textarea v-model="editedItem.description" label="Descrpción"
											type="text"></v-textarea> </v-col></v-row>
							</v-container>
						</v-card-text>

						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn color="blue darken-1" v-on:keyup.enter="save" text @click.prevent="close">
								Cancelar
							</v-btn>
							<v-btn color="blue darken-1" text @click.prevent="save">
								Guardar
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
				<!-- <v-dialog
					:dark="$store.getters.hasdarkflag"
					v-model="dialogActivate"
					max-width="500px"
				>
					<v-card>
						<v-card-title class="headline"
							>¿Estas seguro de querer eliminarlo?</v-card-title
						>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn color="blue darken-1" text @click.prevent="closeDelete"
								>Cancelar</v-btn
							>
							<v-btn
								color="blue darken-1"
								text
								@click.prevent="activationConfirm"
								>Aceptar</v-btn
							>
							<v-spacer></v-spacer>
						</v-card-actions>
					</v-card>
				</v-dialog> -->
				<modalConfirmation :dialogConfirmation.sync="dialogActivate">
					<template v-slot:titledialog>
						<span v-show="editedItem.active === 0" class="headline">
							¿Estas seguro de querer habilitarlo?
						</span>
						<span v-show="editedItem.active === 1" class="headline">
							¿Quieres deshabilitarlo?
						</span>
					</template>
					<template v-slot:buttonsuccess>
						<v-btn v-on:keyup.enter="activationConfirm" large :disabled="cargaDialog == true" :color="$store.getters.hasdarkflag
							? editedItem.active == 1
								? 'red darken-4'
								: 'lime darken-2'
							: editedItem.active == 1
								? 'red lighten-2'
								: 'lime accent-4'" @click.prevent="activationConfirm">
							<span v-show="cargaDialog == false">confirmar</span>
							<v-progress-circular v-show="cargaDialog == true" :active="cargaDialog"
								:indeterminate="cargaDialog" :size="20"></v-progress-circular>
						</v-btn>
					</template>
				</modalConfirmation>
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-icon small :dark="$store.getters.hasdarkflag" @click.prevent="editItem(item)">
					mdi-pencil
				</v-icon>

				<v-icon v-show="item.active === 1" small :dark="$store.getters.hasdarkflag"
					@click.prevent="deleteItem(item)">
					mdi-lightbulb-on
				</v-icon>
				<v-icon v-show="item.active === 0" small :dark="$store.getters.hasdarkflag"
					@click.prevent="deleteItem(item)">
					mdi-lightbulb-on-outline
				</v-icon>
			</template>
			<template v-slot:no-data>
				<span>Datos no disponibles.</span>
			</template>
			<template v-slot:expanded-item="{ headers, item }">
				<td :colspan="headers.length">
					{{ item.description }}
				</td>
			</template>
		</v-data-table>
	</v-card>
</template>

<script>
import modalConfirmation from "../global/modal-confirmation.vue";
import { getMesas, activationMesas, editMesa } from "@/api/mesas.js";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	name: "tabla-mesa",
	components: {
		modalConfirmation,
	},
	data: () => ({
		dialog: false,
		dialogActivate: false,
		search: "",
		cargaTabla: true,
		cargaDialog: false,
		expanded: [],
		headers: [
			{
				text: "Mesas",
				align: "start",
				sortable: false,
				value: "name",
			},
			{ text: "Acciones", value: "actions", sortable: false, align: "center" },
			{ text: "Descripción", align: "start", value: "data-table-expand" },
		],

		mesaArray: [],
		//variable en la que se deposita la posicion en el selector
		selectrol: null, //Rol

		//Array en el que se deposita de los selectores.
		itemsrol: [], //Rol

		editedIndex: -1,
		editedItem: {
			id: "",
			name: "",
			description: "",
		},
		defaultItem: {
			id: "",
			name: "",
			description: "",
		},
	}),
	mounted() {
		this.onFocus();
		window.Echo.channel("mesas").listen("TableCreated", (e) => {
			console.log(e.mesas.original.mesas);
			this.mesaArray = e.mesas.original.mesas;
		});
		getMesas(this.mesaArray)
			.then((response) => {
				if (response.stats === 200) {
					this.cargaTabla = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargaTabla = true;
			});
	},

	computed: {
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Editar mesa";
		},
	},

	watch: {
		dialog(val) {
			val || this.close();
		},
		dialogActivate(val) {
			val || this.closeDelete();
		},
	},

	created() { },

	methods: {
		onFocus() {
			let stext = document.getElementById("onsearch");
			stext;
			stext = addEventListener("keydown", (e) => {
				if (e.altKey) {
					document.getElementById("onsearch").focus();
				}
			});
		},
		filterOnlyCapsText(value, search) {
			return (
				value != null &&
				search != null &&
				typeof value === "string" &&
				value.toString().toLocaleUpperCase().indexOf(search) !== -1
			);
		},

		editItem(item) {
			this.editedIndex = this.mesaArray.indexOf(item);
			this.editedItem = Object.assign({}, item);

			this.dialog = true;
		},

		deleteItem(item) {
			this.editedIndex = this.mesaArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogActivate = true;
		},

		activationConfirm() {
			this.cargaDialog = false;
			activationMesas(this.editedItem.id).then((response) => {
				if (response.status === 200) {
					this.cargaDialog = false;
					this.closeDelete();
				}
			});
		},

		close() {
			this.dialog = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},

		closeDelete() {
			this.dialogActivate = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},

		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.mesaArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.name = upperConverter(send.name);
				let url = "api/mesa/";
				url = url + send.id;
				url = `${url}?${"name=" + send.name}&${"description=" + send.description
					}`;
				editMesa(url);
			} else {
				this.mesaArray.push(this.editedItem);
			}
			this.close();
		},
	},
};
</script>

<style scoped>
#tabla {
	inline-size: 60rem;
}

.tabla {
	inline-size: 60rem;
}
</style>