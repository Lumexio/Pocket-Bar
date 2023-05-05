<template>
	<v-card :dark="this.$store.getters.hasdarkflag">
		<v-toolbar color="transparent" flat>
			<v-toolbar-title>Tabla mesa</v-toolbar-title>
			<v-divider class="ml-4" inset vertical></v-divider>
			<v-spacer></v-spacer>
			<v-text-field
				v-model="search"
				label="Buscar mesa"
				class="mt-4"
				id="onsearch"
			></v-text-field>
		</v-toolbar>

		<v-data-table
			:headers="headers"
			show-expand
			:expanded.sync="expanded"
			:items="mesaArray"
			class="elevation-1"
			:search="search"
			:custom-filter="filterOnlyCapsText.toUpperCase"
		>
			<template v-slot:top>
				<v-progress-linear
					height="6"
					indeterminate
					color="cyan"
					:active="cargando"
				></v-progress-linear>
				<v-dialog
					:dark="$store.getters.hasdarkflag"
					v-model="dialog"
					max-width="500px"
				>
					<v-card>
						<v-card-title>
							<h1 class="headline">{{ formTitle }}</h1>
						</v-card-title>

						<v-card-text>
							<v-container>
								<v-row>
									<v-col>
										<v-text-field
											v-model="editedItem.nombre_mesa"
											label="Nombre"
										></v-text-field>
									</v-col>
								</v-row>
								<v-row
									><v-col>
										<v-textarea
											v-model="editedItem.descripcion_mesa"
											label="Descrpción"
											type="text"
										></v-textarea> </v-col
								></v-row>
							</v-container>
						</v-card-text>

						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn
								color="blue darken-1"
								v-on:keyup.enter="save"
								text
								@click.prevent="close"
							>
								Cancelar
							</v-btn>
							<v-btn color="blue darken-1" text @click.prevent="save">
								Guardar
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
				<v-dialog
					:dark="$store.getters.hasdarkflag"
					v-model="dialogDelete"
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
								@click.prevent="deleteItemConfirm"
								>Aceptar</v-btn
							>
							<v-spacer></v-spacer>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-icon small dark @click.prevent="editItem(item)"> mdi-pencil </v-icon>

				<v-icon small dark @click.prevent="deleteItem(item)">
					mdi-delete
				</v-icon>
			</template>
			<template v-slot:no-data>
				<span>Datos no disponibles.</span>
			</template>
			<template v-slot:expanded-item="{ headers, item }">
				<td :colspan="headers.length">
					{{ item.descripcion_mesa }}
				</td>
			</template>
		</v-data-table>
	</v-card>
</template>

<script>
import { getMesas, deleteMesa, editMesa } from "@/api/mesas.js";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	nombre_mesa: "tabla-mesa",
	data: () => ({
		dialog: false,
		dialogDelete: false,
		search: "",
		cargando: true,
		expanded: [],
		headers: [
			{
				text: "Mesas",
				align: "start",
				sortable: false,
				value: "nombre_mesa",
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
			nombre_mesa: "",
			descripcion_mesa: "",
		},
		defaultItem: {
			id: "",
			nombre_mesa: "",
			descripcion_mesa: "",
		},
	}),
	mounted() {
		this.onFocus();
		window.Echo.channel("mesas").listen("mesaCreated", (e) => {
			this.mesaArray = e.mesas.original.mesas;
		});
		getMesas(this.mesaArray)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargando = true;
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
		dialogDelete(val) {
			val || this.closeDelete();
		},
	},

	created() {},

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
			this.dialogDelete = true;
		},

		deleteItemConfirm() {
			this.mesaArray.splice(this.editedIndex, 1);
			let id = this.editedItem.id;
			deleteMesa(id);

			this.closeDelete();
		},

		close() {
			this.dialog = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},

		closeDelete() {
			this.dialogDelete = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},

		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.mesaArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.nombre_mesa = upperConverter(send.nombre_mesa);
				let url = "api/mesa/";
				url = url + send.id;
				url = `${url}?${"nombre_mesa=" + send.nombre_mesa}&${
					"descripcion_mesa=" + send.descripcion_mesa
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