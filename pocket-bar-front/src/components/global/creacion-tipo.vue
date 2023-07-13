<template>
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-btn
			class="button-main"
			large
			elevation="2"
			:dark="$store.getters.hasdarkflag"
			:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
			v-shortkey="['ctrl', 'shift', 't']"
			@shortkey="dialogtipo = !dialogtipo"
			@click.prevent="dialogtipo = !dialogtipo"
		>
			<v-icon left large :dark="$store.getters.hasdarkflag">mdi-plus</v-icon>
			Tipo
		</v-btn>

		<creartipo :dialogtipo.sync="dialogtipo" />
	</div>
</template>

<script>
import creartipo from "../cruds/creartipo.vue";

export default {
	name: "crearlist",

	components: {
		creartipo,
	},
	data: () => ({
		dialogtipo: false,
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