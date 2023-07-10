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
			@shortkey="dialogmesa = !dialogmesa"
			@click.prevent="dialogmesa = !dialogmesa"
		>
			<v-icon left large>mdi-plus</v-icon>
			Mesa
		</v-btn>

		<crearmesa :dialogmesa.sync="dialogmesa" />
	</div>
</template>

<script>
import crearmesa from "../cruds/crearmesa.vue";

export default {
	name: "crearlistmesa",

	components: {
		crearmesa,
	},

	data: () => ({
		dialogmesa: false,
	}),
};
</script>
<style scoped>
</style>