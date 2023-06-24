<template>
	<v-data-table
		:dark="this.$store.getters.hasdarkflag"
		id="tabla"
		:headers="headers"
		:items="usersArray"
		class="elevation-1"
		:search="search"
		:custom-filter="filterOnlyCapsText.toUpperCase"
	>
		<template v-slot:top>
			<v-toolbar flat color="transparent">
				<v-toolbar-title>Tabla usuarios</v-toolbar-title>
				<v-divider class="mx-4" inset vertical></v-divider>
				<v-spacer></v-spacer>
				<v-text-field
					v-model="search"
					label="Buscar usuario"
					placeholder="Nombre, correo y rol"
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
								<v-col cols="12">
									<v-text-field
										v-model="editedItem.name"
										label="Nombre"
									></v-text-field>
								</v-col>

								<v-col cols="12">
									<v-select
										v-model="selectrol"
										:items="itemsrol"
										v-on="usersync()"
										item-text="name_rol"
										item-value="rol_id"
										label="Rol"
									></v-select>
								</v-col>
							</v-row>
							<v-row>
								<v-col cols="12">
									<v-text-field
										:append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
										v-model="editedItem.password"
										:type="show3 ? 'text' : 'password'"
										hint="Minimo 8 caracteres"
										:counter="8"
										@click:append="show3 = !show3"
										label="Contraseña"
										placeholder="Contraseña"
									>
									</v-text-field>
								</v-col>
							</v-row>
						</v-container>
					</v-card-text>

					<v-card-actions v-on:keyup.enter="save">
						<v-spacer></v-spacer>
						<v-btn @click.prevent="close"> Cancelar </v-btn>
						<v-btn color="blue darken-1" @click.prevent="save"> Guardar </v-btn>
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
					<v-card-title v-show="editedItem.active === true" class="headline">
						¿Quieres deshabilitarlo?
					</v-card-title>
					<v-card-actions v-on:keyup.enter="deleteItemConfirm">
						<v-spacer></v-spacer>
						<v-btn @click.prevent="closeDelete">Cancelar</v-btn>
						<v-btn :color="$store.getters.hasdarkflag ? 'blue darken-1':'blue lighten-1'" @click.prevent="deleteItemConfirm"
							>Aceptar</v-btn
						>
						<v-spacer></v-spacer>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</template>
		<template v-slot:[`item.name_rol`]="{ item }">
			<v-chip
				:color="getColor(item.name_rol)"
				:dark="$store.getters.hasdarkflag"
			>
				{{ item.name_rol }}
			</v-chip>
		</template>
		<template v-slot:[`item.active`]="{ item }">
			<v-chip
				:color="getActivo(item.active)"
				:dark="$store.getters.hasdarkflag"
			>
				<span
					v-show="
						(item.active === true || item.active === 1) &&
						getActivo(item.active) === `amber lighten-1`
					"
					>En servicio</span
				>
				<span
					v-show="
						(item.active === false || item.active === 0) &&
						getActivo(item.active) === `cyan darken-1`
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
				v-show="(item.active === true) | (item.active === 1)"
				small
				:dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)"
			>
				mdi-lightbulb-on
			</v-icon>
			<v-icon
				v-show="item.active === false || item.active === 0"
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
	</v-data-table>
</template>

<script>
import axios from "axios";
import store from "@/store";
import { upperConverter } from "@/special/uppercases-converter.js";
import { getUsuarios } from "@/api/usuarios.js";
import { getRol } from "@/api/rol.js";
//axios.defaults.withCredentials = true;
axios.defaults.baseURL =
	"http://" + window.location.hostname /*"127.0.0.1"*/ + ":8000";
