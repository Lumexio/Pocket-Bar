<template>
	<v-card :dark="this.$store.getters.hasdarkflag">
		<v-toolbar flat color="transparent">
			<v-toolbar-title>Tabla proveedor</v-toolbar-title>
			<v-divider class="ml-4" inset vertical></v-divider>
			<v-spacer></v-spacer>
			<v-text-field v-model="search" label="Buscar proveedor" class="mx-4" id="onsearch"></v-text-field>
		</v-toolbar>

		<v-data-table :dark="this.$store.getters.hasdarkflag" id="tabla" :headers="headers" show-expand
			:expanded.sync="expanded" :items="proveedorArray" sort-by="cantidad_articulo" class="elevation-1"
			:search="search" :custom-filter="filterOnlyCapsText.toUpperCase">
			<template v-slot:top>
				<v-progress-linear height="6" indeterminate color="cyan" :active="cargandoTabla"></v-progress-linear>

				<v-dialog :dark="$store.getters.hasdarkflag" v-model="dialog" max-width="500px">
					<v-card>
						<v-card-title>
							<h1 class="headline">{{ formTitle }}</h1>
						</v-card-title>

						<v-card-text>
							<v-container>
								<v-row>
									<v-col cols="12" sm="6" md="4">
										<v-text-field v-model="editedItem.nombre_proveedor" label="Nombre"></v-text-field>
									</v-col>
								</v-row>
							</v-container>
						</v-card-text>

						<v-card-actions v-on:keyup.enter="save">
							<v-spacer></v-spacer>
							<v-btn color="blue darken-1" text @click.prevent="close">
								Cancelar
							</v-btn>
							<v-btn color="blue darken-1" text @click.prevent="save">
								Guardar
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
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
						<v-btn
							large
							:disabled="cargaDialog == true"
							:color="
							$store.getters.hasdarkflag
								? editedItem.active == 1
									? 'red darken-4'
									: 'lime darken-2'
								: editedItem.active == 1
								? 'red lighten-2'
								: 'lime accent-4'"		
							@click.prevent="activateItemConfirm"
						>
							<span v-show="cargaDialog == false">confirmar</span>
							<v-progress-circular
								v-show="cargaDialog == true"
								:active="cargaDialog"
								:indeterminate="cargaDialog"
								:size="20"
							></v-progress-circular>
						</v-btn>
					</template>
				</modalConfirmation>
			</template>
			<template v-slot:[`item.active`]="{ item }">
				<v-chip :color="getActivo(item.active)" :dark="$store.getters.hasdarkflag">
					<span v-show="item.active === 1 && getActivo(item.active) === `amber lighten-1`
						">En servicio</span>
					<span v-show="item.active === 0 && getActivo(item.active) === `cyan darken-1`
						">Fuera de servcio</span>
				</v-chip>
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-icon small :dark="$store.getters.hasdarkflag" @click.prevent="editItem(item)">
					mdi-pencil
				</v-icon>

				<v-icon v-show="item.active === 1" small :dark="$store.getters.hasdarkflag" @click.prevent="deleteItem(item)">
					mdi-lightbulb-on
				</v-icon>
				<v-icon v-show="item.active === 0" small :dark="$store.getters.hasdarkflag" @click.prevent="deleteItem(item)">
					mdi-lightbulb-on-outline
				</v-icon>
			</template>

			<template v-slot:no-data>
				<span>Datos no disponibles.</span>
			</template>
			<template v-slot:expanded-item="{ headers, item }">
				<td :colspan="headers.length">
					{{ item.descripcion }}
				</td>
			</template>
		</v-data-table>
	</v-card>
</template>

<script>
import modalConfirmation from "../global/modal-confirmation.vue";
import {
	getProveedores,
	activationProveedores,
	editProveedores,
} from "@/api/proveedores.js";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	nombre_proveedor: "tabla-proveedor",
		components: {
		modalConfirmation,
	},
	data: () => ({
		dialog: false,
		dialogActivate: false,
		cargaDialog: false,
		search: "",
		cargandoTabla: true,
		expanded: [],

		headers: [
			{
				text: "Proveedores",
				align: "start",
				sortable: false,
				value: "nombre_proveedor",
			},
			{ text: "Status", value: "active", align: "center" },
			{ text: "Acciones", value: "actions", sortable: false, align: "center" },
			{ text: "Descripción", align: "start", value: "data-table-expand" },
		],

		proveedorArray: [],
		//variable en la que se deposita la posicion en el selector
		selectrol: null, //Rol

		//Array en el que se deposita de los selectores.
		itemsrol: [], //Rol

		editedIndex: -1,
		editedItem: {
			id: "",
			nombre_proveedor: "",
			active: null,
		},
		defaultItem: {
			id: "",
			nombre_proveedor: "",
			active: null,
		},
	}),
	mounted() {
		this.onFocus();
		window.Echo.channel("proveedores").listen("proveedorCreated", (e) => {
			this.proveedorArray = e.proveedores.original.proveedores;
		});

		getProveedores(this.proveedorArray)
			.then((response) => {
				if (response.stats === 200) {
					this.cargandoTabla = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargandoTabla = true;
			});
	},

	computed: {
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Editar proveedor";
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
	methods: {
		getActivo(status) {
			if (status === 1) {
				return "amber lighten-1";
			} else if (status === 0) {
				return "cyan darken-1";
			}
		},
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
			this.editedIndex = this.proveedorArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialog = true;
		},

		deleteItem(item) {
			this.editedIndex = this.proveedorArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogActivate = true;
		},
		activateItemConfirm() {
			activationProveedores(this.editedItem.id).then((response) => {
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
				Object.assign(this.proveedorArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.nombre_proveedor = upperConverter(send.nombre_proveedor);
				let url = "api/proveedor/";

				url = url + send.id;
				url = `${url}?${"nombre_proveedor=" + send.nombre_proveedor}`;
				editProveedores(url);
			} else {
				this.proveedorArray.push(this.editedItem);
			}
			this.close();
		},
	},
};
</script>

<style scoped>
#tabla {
	inline-size: 100%;
}

.tabla {
	inline-size: 60rem;
}
</style>