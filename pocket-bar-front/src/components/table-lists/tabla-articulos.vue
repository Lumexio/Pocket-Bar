<template>
	<v-data-table :dark="this.$store.getters.hasdarkflag" :headers="headers" :items="articulosArray"
		:expanded.sync="expanded" sort-by="cantidad_articulo" class="elevation-2" :search="search" show-expand
		:custom-filter="filterOnlyCapsText.toUpperCase">
		<template v-slot:top>
			<v-toolbar color="transparent" flat>
				<v-card-title>Tabla artículos</v-card-title>
				<v-divider inset vertical></v-divider>
				<v-spacer></v-spacer>
				<v-text-field label="Buscar artículo" placeholder="Nombre, cantidad, categoria, tipo ...." class="mt-4"
					v-model="search" id="onsearch" />
			</v-toolbar>
			<v-progress-linear height="6" indeterminate color="cyan" :active="cargando"></v-progress-linear>
			<v-dialog :dark="$store.getters.hasdarkflag" v-model="dialog" min-width="300px" max-width="600px">
				<v-card>
					<v-card-title>
						<h1 class="headline">{{ formTitle }}</h1>
					</v-card-title>
					<v-card-text>
						<v-text-field v-model="editedItem.nombre_articulo" label="Nombre"></v-text-field>
						<v-text-field v-model="editedItem.cantidad_articulo" type="number" label="Cantidad"></v-text-field>
						<v-select v-model="selectc" :items="itemsc" item-text="nombre_categoria" item-value="id"
							label="Categoría"></v-select>
						<v-select v-model="selectt" :items="itemstt" item-text="nombre_tipo" item-value="id" label="Tipo">
						</v-select>
						<v-select v-model="selectm" :items="itemstm" item-text="nombre_marca" item-value="id" label="Marca"
							required></v-select>
						<v-select v-model="selectp" :items="itemsp" item-text="name" item-value="id" label="Proveedor"
							required></v-select>
						<v-select v-model="selectst" :items="itemstst" item-text="name" item-value="status_id"
							label="Estatus" required></v-select>
						<v-textarea v-model="editedItem.descripcion_articulo" label="Descrpción" type="text"></v-textarea>
					</v-card-text>

					<v-card-actions v-on:keyup.enter="save">
						<v-spacer></v-spacer>
						<v-btn color="grey darken-2" outlined @click.prevent="close">
							Cancelar
						</v-btn>
						<v-btn color="yellow darken-2" outlined @click.prevent="save">
							Guardar cambios
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
			<modalConfirmation :dialogConfirmation.sync="dialogActivate">
				<template v-slot:titledialog>
					<span v-show="editedItem.deactivated_at === null" class="headline">
						¿Estas seguro de querer habilitarlo?
					</span>
					<span v-show="editedItem.deactivated_at != null" class="headline">
						¿Quieres deshabilitarlo?
					</span>
				</template>
				<template v-slot:buttonsuccess>
					<v-btn v-on:keyup.enter="activateItemConfirm" large :disabled="cargaDialog == true" :color="$store.getters.hasdarkflag
						? editedItem.deactivated_at != null
							? 'red darken-4'
							: 'lime darken-2'
						: editedItem.deactivated_at != null
							? 'red lighten-2'
							: 'lime accent-4'
						" @click.prevent="activateItemConfirm">
						<span v-show="cargaDialog == false">confirmar</span>
						<v-progress-circular v-show="cargaDialog == true" :active="cargaDialog" :indeterminate="cargaDialog"
							:size="20"></v-progress-circular>
					</v-btn>
				</template>
			</modalConfirmation>
			<v-dialog v-model="dialogDetail" transition="dialog-bottom-transition" max-width="600">
				<template v-slot:default="dialogDetail">
					<v-card :dark="$store.getters.hasdarkflag" :key="count">
						<v-toolbar flat color="transparent">
							<v-card-title>
								Foto {{ editedItem.nombre_articulo }}</v-card-title></v-toolbar>
						<v-card-text>
							<v-img :colspan="headers.length" v-bind:lazy-src="editedItem.image" max-height="500"
								max-width="600" v-bind:src="editedItem.image"></v-img>
						</v-card-text>
						<v-card-actions class="justify-end">
							<v-file-input v-model="photo" prepend-icon="mdi-camera" hide-input
								label="File input"></v-file-input>
							<v-btn v-show="photo != null" color="orange" @click.prevent="photochange()">Subir Imagen</v-btn>
							<v-btn @click.prevent="dialogDetail.value = false">Close</v-btn>
						</v-card-actions>
					</v-card>
				</template>
			</v-dialog>
		</template>

		<template v-slot:[`item.name_status`]="{ item }">
			<v-chip :color="getColor(item.name_status)" :dark="$store.getters.hasdarkflag">
				{{ item.name_status }}
			</v-chip>
		</template>
		<template v-slot:[`item.actions`]="{ item }">
			<v-icon small @click.prevent="editItem(item)" :dark="$store.getters.hasdarkflag">
				mdi-pencil
			</v-icon>
			<v-icon v-show="item.deactivated_at === null" small :dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)">
				mdi-lightbulb-on
			</v-icon>
			<v-icon v-show="item.deactivated_at != null" small :dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)">
				mdi-lightbulb-on-outline
			</v-icon>

			<v-icon small @click.prevent="detailItem(item)">
				mdi-file-eye-outline
			</v-icon>
		</template>
		<template v-slot:no-data>
			<span>Datos no disponibles.</span>
		</template>
		<template v-slot:expanded-item="{ headers, item }">
			<td :colspan="headers.length">
				{{ item.descripcion_articulo }}
			</td>
		</template>
	</v-data-table>
