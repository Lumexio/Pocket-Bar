<template>
	<nav>
		<div
			:class="[
				$store.getters.hasdarkflag === true ? 'title-user-dark' : 'title-user',
			]"
		>
			<v-card-title
				class="fade-in-title font-weight-light"
				style="font-size: 2rem"
				:class="[
					$store.getters.hasdarkflag === true
						? 'title-user-letter-dark'
						: 'white-mode-text',
				]"
				><code>Pocket</code
				><strong
					:class="[
						this.$store.getters.hasdarkflag === true
							? 'black-mode-text'
							: 'white-mode-text',
					]"
				>
					bar
				</strong></v-card-title
			>
			<v-spacer></v-spacer>
			<span
				class="mr-6"
				:class="[
					$store.getters.hasdarkflag === true
						? 'title-user-letter-dark'
						: 'white-mode-text',
				]"
				>{{ typeUser }}</span
			>
		</div>
		<v-toolbar :dark="darkonchange" flat>
			<v-btn @click="dialogaccount = true" outlined>
				<v-icon>mdi-account-cog</v-icon>
			</v-btn>
			<v-btn class="mr-1 ml-1" @click="dialoglistorden = true" outlined>
				<v-icon>mdi-bell</v-icon>
			</v-btn>
			<v-spacer></v-spacer>
			<v-btn outlined @click="dialogorden = true">
				<v-icon>mdi-plus</v-icon>
			</v-btn>

			<template v-slot:extension>
				<v-tabs
					style="padding-right: 0px; width: 1%"
					next-icon="mdi-arrow-right-bold-box-outline"
					prev-icon="mdi-arrow-left-bold-box-outline"
					show-arrows
					v-model="tabs"
					grow
					left
				>
					<v-tab style="padding: 2px" v-for="item in items" :key="item.id">
						<span @click="statuschange(item.status)"> {{ item.title }}</span>
					</v-tab>
				</v-tabs>
			</template>
		</v-toolbar>

		<tomarorden :dialogorden.sync="dialogorden" />
		<notiorden :dialoglistorden.sync="dialoglistorden" />
		<account-settings :dialogaccount.sync="dialogaccount" />
	</nav>
</template>

<script>
import tomarorden from "@/pwa-components/forms/crear-orden.vue";
import notiorden from "@/pwa-components/tables/lista-notificaciones-ordenes.vue";
import AccountSettings from "@/pwa-components/tables/account-settings.vue";
import store from "@/store";
export default {
	name: "navbarPwa",
	components: {
		tomarorden,
		notiorden,
		AccountSettings,
	},
	data() {
		return {
			type: null,
			tabs: null,
			dialogorden: false,
			dialoglistorden: false,
			dialogaccount: false,
			items: [
				{ id: 1, title: "Por entregar", status: "Por entregar" },
				{ id: 2, title: "Entregadas", status: "Entregado" },
				{ id: 3, title: "Cerradas", status: "Cerrado" },
			],
		};
	},

	methods: {
		clear() {
			store.commit("RESET");
			store.commit("SET_TOKEN", null);
		},
		statuschange(title) {
			store.commit("setstatus", title);
			store.commit("increment", 1);
		},
	},
	computed: {
		darkonchange() {
			return store.getters.hasdarkflag;
		},

		typeUser() {
			var rol = "";
			rol;
			store.getters.hasrol;

			switch (store.getters.hasrol) {
				case 4:
					rol = "Meser@";
					break;
				case 5:
					rol = "Bartender";
					break;

				default:
					break;
			}
			return rol;
		},
		hasstatus() {
			return store.getters.hasstatus;
		},
	},
};
</script>

<style scoped>
.title-user {
	display: flex;
	justify-content: flex-start;
	align-items: center;
}
.title-user-dark {
	display: flex;
	justify-content: flex-start;
	align-items: center;
	background-color: #272727;
}
.title-user-letter-dark {
	color: aliceblue;
}

.black-mode-text {
	color: #9acd32;
}
.white-mode-text {
	color: black;
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
</style>