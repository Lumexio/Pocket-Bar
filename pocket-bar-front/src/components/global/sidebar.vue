<template>
	<v-navigation-drawer :dark="this.$store.getters.hasdarkflag" permanent app>
		<v-list nav expand dense>
			<v-toolbar flat color="transparent">
				<v-list-item-title style="font-size: 20px" class="text-uppercase">
					<code class="font-weight-light">Pocket</code><strong :class="[
						this.$store.getters.hasdarkflag === true
							? 'black-mode-text'
							: 'white-mode-text',
					]">bar</strong>
				</v-list-item-title>
				<v-switch class="mt-6" v-model="switchdark" color="success" flat></v-switch></v-toolbar>

			<v-divider></v-divider>
			<v-list-item-group color="primary">
				<v-list v-if="hasrol === 1" flat>
					<v-list-item v-for="item in itemsmain" :key="item.title" link :to="item.path" v-shortkey="{
						usuarios: ['ctrl', 'u'],
						articulos: ['ctrl', 'a'],
						historial: ['ctrl', 'h'],
					}" @shortkey="paths">
						<v-list-item-icon>
							<v-icon>{{ item.icon }}</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>{{ item.title }}</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list>
				<v-list v-else-if="hasrol === 2" flat>
					<v-list-item v-for="item in itemsemp" :key="item.title" link :to="item.path" v-shortkey="{
						articulos: ['ctrl', 'a'],
					}" @shortkey="paths">
						<v-list-item-icon>
							<v-icon>{{ item.icon }}</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>{{ item.title }}</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list>
				<v-list v-else-if="hasrol === 3" flat>
					<v-list-item v-for="item in itemscajero" :key="item.title" link :to="item.path" v-shortkey="{
						articulos: ['ctrl', 'a'],
					}" @shortkey="paths">
						<v-list-item-icon>
							<v-icon>{{ item.icon }}</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>{{ item.title }}</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list>
				<v-list v-else-if="hasrol === 6" flat>
					<v-list-item v-for="item in itemsguardias" :key="item.title" link :to="item.path" v-shortkey="{
						articulos: ['ctrl', 'g'],
					}" @shortkey="paths">
						<v-list-item-icon>
							<v-icon>{{ item.icon }}</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>{{ item.title }}</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list>
			</v-list-item-group>

			<v-list-group v-if="hasrol === 2 || hasrol === 1" :value="true" no-action sub-group>
				<template v-slot:activator>
					<v-list-item-content>
						<v-list-item-title>Catálogos </v-list-item-title>
					</v-list-item-content>
				</template>
				<v-list-item v-for="item in itemstable" :key="item.title" link flat :to="item.path" v-shortkey="{
					categorias: ['ctrl', 'c'],
					marcas: ['ctrl', 'm'],
					proveedores: ['ctrl', 'p'],
					tipos: ['ctrl', 't'],
				}" @shortkey="paths">
					<v-list-item-title>{{ item.title }}</v-list-item-title>

					<v-list-item-icon>
						<v-icon>{{ item.icon }}</v-icon>
					</v-list-item-icon>
				</v-list-item>
			</v-list-group>
		</v-list>
		<v-divider></v-divider>
		<template v-slot:append>
			<div class="pa-2">
				<v-btn large dark class="mr-6" v-on:click="dialogLogout = true" elevation="2">
					<v-icon left>mdi-logout</v-icon> Cerrar sesión
				</v-btn>
			</div>
			<modalConfirmation :dialogConfirmation.sync="dialogLogout">
				<template v-slot:titledialog> ¿Quieres cerrar sesión? </template>
				<template v-slot:buttonsuccess>
					<v-btn large :disabled="cargando == true" :color="$store.getters.hasdarkflag ? 'lime darken-1' : 'lime lighten-1'
						" @click.prevent="logoutConfirm">
						<span v-show="cargando == false">confirmar</span>
						<v-progress-circular v-show="cargando == true" :active="cargando" :indeterminate="cargando"
							:size="20"></v-progress-circular>
					</v-btn>
				</template>
			</modalConfirmation>
		</template>
	</v-navigation-drawer>
