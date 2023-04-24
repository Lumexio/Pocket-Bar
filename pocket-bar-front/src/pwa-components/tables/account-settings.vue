<template>
	<v-dialog
		v-model="dialogaccount"
		fullscreen
		hide-overlay
		transition="dialog-bottom-transition"
		><v-card :dark="this.$store.getters.hasdarkflag">
			<v-toolbar 				color="transparent"
				elevation="0"
				prominent
				v-touch="{
					down: () => swipe('Down'),
				}">
				<v-btn
					icon
					:dark="this.$store.getters.hasdarkflag"
					@click.prevent="close()"
					large
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title
					><v-icon>mdi-account-cog</v-icon> Configuración <v-spacer></v-spacer>
					<span class="caption">{{ typeUser }}</span></v-toolbar-title
				>
				<v-spacer></v-spacer>
			</v-toolbar>

			<h2>Temas</h2>
			<v-switch
				class="ml-3"
				v-model="switchdark"
				color="success"
				flat
				:label="`Tema ${switchdark == true ? `oscuro` : `claro`}`"
			></v-switch>
			<v-divider></v-divider>
			<v-btn
				class="mt-3"
				@click.prevent="dialogLogout = true"
				block
				large
				outlined
				color="gold"
				>Cerrar sesión</v-btn
			>
		</v-card>
		<v-dialog
			:dark="$store.getters.hasdarkflag"
			v-model="dialogLogout"
			max-width="500px"
		>
			<v-card>
				<v-card-title class="headline justify-center"
					>¿Quieres cerrar sesión?</v-card-title
				>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" text @click.prevent="closeLogout"
						>Cancelar</v-btn
					>
					<v-btn color="success" @click.prevent="logoutConfirm">Aceptar</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-dialog>
</template>

<script>
import store from "@/store";
import router from "@/router";
import { Logout } from "@/api/usuarios.js";
export default {
	name: "configurionCuenta",
	props: {
		dialogaccount: { default: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		swipeDirection: "None",
		switchdark: false,
		dialogLogout: false,
	}),

	methods: {
		swipe(direction) {
			if (direction === "Down") {
				this.$emit("update:dialogaccount", false);
			}
			this.swipeDirection = direction;
		},
		logoutConfirm() {
			Logout().then((res) => {
				if (res.status === 200) {
					this.toClose();
					this.closeLogout();
				}
			});
		},
		closeLogout() {
			this.dialogLogout = false;
		},
		toClose() {
			this.clear();
			router.push("/login");
		},
		clear() {
			store.commit("RESET");
			store.commit("SET_TOKEN", null);
		},
		close() {
			this.$emit("update:dialogaccount", false);
		},
		checkDark() {
			if (this.$store.getters.hasdarkflag === true) {
				this.switchdark = true;
			}
		},
	},
	watch: {
		switchdark(val) {
			store.commit("setdarkflag", val);
		},
	},
	computed: {
		typeUser() {
			var rol = "";
			rol;
			store.getters.hasrol;

			switch (store.getters.hasrol) {
				case 4:
					rol = "Meser@";
					break;
				case 5:
					rol = "Bartender";
					break;

				default:
					break;
			}
			return rol;
		},
	},
	created() {
		this.checkDark();
	},
};
</script>

<style scoped>
.white-mode-text {
	color: black;
}
.title-user-letter-dark {
	color: aliceblue;
}
</style>
