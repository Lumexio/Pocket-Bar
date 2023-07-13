<template>
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-tooltip open-delay="500" left>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					class="button-main"
					large
					elevation="2"
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					v-shortkey="['ctrl', 'shift', 'c']"
					@shortkey="dialogcategoria = !dialogcategoria"
					@click.prevent="dialogcategoria = !dialogcategoria"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					Categoria
				</v-btn>
			</template>
			<code>abrir y cerrar:ctrl+shift+c</code>
		</v-tooltip>
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
.button-main {
	margin-block-start: 10px;
	justify-content: start !important;
	min-inline-size: 9rem !important;
}
</style>