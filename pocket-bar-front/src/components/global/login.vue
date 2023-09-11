<template>
	<div class="logcard">
		<v-card v-on:keyup.enter="login()" class="cont-card" elevation="2" :dark="this.$store.getters.hasdarkflag">
			<v-card-title class="fade-in-title" style="font-size: 3rem"><code class="font-weight-light">Pocket</code><strong
					:class="[
						$store.getters.hasdarkflag === true
							? 'black-mode-text'
							: 'white-mode-text',
					]">
					bar
				</strong>
				<v-progress-circular :class="[
					$store.getters.hasdarkflag === true
						? 'black-mode-text'
						: 'white-mode-text',
				]" v-show="cargando == true" :active="cargando" :indeterminate="cargando"
					:size="30"></v-progress-circular></v-card-title>

			<v-text-field v-model="name" label="Nombre" required></v-text-field>
			<v-text-field :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'" v-model="password"
				:type="show3 ? 'text' : 'password'" class="input-group--focused" :counter="8" label="contraseña"
				:error-messages="passwordErrors" required @click:append="show3 = !show3" @input="$v.password.$touch()"
				@blur="$v.password.$touch()" loading>
				<template v-slot:progress>
					<v-progress-linear :value="progress" :color="color" absolute height="2"></v-progress-linear>
				</template>
			</v-text-field>
			<v-card-actions>
				<v-btn large @click.prevent="clear">
					<v-icon>mdi-eraser</v-icon>
				</v-btn>
				<v-spacer></v-spacer>
				<v-btn large color="#4caf50" :dark="$store.getters.hasdarkflag" class="mr-4" v-on:click="login()">
					Iniciar sesión
				</v-btn>
			</v-card-actions>
		</v-card>
	</div>
</template>

<script>
const { required, minLength } = require("vuelidate/lib/validators");
import { LogIn } from "@/api/usuarios.js";
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
			LogIn(enviar).then((resp) => {
				if (resp.response != null) {
					console.log(resp);
					this.cargando
				}
			}).catch((err) => {
				this.cargando = false;
				err;
				alert("Error al iniciar sesión");
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
	inline-size: 24em;
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