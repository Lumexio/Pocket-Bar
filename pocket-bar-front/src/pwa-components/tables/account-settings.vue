<template>
	<v-dialog
		v-model="dialogaccount"
		fullscreen
		hide-overlay
		transition="dialog-top-transition"
		><v-card :dark="this.$store.getters.hasdarkflag">
			<v-toolbar color="transparent">
				<v-btn
					icon
					:dark="this.$store.getters.hasdarkflag"
					@click="close()"
					outlined
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Configuración </v-toolbar-title>
				<v-spacer></v-spacer>
				<span
					class="mr-6"
					:class="[
						$store.getters.hasdarkflag === true
							? 'title-user-letter-dark'
							: 'white-mode-text',
					]"
					>{{ typeUser }}</span
				>
				<v-divider vertical></v-divider>
				<v-spacer></v-spacer>
				<v-icon>mdi-account-cog</v-icon>
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
			<v-btn class="mt-3" @click="toClose()" block large outlined color="gold"
				>Cerrar sesión</v-btn
			>
		</v-card>
	</v-dialog>
</template>

<script>
import store from "@/store";
import router from "@/router";
export default {
	name: "configurionCuenta",
	props: {
		dialogaccount: { default: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		switchdark: false,
	}),

	methods: {
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
