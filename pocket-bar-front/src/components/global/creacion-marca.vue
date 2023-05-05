<template>
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div style="inline-size: 64%" class="pa-3">
		<v-row>
			<v-col align-self="end" cols="2">
				<v-row>
					<v-btn
						large
						elevation="2"
						color="primary"
						v-shortkey="['ctrl', 'shift', 'm']"
						@shortkey="dialogmarca = !dialogmarca"
						dark
						
						@click.prevent="dialogmarca = !dialogmarca"
					>
						<v-icon left large dark>mdi-plus</v-icon>
						Marca
					</v-btn>
				</v-row>
			</v-col>
		</v-row>

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
.list-card {
	display: flex;
	align-content: center;
	justify-content: center;
	padding: 1em;

	inline-size: 7rem;
	height: 3em;
}
</style>