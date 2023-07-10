<template>
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-btn
			large
			elevation="2"
			:dark="$store.getters.hasdarkflag"
			:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
			v-shortkey="['ctrl', 'shift', 'c']"
			@shortkey="dialogcategoria = !dialogcategoria"
			@click.prevent="dialogcategoria = !dialogcategoria"
		>
			<v-icon left large>mdi-plus</v-icon>
			Categoria
		</v-btn>

		<crearcategoria :dialogcategoria.sync="dialogcategoria" />
	</div>
</template>

<script>
import crearcategoria from "../cruds/crearcategoria.vue";

export default {
	name: "crearlist",

	components: {
		crearcategoria,
	},

	data: () => ({
		dialogcategoria: false,
	}),
};
</script>
<style scoped>
</style>