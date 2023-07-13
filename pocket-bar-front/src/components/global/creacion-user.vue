<template>
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-btn
			class="button-main"
			large
			v-shortkey="['ctrl', 'shift', 'u']"
			@shortkey="dialogusuarios = !dialogusuarios"
			elevation="2"
			:dark="$store.getters.hasdarkflag"
			:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
			@click.prevent="dialogusuarios = true"
		>
			<v-icon left large>mdi-plus</v-icon>
			Usuarios
		</v-btn>

		<crearusuario :dialogusuarios.sync="dialogusuarios" />
	</div>
</template>

<script>
import crearusuario from "../cruds/crearusuario.vue";

import store from "@/store";
export default {
	name: "crearlist",

	components: {
		crearusuario,
	},
	computed: {
		count() {
			return store.getters.counter;
		},
	},
	methods: {
		syncFromRol(updatedDialog) {
			this.dialogrol = updatedDialog;
		},
	},
	data: () => ({
		dialogusuarios: false,
		dialogrol: false,
	}),
};
</script>
<style scoped>
.button-main {
	margin-block-start: 10px;
	justify-content: start !important;
	min-inline-size: 9rem !important;
}
</style>