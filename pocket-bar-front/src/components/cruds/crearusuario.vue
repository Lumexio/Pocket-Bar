<template>
	<v-dialog content-class="elevation-0" v-model="dialogusuarios" max-width="25rem" persistent
		:dark="this.$store.getters.hasdarkflag">
		<v-card v-on:keyup.enter="submit()" class="cont-card">
			<v-toolbar :dark="this.$store.getters.hasdarkflag" flat color="transparent">
				<v-btn v-shortkey="['esc']" icon color="dark" @shortkey="onClose" @click.prevent="onClose">
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Crear usuario</v-toolbar-title>
			</v-toolbar>
			<v-row><v-col sm="6" md="12" lx="13">
					<v-text-field v-model="name" :counter="10" label="Nombre" required></v-text-field>
				</v-col>
			</v-row>
			<!-- <v-row
        ><v-col sm="6" md="12" lx="13">
          <v-text-field v-model="email" label="Correo" required></v-text-field>
        </v-col>
      </v-row> -->
			<v-row><v-col sm="6" md="12" lx="13">
					<v-text-field v-model="password" :counter="8" :type="'password'" label="Contraseña"
						required></v-text-field>
				</v-col>
			</v-row>
			<v-row align="center">
				<v-col sm="6" md="12" lx="13">
					<v-select v-model="selectrol" :items="itemsrol" item-text="name_rol" item-value="rol_id" label="Rol">
					</v-select>
				</v-col>
			</v-row>
			<v-card-actions>
				<v-btn class="mr-4" @click.prevent="submit" text> Guardar </v-btn>
				<v-btn @click.prevent="clear" text> <v-icon>mdi-eraser</v-icon> </v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import axios from "axios";
import store from "@/store";
import { getRol } from "@/api/rol.js";
import { postUsers } from "@/api/usuarios.js";
axios.defaults.withCredentials = true;
axios.defaults.baseURL =
	"http://" + window.location.hostname /*"127.0.0.1"*/ + ":8000";
export default {
	name: "crearusuario",
	props: {
		dialogusuarios: { dafault: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		name: "",
		email: "",
		password: "",

		selectrol: null,
		itemsrol: [],
	}),
	mounted() {
		window.Echo.channel("roles").listen("rolCreated", (e) => {
			this.itemsrol = e.roles;
		});
		getRol(this.itemsrol);

	},
	methods: {
		onClose() {
			/*Envia parametro de cierre a componente creación*/
			this.$emit("update:dialogusuarios", false);
		},
		submit() {
			//this.$emit("dialogFromChild", false);
			store.commit("setsuccess", null); //para resetear el valor de la notificion en una nueva entrada
			store.commit("setdanger", null);
			let enviar = {
				name: this.name,
				email: this.email,
				password: this.password,
				rol_id: this.selectrol,
			};
			postUsers(enviar).then((response) => {
				console.log(response);
				if (response.resp.message == "success") {
					this.clear();
				}
			})
		},
		clear() {
			(this.name = ""), (this.email = ""), (this.password = ""), (this.selectrol = null);
		},
	},
};
</script>

<style scoped>
.cont-card {
	padding: 1rem;
}
</style>