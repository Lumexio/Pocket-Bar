<template>
	<v-dialog :dark="this.$store.getters.hasdarkflag" content-class="elevation-0" v-model="dialogarticulo" persistent
		max-width="40rem">
		<v-card v-on:keyup.enter="submit()" class="cont-card">
			<v-toolbar :dark="this.$store.getters.hasdarkflag" flat color="transparent">
				<v-btn v-shortkey="['esc']" icon color="dark" @shortkey="onClose" @click.prevent="onClose">
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Crear {{ nameat }}</v-toolbar-title>
			</v-toolbar>
			<form>
				<v-text-field :rules="nameRules" v-model="name" type="text" :counter="100" label="Nombre"
					required></v-text-field>
				<v-text-field :rules="numberRules" v-model="cant" type="number" :counter="10" label="Cantidad"
					required></v-text-field>
				<v-text-field :rules="numberRules" v-model="price" type="number" label="Precio" required></v-text-field>
				<v-select v-model="selectc" :items="itemsc" item-text="nombre_categoria" item-value="id" label="Categoría">
				</v-select>
				<v-select v-model="selectt" :items="itemstt" item-text="nombre_tipo" item-value="id" label="Tipo">
				</v-select>
				<v-select v-model="selectp" :items="itemsp" item-text="nombre_proveedor" item-value="id" label="Proveedor"
					required></v-select>
				<v-select v-model="selectm" :items="itemstm" item-text="nombre_marca" item-value="id" label="Marca"
					required></v-select>
				<v-select v-model="selectst" :items="itemstst" item-text="nombre_status" item-value="status_id"
					label="Estatus" required></v-select>
				<v-file-input label="Subir fotografìa" v-model="photo" prepend-icon="mdi-camera"></v-file-input>
				<v-textarea v-model="descripcion_articulo" label="Descrpción" type="text"></v-textarea>
			</form>
			<v-card-actions><v-spacer></v-spacer>
				<v-btn color="grey darken-1" @click.prevent="clear" outlined>
					<v-icon>mdi-eraser</v-icon>
				</v-btn>
				<v-btn color="yellow darken-2" class="mr-4" v-shortkey="['enter']" @shortkey="submit"
					@click.prevent="submit" outlined>
					Guardar {{ nameat }}
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import store from "@/store";
import { postArticulos } from "@/api/articulos.js";
import { getCategorias } from "@/api/categorias.js";
import { getMarcas } from "@/api/marcas.js";
import { getProveedores } from "@/api/proveedores.js";
import { getStatus } from "@/api/status.js";

import { getTipos } from "@/api/tipos.js";

import { upperConverter } from "@/special/uppercases-converter.js";

export default {
	name: "creararticulos",
	props: {
		dialogarticulo: { default: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		name: "",
		descripcion_articulo: "",
		cant: "",
		price: "",
		nameat: "artículo", //nombre variable en componente
		cargando: false,
		selectc: "", //*categoria
		selectt: "", //*tipo
		selectp: "", //*proveedor
		selectm: "", //*marca
		selectst: "", //*status

		photo: null, //*Imagen de articulo

		itemsc: [], //*categoria [array]
		itemstt: [], //*tipo [array]
		itemsp: [], //*proveedor [array]
		itemstm: [], //*marca [array]
		itemstst: [], //*status [array]

		//Reglas de entradas del formulario
		nameRules: [
			(v) => !!v || "Este campo es requerido",
			(v) =>
				(v && v.length <= 100) ||
				"El Nombre debe tener menos de 100 caracteres",
			(v) => /[A-Z Ñáéíóúü]+/gi.test(v) || "Solo se permiten letras",
		],
		numberRules: [
			(v) => !!v || "Este campo es requerido",
			(v) => /[0-9]/.test(v) || "Este campo solo acepta numeros",
		],
	}),

	mounted() {
		window.Echo.channel("categorias").listen("categoriaCreated", (e) => {
			this.itemsc = e.categorias;
		});
		window.Echo.channel("marcas").listen("marcaCreated", (e) => {
			this.itemstm = e.marcas;
		});
		window.Echo.channel("proveedores").listen("proveedorCreated", (e) => {
			this.itemsp = e.proveedores;
		});
		window.Echo.channel("status").listen("statusCreated", (e) => {
			this.itemstst = e.status;
		});
		window.Echo.channel("tipos").listen("tipoCreated", (e) => {
			this.itemstt = e.tipos;
		});

		getCategorias(this.itemsc)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargando = true;
			});
		getMarcas(this.itemstm)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargando = true;
			});
		getProveedores(this.itemsp)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargando = true;
			});
		getStatus(this.itemstst)
		getTipos(this.itemstt)
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

	methods: {
		onClose() {
			/*Envia parametro de cierre a componente creación*/
			this.$emit("update:dialogarticulo", false);
		},
		submit() {
			store.commit("setsuccess", null); //para resetear el valor de la notificion en una nueva entrada
			store.commit("setdanger", null); //para resetear el valor de la notificion en una nueva entrada
			this.name = upperConverter(this.name);
			const formdata = new FormData();
			var tempPhoto = "";
			if (this.photo === null) {
				this.photo = tempPhoto;
			}
			formdata.append("name", this.name);
			formdata.append("units", this.cant);
			formdata.append("price", this.price);
			formdata.append("category_id", this.selectc);
			formdata.append("provider_id", this.selectp);
			formdata.append("type_id", this.selectt);
			formdata.append("status_id", this.selectst);
			formdata.append("brand_id", this.selectm);
			formdata.append("description", this.descripcion_articulo);
			formdata.append("image", this.photo);

			postArticulos(formdata).then((response) => {
				if (response.stats == "Created") {
					this.clear();
				}
			});
		},
		clear() {
			(this.name = ""),
				(this.cant = ""),
				(this.selectc = ""),
				(this.selectp = ""),
				(this.selectt = ""),
				(this.selectst = ""),
				(this.selectm = ""),
				(this.photo = null);
		},
	},
};
</script>
<style scoped>
.cont-card {
	padding: 1rem;
}
</style>