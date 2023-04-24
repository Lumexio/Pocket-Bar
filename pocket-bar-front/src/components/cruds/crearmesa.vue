<template>
	<v-dialog
		content-class="elevation-0"
		v-model="dialogmesa"
		max-width="40rem"
		persistent
		:dark="this.$store.getters.hasdarkflag"
	>
		<v-card v-on:keyup.enter="submit()" class="cont-card">
			<v-toolbar
				:dark="this.$store.getters.hasdarkflag"
				flat
				color="transparent"
			>
				<v-btn
					v-shortkey="['esc']"
					icon
					color="dark"
					@shortkey="onClose()"
					@click.prevent="onClose()"
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Crear mesas</v-toolbar-title>
			</v-toolbar>
			<v-row
				><v-col>
					<v-text-field
						v-model="nombre_mesa"
						:counter="10"
						label="Nombre mesa"
						required
					></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<v-textarea v-model="descripcion_mesa" :counter="120" color="teal">
						<template v-slot:label>
							<div>Descripción mesa <small>(opcional)</small></div>
						</template>
					</v-textarea>
				</v-col>
			</v-row>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="grey darken-2" @click.prevent="clear" outlined>
					<v-icon>mdi-eraser</v-icon>
				</v-btn>
				<v-btn
					color="yellow darken-2"
					class="mr-4"
					v-shortkey="['enter']"
					@shortkey="submit"
					@click.prevent="submit"
					outlined
				>
					Guardar mesa
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import store from "@/store";
import { postMesas } from "@/api/mesas.js";
import { upperConverter } from "@/special/uppercases-converter.js";
export default {
	name: "crearcategoria",
	props: {
		dialogmesa: { dafault: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		nombre_mesa: "",
		descripcion_mesa: "",
	}),

	methods: {
		onClose() {
			/*Envia parametro de cierre a componente creación*/
			this.$emit("update:dialogmesa", false);
		},
		submit() {
			store.commit("setsuccess", null);
			store.commit("setdanger", null);

			this.nombre_mesa = upperConverter(this.nombre_mesa);
			const formdata = new FormData();
			formdata.append("nombre_mesa", this.nombre_mesa);
			formdata.append("descripcion_mesa", this.descripcion_mesa);
			postMesas(formdata);
			this.clear();
		},
		clear() {
			(this.nombre_mesa = ""), (this.descripcion_mesa = "");
		},
	},
};
</script>

<style scoped>
.cont-card {
	padding: 1rem;
}
</style>