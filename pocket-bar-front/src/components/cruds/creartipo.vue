<template>
	<v-dialog content-class="elevation-0" v-model="dialogtipo" max-width="40rem" persistent
		:dark="this.$store.getters.hasdarkflag">
		<v-card v-on:keyup.enter="submit()" class="cont-card" elevation="2">
			<v-toolbar :dark="this.$store.getters.hasdarkflag" flat color="transparent">
				<v-btn v-shortkey="['esc']" icon color="dark" @shortkey="onClose" @click.prevent="onClose">
					<v-icon> mdi-close </v-icon>
				</v-btn>
				<v-toolbar-title>Crear tipo</v-toolbar-title>
			</v-toolbar>
			<v-row>
				<v-col sm="6" md="12" lx="13">
					<v-text-field v-model="nombre_tipo" :counter="10" label="Nombre tipo" required></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<v-textarea v-model="descripcion_tipo" :counter="120" color="teal">
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
				<v-btn color="yellow darken-2" class="mr-4" v-shortkey="['enter']" @shortkey="submit" v-on:click="submit"
					outlined>
					Guardar tipo
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { postTipos } from "@/api/tipos.js";
import store from "@/store";

export default {
	name: "creartipo",
	props: {
		dialogtipo: { dafault: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		nombre_tipo: "",
		descripcion_tipo: "",
	}),

	methods: {
		onClose() {
			/*Envia parametro de cierre a componente creación*/
			this.$emit("update:dialogtipo", false);
		},
		submit() {
			//this.$emit("dialogFromChild", false);
			store.commit("setsuccess", null); //para resetear el valor de la notificion en una nueva entrada
			store.commit("setdanger", null);
			const formdata = new FormData();
			formdata.append("name", this.nombre_tipo);
			formdata.append("description", this.descripcion_tipo);
			postTipos(formdata).then((response) => {
				if (response.stats == "Created") {
					this.clear();
				}
			});

		},
		clear() {
			(this.nombre_tipo = ""), (this.descripcion_tipo = "");
		},
	},
};
</script>

<style scoped>
.cont-card {
	padding: 1rem;
}
</style>