<template>
	<v-data-table
		:dark="this.$store.getters.hasdarkflag"
		
		:headers="headers"
		show-expand
		:expanded.sync="expanded"
		:items="tipoArray"
		sort-by="cantidad_articulo"
		class="elevation-1"
		:search="search"
		:custom-filter="filterOnlyCapsText.toUpperCase"
	>
		<template v-slot:top>
			<v-toolbar flat color="transparent">
				<v-toolbar-title>Tabla tipo</v-toolbar-title>
				<v-divider class="ml-4" inset vertical></v-divider>
				<v-spacer></v-spacer>
				<v-text-field
					v-model="search"
					label="Buscar tipo"
					class="mt-4"
					id="onsearch"
				></v-text-field>
			</v-toolbar>
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
								<v-col cols="12" sm="6" md="4">
									<v-text-field
										v-model="editedItem.nombre_tipo"
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
			<v-dialog
				:dark="$store.getters.hasdarkflag"
				v-model="dialogDelete"
				max-width="500px"
			>
				<v-card>
					<v-card-title v-show="editedItem.active === false" class="headline">
						¿Estas seguro de querer habilitarlo?
					</v-card-title>
					<v-card-title v-show="editedItem.active === true" class="headline"	>
						¿Quieres deshabilitarlo?
					</v-card-title>
					<v-card-actions v-on:keyup.enter="deleteItemConfirm">
						<v-spacer></v-spacer>
						<v-btn  @click.prevent="closeDelete"
							>Cancelar</v-btn
						>
						<v-btn color="blue darken-1"  @click.prevent="deleteItemConfirm"
							>Aceptar</v-btn
						>
						<v-spacer></v-spacer>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</template>
		<template v-slot:[`item.active`]="{ item }">
			<v-chip :color="getActivo(item.active)" dark>
				<span
					v-show="
						item.active === true && getActivo(item.active) === `amber lighten-1`
					"
					>En servicio</span
				>
				<span
					v-show="
						item.active === false && getActivo(item.active) === `cyan darken-1`
					"
					>Fuera de servcio</span
				>
			</v-chip>
		</template>
		<template v-slot:[`item.actions`]="{ item }">
			<v-icon small dark @click.prevent="editItem(item)"> mdi-pencil </v-icon>

			<v-icon
				v-show="item.active === true"
				small
				dark
				@click.prevent="deleteItem(item)"
			>
				mdi-lightbulb-on
			</v-icon>
			<v-icon
				v-show="item.active === false"
				small
				dark
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
				{{ item.descripcion_tipo }}
			</td>
		</template>
	</v-data-table>
</template>

<script>
import { getTipos, deleteTipos, editTipos } from "@/api/tipos.js";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	nombre_tipo: "tabla-tipo",
	data: () => ({
		dialog: false,
		dialogDelete: false,
		search: "",
		cargando: true,
		expanded: [],
		headers: [
			{
				text: "Tipo",
				align: "start",
				sortable: false,
				value: "nombre_tipo",
			},
			{ text: "Status", value: "active", align: "center" },
			{ text: "Acciones", value: "actions", sortable: false, align: "center" },
			{ text: "Descripción", align: "start", value: "data-table-expand" },
		],

		tipoArray: [],
		//variable en la que se deposita la posicion en el selector

		editedIndex: -1,
		editedItem: {
			id: "",
			nombre_tipo: "",
			active:false
		},
		defaultItem: {
			id: "",
			nombre_tipo: "",
			active:false
		},
	}),
	mounted() {
		this.onFocus();
		window.Echo.channel("tipos").listen("tipoCreated", (e) => {
			this.tipoArray = e.tipos.original.tipos;
		});
		getTipos(this.tipoArray)
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
			return this.editedIndex === -1 ? "New Item" : "Editar tipo";
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
		getActivo(status) {
			if (status === true) {
				return "amber lighten-1";
			} else if (status == false) {
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
			this.editedIndex = this.tipoArray.indexOf(item);
			this.editedItem = Object.assign({}, item);

			this.dialog = true;
		},

		deleteItem(item) {
			this.editedIndex = this.tipoArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogDelete = true;

			let id = this.editedItem.id;
			deleteTipos(id);
		},

		deleteItemConfirm() {
			this.tipoArray.splice(this.editedIndex, 1);
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
				Object.assign(this.tipoArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.nombre_tipo = upperConverter(send.nombre_tipo);
				let url = "api/tipo/";
				url = url + send.id;
				url = `${url}?${"nombre_tipo=" + send.nombre_tipo}`;
				editTipos(url);
			} else {
				this.tipoArray.push(this.editedItem);
			}
			this.close();
		},
	},
};
</script>

<style scoped>

</style>