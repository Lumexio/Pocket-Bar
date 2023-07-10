<template >
	<!--
  En esta tarjeta se encuentra el listado de componentes para crear todos los elementos
  dentro del sistema.
-->
	<div>
		<v-tooltip open-delay="500" left>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					large
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					class="mt-3 button-main"
					v-shortkey="['ctrl', 'shift', 'a']"
					@shortkey="dialogarticulo = !dialogarticulo"
					elevation="2"
					@click.prevent="dialogarticulo = true"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					Artículos
				</v-btn>
			</template>
			<code>abrir y cerrar:ctrl+shift+a</code>
		</v-tooltip>

		<v-tooltip open-delay="500" left>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					large
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					class="mt-3 button-main"
					v-shortkey="['ctrl', 'shift', 'c']"
					@shortkey="dialogcategoria = !dialogcategoria"
					elevation="2"
					@click.prevent="dialogcategoria = true"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					Categoría
				</v-btn>
			</template>
			<code>abrir y cerrar:ctrl+shift+c</code>
		</v-tooltip>

		<v-tooltip open-delay="500" left>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					large
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					class="mt-3 button-main"
					v-shortkey="['ctrl', 'shift', 'm']"
					@shortkey="dialogmarca = !dialogmarca"
					elevation="2"
					@click.prevent="dialogmarca = true"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					Marca
				</v-btn>
			</template>
			<code>abrir y cerrar:ctrl+shift+m</code>
		</v-tooltip>

		<v-tooltip open-delay="500" left>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					large
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					class="mt-3 button-main"
					v-shortkey="['ctrl', 'shift', 't']"
					@shortkey="dialogtipo = !dialogtipo"
					elevation="2"
					@click.prevent="dialogtipo = true"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					Tipo
				</v-btn>
			</template>
			<code>abrir y cerrar:ctrl+shift+t</code>
		</v-tooltip>

		<v-tooltip open-delay="500" left>
			<template v-slot:activator="{ on, attrs }">
				<v-btn
					large
					:dark="$store.getters.hasdarkflag"
					:color="$store.getters.hasdarkflag ? 'deep-purple darken-1' : ''"
					class="mt-3 button-main"
					v-shortkey="['ctrl', 'shift', 'p']"
					@shortkey="dialogproveedor = !dialogproveedor"
					elevation="2"
					@click.prevent="dialogproveedor = true"
					v-bind="attrs"
					v-on="on"
				>
					<v-icon left large>mdi-plus</v-icon>
					Proveedor
				</v-btn>
			</template>
			<code>abrir y cerrar:ctrl+shift+p</code>
		</v-tooltip>

		<!--<v-row class="ma-2">
          <v-btn elevation="2"
                :dark="$store.getters.hasdarkflag"  @click.prevent="dialogstatus = !dialogstatus">
            Status
          </v-btn>
        </v-row>-->

		<creararticulo :key="count" :dialogarticulo.sync="dialogarticulo" />
		<crearcategoria :dialogcategoria.sync="dialogcategoria" />
		<crearmarca :dialogmarca.sync="dialogmarca" />
		<creartipo :dialogtipo.sync="dialogtipo" />
		<crearproveedor :dialogproveedor.sync="dialogproveedor" />
		<!--<crearstatus
      :parentdialog="dialogstatus"
      v-on:dialogFromChild="syncFromStatus($event)"
     
    />-->
	</div>
</template>

<script>
import creararticulo from "../cruds/creararticulos.vue";
import crearcategoria from "../cruds/crearcategoria.vue";
import crearmarca from "../cruds/crearmarca.vue";
import creartipo from "../cruds/creartipo.vue";
import crearproveedor from "../cruds/crearproveedor.vue";

//import crearstatus from "../cruds/crearstatus.vue";

import store from "@/store";

export default {
	name: "crearlist",

	components: {
		creararticulo,
		crearcategoria,
		crearmarca,
		creartipo,
		crearproveedor,
		//crearstatus,
	},
	computed: {
		count() {
			return store.getters.counter;
		},
	},

	data: () => ({
		dialogarticulo: false,
		dialogcategoria: false,
		dialogmarca: false,
		dialogtipo: false,
		dialogproveedor: false,
		//dialogstatus: false,
	}),
};
</script>
<style scoped>
.button-main {
	justify-content: start !important;
	min-inline-size: 9rem !important;
}
</style>