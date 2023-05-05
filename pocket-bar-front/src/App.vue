<template>
	<div
		id="app"
		:class="[$store.getters.hasdarkflag === true ? 'black-main' : 'white-main']"
	>
		<v-snackbar :value="hassuccess" :timeout="timeout" color="#C0CA33"
			><v-row
				><b class="mt-2">¡Operación exitosa!</b> <v-spacer></v-spacer
				><v-icon large>mdi-checkbox-marked-circle</v-icon></v-row
			>
		</v-snackbar>
		<v-snackbar
			:value="hasdanger"
			:timeout="timeout"
			color="#F4511E"
			rounded="pill"
			><v-row>
				<b class="mt-2">¡Oops hubo un problema!</b> <v-spacer></v-spacer
				><v-icon large>mdi-alert</v-icon></v-row
			>
		</v-snackbar>
		<vue-extend-layouts />
	</div>
</template>
<script>
import VueExtendLayouts from "vue-extend-layout";
import store from "@/store";
export default {
	name: "App",
	components: {
		VueExtendLayouts,
	},
	data: () => ({
		timeout: 2000,
	}),
	computed: {
		hassuccess() {
			return store.getters.hassuccess;
		},
		hasdanger() {
			return store.getters.hasdanger;
		},
		darkflag() {
			return this.$store.getters.hasdarkflag;
		},
	},
	created() {
		/*para ejecutar lo que yo quiera automaticamente*/
	},
	mounted() {},
	methods: {},
};
</script>
<style >
.button-list{
	inline-size: 3rem !important;
					height: 2.6rem !important;
padding: 0px !important;
min-width: 0px !important;
}
.v-expansion-panel-content__wrap {
	padding: 0 !important;
}
.v-application--wrap {
	min-height: 0vh !important;
}

p {
	text-transform: capitalize;
	font-size: 14px;
}
.v-data-table > .v-data-table__wrapper > table > tbody > tr > td,
.v-data-table > .v-data-table__wrapper > table > thead > tr > td,
.v-data-table > .v-data-table__wrapper > table > tfoot > tr > td {
	vertical-align: inherit;
	margin: 0px !important;
}

span {
	text-transform: capitalize;
	font-size: 14px;
}
.v-data-table__expanded__content {
	box-shadow: none !important;
	border-top-style: dotted;
	border-width: 2px 20px;
	border-color: grey;
	text-align: start;
}
.v-application {
  font-family: "Cascadia code", sans-serif !important;
	}
#app {
	font-family: "Cascadia code", system-ui, -apple-system, BlinkMacSystemFont,
		Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif,
		Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #333333;
}
/* Scroller classes */
::-webkit-scrollbar {
	inline-size: 8px;
}
::-webkit-scrollbar-track {
	background: #eeeeee;

	/* border-radius: 5px; */
}
::-webkit-scrollbar-thumb {
	background: #bdbdbd;
	/* border-radius: 5px; */
	transition: background 250ms ease-out;
}
::-webkit-scrollbar-thumb:hover {
	background: #bdbdbd;
}

.black-main {
	background-color: #272727;
}
.white-main {
	background-color: #fff;
}
.v-expansion-panel-header__icon {
	background-color: #ffca28;

	border-radius: 5px;
	inline-size: 3rem;
	height: 2.6rem;
	justify-content: center;
}
</style>
