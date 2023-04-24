<template>
	<v-toolbar prominent :dark="darkonchange" fixed style="flex: none">
		<h1
			class="fade-in-title font-weight-light"
			:class="[
				$store.getters.hasdarkflag === true
					? 'title-user-letter-dark'
					: 'white-mode-text',
			]"
		>
			<code>Pocket</code
			><strong
				:class="[
					$store.getters.hasdarkflag === true
						? 'black-mode-text'
						: 'white-mode-text',
				]"
			>
				bar
			</strong>
		</h1>
		<v-spacer></v-spacer>

		<v-badge
			overlap
			color="green"
			bottom
			left
			:value="$store.getters.hasorders"
			:content="$store.getters.hasorders"
		>
			<v-btn
				large
				class="mr-1 ml-1"
				@click.prevent="dialoglistorden = true"
				icon
			>
				<v-icon>mdi-bell</v-icon>
			</v-btn>
		</v-badge>
		<v-btn large @click.prevent="dialogaccount = true" icon>
			<v-icon>mdi-account-cog</v-icon>
		</v-btn>
		<template v-slot:extension>
			<v-tabs v-model="tabs" icons-and-text>
				<v-tab
					@click.prevent="statuschange(item.status)"
					v-for="item in items"
					:key="item.id"
				>
					<v-icon> {{ item.icon }}</v-icon>
				</v-tab>
			</v-tabs>
		</template>
		<notiorden :dialoglistorden.sync="dialoglistorden"  />
		<account-settings :dialogaccount.sync="dialogaccount" />
	</v-toolbar>
</template>

<script>
import notiorden from "@/pwa-components/tables/lista-notificaciones-ordenes.vue";
import AccountSettings from "@/pwa-components/tables/account-settings.vue";
import store from "@/store";
export default {
	name: "navbarPwa",
	components: {
		notiorden,
		AccountSettings,
	},
	data() {
		return {
			type: null,
			tabs: 1,
			dialoglistorden: false,
			dialogaccount: false,
			items: [
				{
					id: 0,
					title: "Por entregar",
					status: "Por entregar",
					icon: "mdi-package",
				},
				{
					id: 1,
					title: "Entregadas",
					status: "Entregado",
					icon: "mdi-package-check",
				},
				{
					id: 2,
					title: "Cerradas",
					status: "Cerrado",
					icon: "mdi-package-variant-closed",
				},
			],
		};
	},
	mounted() {
		this.test();
	},
	methods: {
		
		test() {
			this.items.forEach((element) => {
				if (element.status == store.getters.hasstatus) {
					return (this.tabs = element.id);
				}
			});
		},
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