</template>
<script>
import store from "@/store.js";
import router from "@/router";
import modalConfirmation from "../global/modal-confirmation.vue";
import { Logout } from "@/api/usuarios.js";
export default {
	name: "sidebar",
	components: { modalConfirmation },
	data: () => ({
		dialogLogout: false,
		switchdark: false,
		cargando: false,
		itemsmain: [
			{ path: "/usuarios", title: "Usuarios", icon: "mdi-account-multiple" },
			{
				path: "/articulos",
				title: "Artículos",
				icon: "mdi-folder-multiple",
			},
			{
				path: "/historial",
				title: "Historial",
				icon: "mdi-folder-multiple",
			},
			{
				path: "/ordenes",
				title: "Ordenes",
				icon: "mdi-folder-multiple",
			},
		],
		itemsemp: [
			{
				path: "/articulos",
				title: "Artículos",
				icon: "mdi-folder-multiple",
			},
		],
		itemscajero: [
			{
				path: "/ordenes",
				title: "Ordenes",
				icon: "mdi-folder-multiple",
			},

			{
				path: "/pagos",
				title: "Cortes y ganancias",
				icon: "mdi-cash-multiple",
			},
		],
		itemsguardias: [
			{
				path: "/guardias",
				title: "Guardias",
				icon: "mdi-folder-multiple",
			},
		],
		itemstable: [
			{
				path: "/categorias",
				title: "Categorias",
				icon: "mdi-folder-multiple",
			},
			{
				path: "/marcas",
				title: "Marcas",
				icon: "mdi-folder-multiple",
			},
			{
				path: "/tipos",
				title: "Tipos",
				icon: "mdi-folder-multiple",
			},
			{
				path: "/proveedores",
				title: "Proveedores",
				icon: "mdi-folder-multiple",
			},

			{
				path: "/mesas",
				title: "Mesas",
				icon: "mdi-folder-multiple",
			},
		],
	}),
	computed: {
		hasrol() {
			return store.getters.hasrol;
		},
	},
	watch: {
		switchdark(val) {
			this.$store.commit("setdarkflag", val);
		},
	},
	created() {
		this.checkDark();
	},
	methods: {
		logoutConfirm() {
			this.cargando = true;
			Logout()
				.then((res) => {
					if (res.status === 200) {
						this.logout()
						this.cargando = false;
						this.dialogLogout = false;

					}
				})
				.catch((error) => {
					if (error.data.message) {
						this.message = error.data.message;
						this.cargando = true;
						setTimeout(() => {
							this.message = "";
							this.cargando = false;
						}, 2000);
					}
				});
		},
		closeDelete() {
			this.dialogDelete = false;
		},
		checkDark() {
			if (this.$store.getters.hasdarkflag === true) {
				this.switchdark = true;
			}
		},
		paths(event) {
			switch (event.srcKey) {
				case "usuarios":
					router.push("/usuarios").catch(() => { });
					break;
				case "articulos":
					router.push("/articulos").catch(() => { });
					break;
				case "historial":
					router.push("/historial").catch(() => { });
					break;
				case "categorias":
					router.push("/categorias").catch(() => { });
					break;
				case "marcas":
					router.push("/marcas").catch(() => { });
					break;
				case "tipos":
					router.push("/tipos").catch(() => { });
					break;
				case "guardias":
					router.push("/guardias").catch(() => { });
					break;
				case "proveedores":
					router.push("/proveedores").catch(() => { });
					break;
				default:
					break;
			}
		},
		logout() {
			let commit = (store.state.token = null);
			this.$store.dispatch("logout", commit);
			this.$router.push("/");
		},
	},
};
</script>
<style lang="scss">
#app {
	font-family: "Cascadia code", Verdana, Tahoma, sans-serif, Helvetica, Arial,
		sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #396796;
}

#nav {
	padding: 30px;

	a {
		color: #2c3e50;

		&.router-link-exact-active {
			color: #ffffff;
		}
	}
}

.black-mode-text {
	color: yellowgreen;
}

.theme--dark.v-item--active {
	color: yellowgreen !important;
}

.theme--dark.v-list-item--active {
	color: yellowgreen !important;
}

// .theme--light.v-list-item--active {
// 	color: black !important;
// }
.white-mode-text {
	color: black;
}
</style>
