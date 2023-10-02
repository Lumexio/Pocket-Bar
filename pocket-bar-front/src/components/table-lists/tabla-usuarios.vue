<template>
	<v-data-table :dark="this.$store.getters.hasdarkflag" id="tabla" :headers="headers" :items="usersArray"
		class="elevation-1" :search="search" :custom-filter="filterOnlyCapsText.toUpperCase">
		<template v-slot:top>
			<v-toolbar flat color="transparent">
				<v-toolbar-title>Tabla usuarios</v-toolbar-title>
				<v-divider class="mx-4" inset vertical></v-divider>
				<v-spacer></v-spacer>
				<v-text-field v-model="search" label="Buscar usuario" placeholder="Nombre, correo y rol" class="mt-4"
					id="onsearch"></v-text-field>
			</v-toolbar>
			<v-progress-linear height="6" indeterminate color="cyan" :active="cargando"></v-progress-linear>
			<modalConfirmation :dialogConfirmation.sync="dialog">
				<template v-slot:titledialog>
					<h1 class="headline">{{ formTitle }}</h1>
				</template>
				<template v-slot:textmoneygeneral>
					<v-text-field v-model="editedItem.name" label="Nombre"></v-text-field>
					<v-select v-model="selectrol" :items="itemsrol" v-on="usersync()" item-text="name_rol"
						item-value="rol_id" label="Rol"></v-select>
					<v-text-field :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'" v-model="passwordFake"
						:type="show3 ? 'text' : 'password'" hint="Minimo 8 caracteres" :counter="8"
						@click:append="show3 = !show3" label="Contraseña" placeholder="Contraseña">
					</v-text-field>
					<v-text-field :disabled="passwordFake.length < 1" :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
						v-model="password" :type="show3 ? 'text' : 'password'" hint="Minimo 8 caracteres" :counter="8"
						@click:append="show3 = !show3" label="Confirmar contraseña" placeholder="Confirmar contraseña">
					</v-text-field>
				</template>
				<template v-slot:buttonsuccess>
					<v-btn v-on:keyup.enter="save" large :disabled="cargaDialog == true" :color="$store.getters.hasdarkflag
						? editedItem.active == 1
							? 'lime darken-4'
							: 'lime darken-2'
						: editedItem.active == 1
							? 'lime lighten-2'
							: 'lime accent-4'
						" @click.prevent="save">
						<span v-show="cargaDialog == false">confirmar</span>
						<v-progress-circular v-show="cargaDialog == true" :active="cargaDialog" :indeterminate="cargaDialog"
							:size="20"></v-progress-circular>
					</v-btn>
				</template>
			</modalConfirmation>
			<modalConfirmation :dialogConfirmation.sync="dialogActivate">

				<template v-slot:titledialog>
					<span v-show="editedItem.active === false" class="headline">
						¿Estas seguro de querer habilitarlo?
					</span>
					<span v-show="editedItem.active === true" class="headline">
						¿Quieres deshabilitarlo?
					</span>
				</template>
				<template v-slot:buttonsuccess>
					<v-btn v-on:keyup.enter="activateItemConfirm" large :disabled="cargaDialog == true" :color="$store.getters.hasdarkflag
						? editedItem.active == 1
							? 'red darken-4'
							: 'lime darken-2'
						: editedItem.active == 1
							? 'red lighten-2'
							: 'lime accent-4'
						" @click.prevent="activateItemConfirm">
						<span v-show="cargaDialog == false">confirmar</span>
						<v-progress-circular v-show="cargaDialog == true" :active="cargaDialog" :indeterminate="cargaDialog"
							:size="20"></v-progress-circular>
					</v-btn>
				</template>
			</modalConfirmation>
		</template>
		<template v-slot:[`item.name_rol`]="{ item }">
			<v-chip :color="getColor(item.name_rol)" :dark="$store.getters.hasdarkflag">
				{{ item.name_rol }}
			</v-chip>
		</template>
		<template v-slot:[`item.active`]="{ item }">
			<v-chip :color="getActivo(item.active)" :dark="$store.getters.hasdarkflag">
				<span v-show="(item.active === true || item.active === 1) &&
					getActivo(item.active) === `amber lighten-1`
					">En servicio</span>
				<span v-show="(item.active === false || item.active === 0) &&
					getActivo(item.active) === `cyan darken-1`
					">Fuera de servcio</span>
			</v-chip>
		</template>
		<template v-slot:[`item.actions`]="{ item }">
			<v-icon small :dark="$store.getters.hasdarkflag" @click.prevent="editItem(item)">
				mdi-pencil
			</v-icon>

			<v-icon v-show="item.active === true || item.active === 1" small :dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)">
				mdi-lightbulb-on
			</v-icon>
			<v-icon v-show="item.active === false || item.active === 0" small :dark="$store.getters.hasdarkflag"
				@click.prevent="deleteItem(item)">
				mdi-lightbulb-on-outline
			</v-icon>
		</template>
		<template v-slot:no-data>
			<span>Datos no disponibles.</span>
		</template>
	</v-data-table>
</template>

<script>
import modalConfirmation from "../global/modal-confirmation.vue";
import axios from "axios";
import { getUsuarios, putUsers } from "@/api/usuarios.js";
import { getRol } from "@/api/rol.js";
//axios.defaults.withCredentials = true;
axios.defaults.baseURL =
	"http://" + window.location.hostname /*"127.0.0.1"*/ + ":8000";
export default {
	name: "tabla-usuarios",
	components: {
		modalConfirmation,
	},
	data: () => ({
		dialog: false,
		dialogActivate: false,
		search: "",
		password: "",
		passwordFake: "",
		passwordReal: "",
		cargando: true,
		cargaDialog: false,
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
		password(val) {
			if (val === this.passwordFake) {

				this.passwordReal = val;
				console.log("iguales", val, this.passwordFake, "To send:", this.passwordReal);
			}
		},
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
			stext = addEventListener("keydown", (e) => {
				if (e.altKey) {
					document.getElementById("onsearch").focus();
				}
			});
		},
		getColor(status) {
			if (status == "Administrativo") {
				return this.$store.getters.hasdarkflag
					? "amber darken-2"
					: "amber lighten-1";
			} else if (status == "Gerencia") {
				return this.$store.getters.hasdarkflag
					? "amber darken-4"
					: "orange lighten-1";
			} else if (status == "Mesero") {
				return this.$store.getters.hasdarkflag
					? "cyan darken-1"
					: "cyan lighten-1";
			} else if (status == "Cajero") {
				return this.$store.getters.hasdarkflag
					? "purple darken-1"
					: "purple lighten-3";
			} else if (status == "Bartender") {
				return this.$store.getters.hasdarkflag
					? "pink darken-1"
					: "pink lighten-3";
			} else if (status == "Guardia") {
				return this.$store.getters.hasdarkflag
					? "indigo darken-1"
					: "indigo lighten-2";
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
			this.dialogActivate = true;
		},
		activateItemConfirm() {
			this.cargaDialog = true;
			axios.put("api/user/activate/" + this.editedItem.id).then((response) => {
				if (response.data.message === "success") {
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
			Object.assign(this.usersArray[this.editedIndex], this.editedItem);
			let send = this.editedItem;
			this.cargaDialog = true;
			putUsers(send.id, {
				name: send.name,
				password: this.passwordReal,
				rol_id: this.selectrol,
			}).then((response) => {
				if (response.resp.message === "success") {
					this.close();
					this.cargaDialog = false;
				}
			})
				.catch((error) => console.log(error));
		},
	},
};
</script>

<style scoped>
#tabla {
	inline-size: 100%;
}
</style>