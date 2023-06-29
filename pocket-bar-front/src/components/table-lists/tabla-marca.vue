<template>
	<v-data-table
		:dark="this.$store.getters.hasdarkflag"
		id="tabla"
		:headers="headers"
		show-expand
		:expanded.sync="expanded"
		:items="marcaArray"
		sort-by="cantidad_articulo"
		class="elevation-1"
		:search="search"
		:custom-filter="filterOnlyCapsText.toUpperCase"
	>
		<template v-slot:top>
			<v-toolbar flat color="transparent">
				<v-toolbar-title>Tabla marca</v-toolbar-title>
				<v-divider inset vertical class="ml-4"></v-divider>
				<v-spacer></v-spacer>
				<v-text-field
					v-model="search"
					label="Buscar marca"
					class="mt-4"
					id="onsearch"
				></v-text-field>
			</v-toolbar>
			<v-progress-linear
				height="6"
				indeterminate
				color="cyan"
				:active="cargando"
			>
			</v-progress-linear>
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
								<v-col cols="12" sm="6" md="4">
									<v-text-field
										v-model="editedItem.nombre_marca"
										label="Nombre"
									></v-text-field>
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
						:disabled="cargando2 == true"
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
						<span v-show="cargando2 == false">confirmar</span>
						<v-progress-circular
							v-show="cargando2 == true"
							:active="cargando2"
							:indeterminate="cargando2"
							:size="20"
						></v-progress-circular>
					</v-btn>
				</template>
			</modalConfirmation>
		</template>
		<template v-slot:[`item.active`]="{ item }">
			<v-chip
				:color="getActivo(item.active)"
				:dark="$store.getters.hasdarkflag"
			>
				<span
					v-show="
						item.active === 1 && getActivo(item.active) === `amber lighten-1`
					"
					>En servicio</span
				>
				<span
					v-show="
						item.active === 0 && getActivo(item.active) === `cyan darken-1`
					"
					>Fuera de servcio</span
				>
			</v-chip>
		</template>
		<template v-slot:[`item.actions`]="{ item }">
			<v-icon
				small
				:dark="$store.getters.hasdarkflag"
				@click.prevent="editItem(item)"
			>
				mdi-pencil
			</v-icon>
			<v-icon
				v-show="item.active === 1"
				small
				:dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)"
			>
				mdi-lightbulb-on
			</v-icon>
			<v-icon
				v-show="item.active === 0"
				small
				:dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)"
			>
				mdi-lightbulb-on-outline
			</v-icon>
		</template>
		<template v-slot:no-data>
			<span>Datos no disponibles.</span>
		</template>
		<template v-slot:expanded-item="{ headers, item }">
			<td :colspan="headers.length">
				{{ item.descripcion_marca }}
			</td>
		</template>
	</v-data-table>
</template>
<script>
import modalConfirmation from "../global/modal-confirmation.vue";
import { getMarcas, editMarcas, avtivationMarcas } from "@/api/marcas.js";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	name: "tabla-marca",
	components: {
		modalConfirmation,
	},
	data: () => ({
		dialog: false,
		dialogActivate: false,
		search: "",
		cargando: true,
		cargando2: false,
		expanded: [],
		headers: [
			{
				text: "Marcas",
				align: "start",
				sortable: false,
				value: "nombre_marca",
			},
			{ text: "Status", value: "active", align: "center" },
			{ text: "Acciones", value: "actions", sortable: false, align: "center" },
			{ text: "Descripción", align: "start", value: "data-table-expand" },
		],
		marcaArray: [],
		editedIndex: -1,
		editedItem: {
			id: "",
			nombre_marca: "",
			active: null,
		},
		defaultItem: {
			id: "",
			nombre_marca: "",
			active: null,
		},
	}),
	mounted() {
		this.onFocus();
		window.Echo.channel("marcas").listen("marcaCreated", (e) => {
			this.marcaArray = e.marcas.original.marcas;
		});
		getMarcas(this.marcaArray)
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
			return this.editedIndex === -1 ? "New Item" : "Editar marca";
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
			this.editedIndex = this.marcaArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialog = true;
		},
		deleteItem(item) {
			this.editedIndex = this.marcaArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogActivate = true;
		},
		activateItemConfirm() {
			avtivationMarcas(this.editedItem.id).then((response) => {
				if (response.status === 200) {
					this.cargando2 = false;
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
				Object.assign(this.marcaArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.nombre_marca = upperConverter(send.nombre_marca);
				let url = "api/marca/";

				url = url + send.id;
				url = `${url}?${"nombre_marca=" + send.nombre_marca}`;
				editMarcas(url);
			} else {
				this.marcaArray.push(this.editedItem);
			}
			this.close();
		},
	},
};
</script>