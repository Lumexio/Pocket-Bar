<template>
	<div>
		<v-row>
			<v-col sm="6" md="10">
				<tablaArticulos />
			</v-col>
			<v-col sm="6" md="2">
				<creacionlist />
			</v-col>
		</v-row>
	</div>
</template>
<script>
import tablaArticulos from "../components/table-lists/tabla-articulos.vue";
import creacionlist from "../components/global/creacion.vue";
export default {
	name: "artículos",
	components: { tablaArticulos, creacionlist },
};
</script>