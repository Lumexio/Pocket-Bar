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
					v-shortkey="['ctrl', 'shift', 'm']"
					@shortkey="dialogmarca = !dialogmarca"
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					@click.prevent="dialogmarca = !dialogmarca"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large :dark="$store.getters.hasdarkflag"
						>mdi-plus</v-icon
					>
					Marca
				</v-btn>
			</template>
		</v-tooltip>
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
.button-main {
	margin-block-start: 10px;
	justify-content: start !important;
	min-inline-size: 9rem !important;
}
</style>