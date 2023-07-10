<template>
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-btn
			large
			elevation="2"
			v-shortkey="['ctrl', 'shift', 'm']"
			@shortkey="dialogmarca = !dialogmarca"
			:dark="$store.getters.hasdarkflag"
			:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
			@click.prevent="dialogmarca = !dialogmarca"
		>
			<v-icon left large :dark="$store.getters.hasdarkflag">mdi-plus</v-icon>
			Marca
		</v-btn>

		<crearmarca :dialogmarca.sync="dialogmarca" />
	</div>
</template>

<script>
import crearmarca from "../cruds/crearmarca.vue";

export default {
	name: "crearlist",

	components: {
		crearmarca,
	},
	methods: {},
	data: () => ({
		dialogmarca: false,
	}),
};
</script>
<style scoped>
</style>