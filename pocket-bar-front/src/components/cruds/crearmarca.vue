<template>
	<v-dialog content-class="elevation-0" v-model="dialogmarca" max-width="40rem" persistent
		:dark="this.$store.getters.hasdarkflag">
		<v-card v-on:keyup.enter="submit()" class="cont-card" elevation="2">
			<v-toolbar :dark="this.$store.getters.hasdarkflag" flat color="transparent">
				<v-btn v-shortkey="['esc']" icon color="dark" @shortkey="onClose()" @click.prevent="onClose()">
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Crear marcas</v-toolbar-title>
			</v-toolbar>
			<v-row>
				<v-col sm="6" md="12" lx="13">
					<v-text-field v-model="nombre_marca" :counter="10" label="Nombre marca" required></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<v-textarea v-model="descripcion_marca" :counter="120" color="teal">
						<template v-slot:label>
							<div>Descripción marca <small>(opcional)</small></div>
						</template>
					</v-textarea>
				</v-col>
			</v-row>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="grey darken-2" @click.prevent="clear" outlined>
					<v-icon>mdi-eraser</v-icon>
				</v-btn>
				<v-btn color="yellow darken-2" class="mr-4" v-shortkey="['enter']" @shortkey="submit"
					@click.prevent="submit()" outlined>
					Guardar marca
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { postMarcas } from "@/api/marcas.js";
import store from "@/store";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	name: "crearmarca",
	props: {
		dialogmarca: { default: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		nombre_marca: "",
		descripcion_marca: "",
	}),

	methods: {
		onClose() {
			/*Envia parametro de cierre a componente creación*/
			this.$emit("update:dialogmarca", false);
		},
		submit() {
			//this.$emit("dialogFromChild", false);
			store.commit("setsuccess", null); //para resetear el valor de la notificion en una nueva entrada
			store.commit("setdanger", null);

			this.nombre_marca = upperConverter(this.nombre_marca);
			const formdata = new FormData();
			formdata.append("name", this.nombre_marca);
			formdata.append("description", this.descripcion_marca);
			postMarcas(formdata);
			this.clear();
		},
		clear() {
			(this.nombre_marca = ""), (this.descripcion_marca = "");
		},
	},
};
</script>

<style scoped>
.cont-card {
	padding: 1rem;
}
</style>