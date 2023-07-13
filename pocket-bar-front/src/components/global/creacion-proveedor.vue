
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
			v-shortkey="['ctrl', 'shift', 'p']"
			@shortkey="dialogproveedor = !dialogproveedor"
			:dark="$store.getters.hasdarkflag"
			:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
			@click.prevent="dialogproveedor = !dialogproveedor"
		>
			<v-icon left large :dark="$store.getters.hasdarkflag">mdi-plus</v-icon>
			Proveedor
		</v-btn>

		<crearproveedor :dialogproveedor.sync="dialogproveedor" />
	</div>
</template>

<script>
import crearproveedor from "../cruds/crearproveedor.vue";

export default {
	name: "crearlist",
	components: {
		crearproveedor,
	},

	data: () => ({
		dialogproveedor: false,
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