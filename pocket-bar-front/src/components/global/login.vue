<template>
	<div class="logcard">
		<v-card
			v-on:keyup.enter="login()"
			class="cont-card"
			elevation="2"
			:dark="this.$store.getters.hasdarkflag"
		>
			<v-card-title class="fade-in-title" style="font-size: 3rem"
				><code class="font-weight-light">Pocket</code
				><strong
					:class="[
						this.$store.getters.hasdarkflag === true
							? 'black-mode-text'
							: 'white-mode-text',
					]"
				>
					bar
				</strong>
				<v-progress-circular
					:class="[
						this.$store.getters.hasdarkflag === true
							? 'black-mode-text'
							: 'white-mode-text',
					]"
					v-show="cargando == true"
					:active="cargando"
					:indeterminate="cargando"
					:size="30"
				></v-progress-circular
			></v-card-title>

			<v-text-field v-model="name" label="Nombre" required></v-text-field>
			<v-text-field
				:append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
				v-model="password"
				:type="show3 ? 'text' : 'password'"
				class="input-group--focused"
				:counter="8"
				label="contraseña"
				:error-messages="passwordErrors"
				required
				@click:append="show3 = !show3"
				@input="$v.password.$touch()"
				@blur="$v.password.$touch()"
				loading
			>
				<template v-slot:progress>
					<v-progress-linear
						:value="progress"
						:color="color"
						absolute
						height="2"
					></v-progress-linear>
				</template>
			</v-text-field>
			<v-card-actions>
				<v-btn x-large @click="clear"> limpiar </v-btn>
				<v-spacer></v-spacer>
				<v-btn x-large color="#4caf50" dark class="mr-4" v-on:click="login()">
					Iniciar sesión
				</v-btn>
			</v-card-actions>
		</v-card>
	</div>
</template>

<script>
const { required, minLength } = require("vuelidate/lib/validators");
import axios from "axios";

import store from "@/store";
import router from "@/router";

axios.defaults.withCredentials = true;
axios.defaults.baseURL =
	"http://" + window.location.hostname /*"127.0.0.1"*/ + ":8000";
export default {
	name: "crearusuario",
	data: () => ({
		name: "",
		email: "", //a@a.com//b@b.com
		password: "", //12345678
		show3: false,
		cargando: false,
	}),

	validations: {
		name: { required },
		password: { required, minLength: minLength(8) },
	},
	computed: {
		passwordErrors() {
			const errors = [];
			if (!this.$v.password.$dirty) return errors;
			!this.$v.password.minLength &&
				errors.push("La contraseña debe ser 8 caracteres de largo, minimo.");
			!this.$v.password.required && errors.push("Contaseña requerida.");
			return errors;
		},
		progress() {
			return Math.min(100, this.password.length * 13);
		},
		color() {
			return ["error", "warning", "success"][Math.floor(this.progress / 40)];
		},
	},

	methods: {
		async login() {
			this.$v.$touch();
			this.name = this.name.trim();
			let enviar = {
				name: this.name,
				password: this.password,
			};

			this.cargando = true;
			axios
				.get("sanctum/csrf-cookie")
				.then((response) => {
					response;
					axios
						.post("api/login", enviar)
						.then((response) => {
							let rol = response.data.user.rol_id;
							let userId = response.data.user.id;

							store.commit("setrol", rol);
							console.log("Rol de usuario:", store.getters.hasrol);
							store.commit("setUserId", userId);
							let validado = response.request.withCredentials;

							if (validado == true) {
								this.cargando = false;
								store.commit("SET_TOKEN", response.data.token);
								let token = store.state.token;
								store.dispatch("login", { token });
								switch (rol) {
									case 1:
										router.push("/usuarios").catch(() => {});
										break;
									case 2:
										router.push("/articulos").catch(() => {});
										break;
									case 3:
										router.push("/ordenes").catch(() => {});
										break;
									case 4:
										router.push("/mesero").catch(() => {});
										break;
									case 5:
										router.push("/barra").catch(() => {});
										break;
									default:
										alert("Cuanta no existe o es incorrecta");
										break;
								}
							} else if (validado == false) {
								alert("Cuanta no existe o es incorrecta");
							}
						})
						.catch((e) => {
							if (e) {
								this.cargando = false;
								console.log("pppp", e);
							}
						});
				})
				.catch((e) => {
					console.log(e);
				});
		},

		clear() {
			this.name = "";
			this.password = "";
		},
	},
};
</script>

<style scoped>
.logcard {
	display: flex;
	align-items: center;
	justify-content: center;
	padding-top: 15rem;
}

.cont-card {
	padding: 1rem;
	width: 24em;
}

.fade-in-title {
	animation: fadeIn 5s;
	-webkit-animation: fadeIn 5s;
	-moz-animation: fadeIn 5s;
	-o-animation: fadeIn 5s;
	-ms-animation: fadeIn 5s;
}
@keyframes fadeIn {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@-moz-keyframes fadeIn {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@-webkit-keyframes fadeIn {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@-o-keyframes fadeIn {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@-ms-keyframes fadeIn {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}
.black-mode-text {
	color: #9acd32;
}
.white-mode-text {
	color: black;
}
</style>