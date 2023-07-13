<template >
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-tooltip
			v-for="(item, index) in buttonsArray"
			:key="index"
			open-delay="500"
			left
		>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					large
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					class="mt-3 button-main"
					v-shortkey="['ctrl', 'shift', item.shortkey]"
					@shortkey="openShortkey(item.title)"
					elevation="2"
					@click.prevent="openDialog(item.title)"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					{{ item.title }}
				</v-btn>
			</template>
			<code>{{ item.tooltipTitle }}</code>
		</v-tooltip>
		<creararticulo :dialogarticulo.sync="dialogarticulo" />
		<crearcategoria :dialogcategoria.sync="dialogcategoria" />
		<crearmarca :dialogmarca.sync="dialogmarca" />
		<creartipo :dialogtipo.sync="dialogtipo" />
		<crearproveedor :dialogproveedor.sync="dialogproveedor" />
	</div>
</template>

<script>
import creararticulo from "../cruds/creararticulos.vue";
import crearcategoria from "../cruds/crearcategoria.vue";
import crearmarca from "../cruds/crearmarca.vue";
import creartipo from "../cruds/creartipo.vue";
import crearproveedor from "../cruds/crearproveedor.vue";

export default {
	name: "crearlist",

	components: {
		creararticulo,
		crearcategoria,
		crearmarca,
		creartipo,
		crearproveedor,
	},
	mounted() {
		this.buttonsArray = [
			{
				title: "Artículos",
				shortkey: 'a',
				tooltipTitle: "abrir y cerrar:ctrl+shift+a",
			},
						{
				title: "Categoría",
				shortkey: 'c',
				tooltipTitle: "abrir y cerrar:ctrl+shift+c",
			},
			{
				title: "Marca",
				shortkey: 'm',
				tooltipTitle: "abrir y cerrar:ctrl+shift+m",
			},
			{
				title: "Tipo",
				shortkey: 't',
				tooltipTitle: "abrir y cerrar:ctrl+shift+t",
			},
			{
				title: "Proveedor",
				shortkey: 'p',
				tooltipTitle: "abrir y cerrar:ctrl+shift+p",
			}
		];
	},
	methods: {
		openDialog(val) {
			switch (val) {
				case "Artículos":
					this.dialogarticulo = true;
					break;
				case "Categoría":
					this.dialogcategoria = true;
					break;
				case "Marca":
					this.dialogmarca = true;
					break;
				case "Tipo":
					this.dialogtipo = true;
					break;
				case "Proveedor":
					this.dialogproveedor = true;
					break;
			}
		},

		openShortkey(val) {
			
			switch (val) {
				case "Artículos":
					this.dialogarticulo = !this.dialogarticulo;
					break;
				case "Categoría":
					this.dialogcategoria = !this.dialogcategoria;
					break;
				case "Marca":
					this.dialogmarca = !this.dialogmarca;
					break;
				case "Tipo":
					this.dialogtipo = !this.dialogtipo;
					break;
				case "Proveedor":
					this.dialogproveedor = !this.dialogproveedor;
					break;
			}
		},
	},
	data: () => ({
		dialogarticulo: false,
		dialogcategoria: false,
		dialogmarca: false,
		dialogtipo: false,
		dialogproveedor: false,
		buttonsArray: [],
	}),
};
</script>
<style scoped>
.button-main {
	justify-content: start !important;
	min-inline-size: 9rem !important;
}
</style>