</template>

<script>
import modalConfirmation from "../global/modal-confirmation.vue";
import store from "@/store";
import { postPhoto } from "@/api/photohandler.js";
import {
	getArticulos,
	activateArticulos,
	editArticulos,
} from "@/api/articulos.js";

import { getCategorias } from "@/api/categorias.js";
import { getMarcas } from "@/api/marcas.js";
import { getProveedores } from "@/api/proveedores.js";
import { getTipos } from "@/api/tipos.js";

import { getStatus } from "@/api/status.js";
import { upperConverter } from "@/special/uppercases-converter.js";
import {
	tiposync,
	statusync,
	categsync,
	marcasync,
	proveedorsync,
} from "@/special/sync-indexitems.js";

export default {
	name: "tabla-articulos",
	components: {
		modalConfirmation,
	},
	data: () => ({
		search: "",
		dialog: false,
		singleExpand: false,
		dialogActivate: false,
		dialogDetail: false,
		cargando: true,
		cargaDialog: false,
		expanded: [],

		headers: [
			{
				text: "Nombre",
				align: "start",
				sortable: false,
				value: "name",
			},
			{
				text: "Cantidad",
				value: "quantity",
				align: "start",
			},
			{ text: "Categoría", value: "name_categoria", align: "center" },
			{ text: "Tipo", value: "name_tipo", align: "center" },
			{ text: "Marca", value: "name_marca", align: "center" },
			{ text: "Proveedor", value: "name_proveedor", align: "center" },
			{ text: "estatus", value: "name_status", align: "center" },
			{
				text: "Acciones",

				value: "actions",
				sortable: false,
				align: "center",
			},
			{ align: "end", value: "data-table-expand" },
		],

		articulosArray: [],
		//variable en la que se deposita la posicion en el selector
		selectc: "", //categoria
		selectt: "", //tipo
		selectp: "", //proveedor
		selectm: null, //marca
		selectst: "", //status

		//Array en el que se deposita de los selectores.
		itemsc: [], //categoria
		itemstt: [], //tipo
		itemsp: [], //proveedor
		itemstm: [], //marca
		itemstst: [], //status

		photo: null,

		editedIndex: -1,
		editedItem: {
			id: "",
			nombre_articulo: "",
			cantidad_articulo: 0,
			nombre_categoria: "",
			nombre_tipo: "",
			nombre_marca: "",
			nombre_proveedor: "",
			name_status: "",
			image: null,
			descripcion_articulo: "",
		},
		defaultItem: {
			id: "",
			nombre_articulo: "",
			cantidad_articulo: 0,
			nombre_categoria: "",
			nombre_tipo: "",
			nombre_marca: "",
			nombre_proveedor: "",
			name_status: "",
			image: null,
			descripcion_articulo: "",
		},
	}),
	created() {
		this.onFocus();
	},
	mounted() {
		window.Echo.channel("articulos").listen("ProductCreated", (e) => {
			console.log(e.articulos.original.articulos);
			this.articulosArray = e.articulos.original.articulos;
		});

		window.Echo.channel("categorias").listen("categoriaCreated", (e) => {
			this.itemsc = e.categorias.original.categorias;
		});
		window.Echo.channel("marcas").listen("marcaCreated", (e) => {
			this.itemstm = e.marcas.original.marcas;
		});
		window.Echo.channel("proveedores").listen("proveedorCreated", (e) => {
			this.itemsp = e.proveedores.original.proveedores;
		});
		window.Echo.channel("status").listen("statusCreated", (e) => {
			this.itemstst = e.status.original.status;
		});
		window.Echo.channel("tipos").listen("tipoCreated", (e) => {
			this.itemstt = e.tipos.original.tipos;
		});

		getArticulos(this.articulosArray)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
					store.commit("setsuccess", null); //para resetear el valor de la notificion en una nueva entrada
					store.commit("setdanger", null);
				}
			})
			.catch((e) => {
				alert(e);
				if (e) {
					store.commit("setdanger", true);
				}
				this.cargando = true;
			});
		getCategorias(this.itemsc);
		getMarcas(this.itemstm);
		getProveedores(this.itemsp);
		getTipos(this.itemstt);

		getStatus(this.itemstst);
	},

	computed: {
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Editar articulo";
		},
		count() {
			return store.getters.counter;
		},
		hasrol() {
			return store.getters.hasrol;
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
		onFocus() {
			let stext = document.getElementById("onsearch");
			stext;
			stext = addEventListener("keyup", (e) => {
				if (e.altKey) {
					document.getElementById("onsearch").focus();
				}
			});
		},
		getColor(status) {
			if (status === "Agotado") return "red lighten-2";
			else if (status === "Disponible") return "orange lighten-2";
			else if (status === "En uso") return "blue lighten-2";
			else return "withe";
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
			this.editedIndex = this.articulosArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			//categoria
			if (this.editedItem.nombre_categoria) {
				let categoriasync = this.editedItem.nombre_categoria;
				this.selectc = categsync(this.itemsc, this.selectc, categoriasync);
			}
			//tipo
			if (this.editedItem.nombre_tipo) {
				let typosync = this.editedItem.nombre_tipo;
				this.selectt = tiposync(this.itemstt, this.selectt, typosync);
			}
			//proveedor
			let provsync = this.editedItem.nombre_proveedor;
			this.selectp = proveedorsync(this.itemsp, this.selectp, provsync);
			//marca
			let marcsync = this.editedItem.nombre_marca;
			this.selectm = marcasync(this.itemstm, this.selectm, marcsync);

			//status
			let statync = this.editedItem.name_status;
			this.selectst = statusync(this.itemstst, this.selectst, statync);

			this.dialog = true;
		},

		deleteItem(item) {
			this.editedIndex = this.articulosArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogActivate = true;
		},
		detailItem(item) {
			this.editedIndex = this.articulosArray.indexOf(item);
			this.editedItem = Object.assign({}, item);

			this.dialogDetail = true;
		},

		activateItemConfirm() {
			this.cargaDialog = true;
			activateArticulos(this.editedItem.id).then((response) => {

				if (response.status === 200) {
					this.cargaDialog = false;
					this.closeDelete();
				}
			});
		},

		close() {
			this.dialog = false;
			this.dialogDetail = false;
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
		photochange() {
			if (this.editedIndex > -1) {
				Object.assign(this.articulosArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;

				if (this.photo != null) {
					const formdata = new FormData();
					formdata.append("image", this.photo);
					postPhoto(send.id, formdata);
				}
			} else {
				this.articulosArray.push(this.editedItem);
			}
			this.close();
		},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.articulosArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.nombre_articulo = upperConverter(send.nombre_articulo);
				const pack = {
					name: send.nombre_articulo,
					quantity: send.cantidad_articulo,
					category_id: this.selectc,
					provider_id: this.selectp,
					tipe_id: this.selectt,
					status_id: this.selectst,
					brand_id: this.selectm,
					description: send.descripcion_articulo,
				};

				editArticulos(send.id, pack);
			} else {
				this.articulosArray.push(this.editedItem);
			}
			this.close();
		},
	},
};
</script>