export default {
	name: "tabla-usuarios",
	data: () => ({
		dialog: false,
		dialogDelete: false,
		search: "",
		password: "",
		cargando: true,
		show3: false,

		headers: [
			{
				text: "Nombre",
				align: "start",
				sortable: false,
				value: "name",
			},

			{ text: "Rol", value: "name_rol" },
			{ text: "Status", value: "active" },

			{ text: "Acciones", value: "actions", sortable: false },
		],

		usersArray: [],
		//variable en la que se deposita la posicion en el selector
		selectrol: null, //Rol

		//Array en el que se deposita de los selectores.
		itemsrol: [], //Rol

		editedIndex: -1,
		editedItem: {
			id: "",
			name: "",
			password: "",
			name_rol: "",
			active: null,
		},
		defaultItem: {
			id: "",
			name: "",
			active: null,
			password: "",
			name_rol: "",
		},
	}),

	mounted() {
		this.onFocus();
		window.Echo.channel("users").listen("userCreated", (e) => {
			this.usersArray = e.users.original.users;
		});
		window.Echo.channel("roles").listen("rolCreated", (e) => {
			this.itemsrol = e.roles.original.roles;
		});
		getUsuarios(this.usersArray)
			.then((response) => {
				this.usersArray = response.usersArray;
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((error) => console.log(error));
		getRol(this.itemsrol)
			.then((response) => {
				this.itemsrol = response.itemsrol;

				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => console.log(e));
	},

	computed: {
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Editar usuario";
		},
		progress() {
			return Math.min(100, this.editedItem.password.length * 13);
		},
		color() {
			return ["error", "warning", "success"][Math.floor(this.progress / 40)];
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
		getColor(status) {
			if (status == "Administrativo") {
				return "amber lighten-1";
			} else if (status == "Meser@") {
				return "cyan darken-1";
			} else if (status == "Cajer@") {
				return "purple darken-1";
			} else if (status == "Bartender") {
				return "pink darken-1";
			} else if (status == "Intendencia") {
				return "indigo lighten-1";
			}
		},
		getActivo(status) {
			if (status === true || status === 1) {
				return "amber lighten-1";
			} else if (status == false || status === 0) {
				return "cyan darken-1";
			}
		},
		filterOnlyCapsText(value, search) {
			return (
				value != null &&
				search != null &&
				typeof value === "string" &&
				value.toString().toLocaleUpperCase().indexOf(search) !== -1
			);
		},
		usersync(recived) {
			var tempid = null;
			var tempname = null;
			tempname;
			if (this.itemsrol) {
				let rol = this.itemsrol;
				rol.forEach((element) => {
					let datos = {
						rol_id: element.rol_id,
						name_rol: element.name_rol,
					};
					if (datos.name_rol === recived) {
						tempid = datos.rol_id;
						tempname = datos.name_rol;

						this.selectrol = tempid;
					}
				});
			}

			return tempid;
		},

		editItem(item) {
			this.editedIndex = this.usersArray.indexOf(item);
			this.editedItem = Object.assign({}, item);

			if (this.editedItem.name_rol) {
				//categoria
				this.usersync(this.editedItem.name_rol);
			}

			this.dialog = true;
		},

		deleteItem(item) {
			this.editedIndex = this.usersArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialogDelete = true;
		},
		deleteItemConfirm() {
			axios
				.put("api/user/activate/" + this.editedItem.id)
				.then((response) => {
					if (response.data.message === "success") {
						this.usersArray.splice(this.editedIndex, 1);
						this.closeDelete();
					}
				})
				.catch((error) => console.log(error));
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
				Object.assign(this.usersArray[this.editedIndex], this.editedItem);
				let send = this.editedItem;
				send.name = upperConverter(send.name);
				let url = "api/user/";

				url = url + send.id;
				url = `${url}?${"name=" + send.name}&${"password=" + send.password}&${
					"rol_id=" + this.selectrol
				}`;

				axios
					.put(url)
					.then((response) => {
						response;
						store.commit("increment", 1);
					})
					.catch((error) => console.log(error));
			} else {
				this.usersArray.push(this.editedItem);
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
</style>