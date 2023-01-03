<template>
	<v-dialog
		v-model="dialogorden"
		fullscreen
		hide-overlay
		transition="dialog-bottom-transition"
	>
		<v-card :dark="this.$store.getters.hasdarkflag === true">
			<v-toolbar
				prominent
				color="transparent"
				v-touch="{
					left: () => swipe('Left'),
					right: () => swipe('Right'),
					up: () => swipe('Up'),
					down: () => swipe('Down'),
				}"
			>
				<v-btn
					icon
					:dark="this.$store.getters.hasdarkflag === true"
					@click.prevent="close()"
					x-large
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Nueva orden</v-toolbar-title>
			</v-toolbar>
			<v-stepper fill-height v-model="e6" editable>
				<v-stepper-items>
					<v-stepper-header style="box-shadow: none">
						<v-stepper-step :complete="e6 > 1" step="1">
							Seleccionar productos
						</v-stepper-step>

						<v-divider></v-divider>

						<v-stepper-step step="2"> Lista de productos </v-stepper-step>
					</v-stepper-header>

					<v-stepper-content class="pa-0" step="1">
						<div class="ma-2 buttonsproced">
							<v-btn
								x-large
								dark
								color="red"
								v-if="pedidoArray.length > 0"
								@click.prevent="cancelarPedido()"
							>
								<v-icon>mdi-trash-can-outline </v-icon>
							</v-btn>
							<v-spacer></v-spacer>
							<v-badge
								overlap
								color="green"
								v-if="countproductos != 0"
								:content="countproductos"
							>
								<v-btn
									x-large
									color="primary"
									dark
									v-if="pedidoArray.length > 0"
									@click.prevent="e6 = 2"
								>
									<v-icon>mdi-cart-variant</v-icon>
								</v-btn>
							</v-badge>
						</div>
						<v-data-iterator
							:items="articulosArray"
							:items-per-page.sync="itemsPerPage"
							:page.sync="page"
							:search="search"
							:sort-by="sortBy.toLowerCase()"
							:sort-desc="sortDesc"
							hide-default-footer
						>
							<template v-slot:header>
								<v-toolbar
									flat
									light
									class="mb-1"
									:dark="$store.getters.hasdarkflag"
									color="transparent"
								>
									<v-text-field
										v-model="search"
										clearable
										flat
										hide-details
										prepend-inner-icon="mdi-magnify"
										label="Buscar"
									></v-text-field>
								</v-toolbar>
							</template>

							<template v-slot:default="props">
								<v-row v-for="item in props.items" :key="item.id" class="mb-3">
									<v-card
										:color="cambio(item)"
										@click.prevent="cajaProductos(item)"
										class="card-p ml-5"
										:disabled="item.cantidad_articulo == 0"
									>
										<v-img
											v-bind:lazy-src="item.foto_articulo"
											max-height="500"
											max-width="600"
											v-bind:src="item.foto_articulo"
										></v-img>
										<v-card-title
											class="subheading font-weight-bold card-prod"
											:class="[
												cambio(item) === '#272727' || cambio(item) === 'success'
													? 'wt'
													: 'blt',
											]"
										>
											{{ item.nombre_articulo }} <v-spacer></v-spacer
											>{{ item.cantidad_articulo }}
										</v-card-title>
										<v-card-text
											class="text font-weight-regular"
											style="text-align: end"
										>
											<span
												:class="[
													cambio(item) === '#272727' ||
													cambio(item) === 'success'
														? 'wt'
														: 'blt',
												]"
											>
												${{ item.precio_articulo }}</span
											>
										</v-card-text>
									</v-card>
								</v-row>
							</template>

							<template v-slot:footer>
								<v-row class="mt-2" align="center" justify="center">
									<span class="mr-4 grey--text">
										Page {{ page }} of {{ numberOfPages }}
									</span>
									<v-btn dark class="mr-1" @click.prevent="formerPage">
										<v-icon>mdi-chevron-left</v-icon>
									</v-btn>
									<v-btn dark class="ml-1" @click.prevent="nextPage">
										<v-icon>mdi-chevron-right</v-icon>
									</v-btn>
								</v-row>
							</template>
						</v-data-iterator>
						<v-spacer class="pb-6"></v-spacer>
					</v-stepper-content>

					<v-stepper-content class="pa-0" step="2">
						<div class="back">
							<v-btn
								x-large
								color="primary"
								dark
								@click.prevent="e6 = 1"
								class="mb-4 ml-2"
							>
								<v-icon>mdi-chevron-left</v-icon>
							</v-btn>
						</div>
						<v-select
							v-show="$store.getters.hasrol == 4"
							v-model="selectmesa"
							append-icon="mdi-table-furniture"
							:items="itemsmesa"
							item-text="nombre_mesa"
							item-value="id"
							label="Mesa"
							outlined
							class="ma-2"
						>
						</v-select>
						<v-select
							v-model="selectip"
							:items="itemstip"
							append-icon="mdi-percent-circle-outline"
							label="Propina %"
							outlined
							class="ma-2"
						>
						</v-select>
						<v-text-field
							label="Titular"
							append-icon="mdi-account-circle-outline"
							class="ma-2"
							v-model="titular"
							outlined
						></v-text-field>

						<v-card
							class="mt-4 mb-4"
							style="width: 100%"
							v-for="(item, index) in pedidoArray"
							:key="index"
						>
							<v-row>
								<v-col>
									<v-btn icon x-large @click.prevent="deleteProduct(index)">
										<v-icon>mdi-close</v-icon>
									</v-btn></v-col
								>
								<v-col cols="6"
									><span>{{ item.nombre_articulo }}</span></v-col
								><v-spacer></v-spacer
								><v-col
									><span class="pr-2">${{ item.precio_articulo }}</span></v-col
								>
							</v-row>
							<v-card-actions :key="refresher" class="arrowscounter">
								<v-btn
									v-if="item.piezas > 1"
									text
									@click.prevent="sumaresta('resta', item, index)"
									><v-icon>mdi-chevron-left</v-icon></v-btn
								><span class="ma-2">{{ item.piezas }} </span
								><v-btn text @click.prevent="sumaresta('suma', item, index)"
									><v-icon>mdi-chevron-right</v-icon></v-btn
								>
							</v-card-actions>
						</v-card>
						<div class="pa-5">
							<b>Total:</b><span> ${{ totalPedido }}</span>
						</div>

						<v-btn
							dark
							block
							x-large
							color="success"
							@click.prevent="crearTicket()"
						>
							Procesar pedido
							<v-icon class="ml-2">mdi-check </v-icon>
						</v-btn>
					</v-stepper-content>
				</v-stepper-items>
			</v-stepper>
		</v-card>
	</v-dialog>
</template>

<script>
import { getArticulos } from "@/api/articulos.js";
import { getMesas } from "@/api/mesas.js";
import { postTickets } from "@/api/tickets.js";
import store from "@/store";
export default {
	props: {
		dialogorden: { default: false },
	} /*data de llegado de componente padre creacion*/,
	data() {
		return {
			swipeDirection: "None",
			selectmesa: "",
			itemsmesa: [],
			itemstip: [5, 10, 15, 20],
			selectip: 0,
			resp: null,
			dialog: false,
			pedidoArray: [],
			countproductos: 0,
			refresher: 0,
			titular: "",
			totalPedido: 0,
			e6: 1,
			steps: 2,
			itemsPerPageArray: [4, 8, 12],
			search: "",
			filter: {},
			sortDesc: false,
			page: 1,
			itemsPerPage: 15,
			sortBy: "name",
			keys: [
				"Name",
				"Calories",
				"Fat",
				"Carbs",
				"Protein",
				"Sodium",
				"Calcium",
				"Iron",
			],
			articulosArray: [],
			ticketParaenviar: [],
			items: [
				{
					name: "Frozen Yogurt",
					calories: 159,
					fat: 6.0,
					carbs: 24,
					protein: 4.0,
					sodium: 87,
					calcium: "14%",
					iron: "1%",
				},
				{
					name: "Ice cream sandwich",
					calories: 237,
					fat: 9.0,
					carbs: 37,
					protein: 4.3,
					sodium: 129,
					calcium: "8%",
					iron: "1%",
				},
				{
					name: "Eclair",
					calories: 262,
					fat: 16.0,
					carbs: 23,
					protein: 6.0,
					sodium: 337,
					calcium: "6%",
					iron: "7%",
				},
				{
					name: "Cupcake",
					calories: 305,
					fat: 3.7,
					carbs: 67,
					protein: 4.3,
					sodium: 413,
					calcium: "3%",
					iron: "8%",
				},
				{
					name: "Gingerbread",
					calories: 356,
					fat: 16.0,
					carbs: 49,
					protein: 3.9,
					sodium: 327,
					calcium: "7%",
					iron: "16%",
				},
				{
					name: "Jelly bean",
					calories: 375,
					fat: 0.0,
					carbs: 94,
					protein: 0.0,
					sodium: 50,
					calcium: "0%",
					iron: "0%",
				},
				{
					name: "Lollipop",
					calories: 392,
					fat: 0.2,
					carbs: 98,
					protein: 0,
					sodium: 38,
					calcium: "0%",
					iron: "2%",
				},
				{
					name: "Honeycomb",
					calories: 408,
					fat: 3.2,
					carbs: 87,
					protein: 6.5,
					sodium: 562,
					calcium: "0%",
					iron: "45%",
				},
				{
					name: "Donut",
					calories: 452,
					fat: 25.0,
					carbs: 51,
					protein: 4.9,
					sodium: 326,
					calcium: "2%",
					iron: "22%",
				},
				{
					name: "KitKat",
					calories: 518,
					fat: 26.0,
					carbs: 65,
					protein: 7,
					sodium: 54,
					calcium: "12%",
					iron: "6%",
				},
			],
		};
	},
	methods: {
		swipe(direction) {
			if (direction === "Down") {
				this.$emit("update:dialogorden", false);
			}
			this.swipeDirection = direction;
		},
		crearTicket() {
			var presend = {
				productos: this.pedidoArray,
				titular: (this.titular = this.titular.trim()),
				mesa_id: this.selectmesa,
				tip: this.selectip,
			};
			if (store.getters.hasrol == 5) {
				presend.mesa_id = 1;
			}
			postTickets(presend).then((response) => {
				if (response) {
					this.pedidoArray = [];
					this.countproductos = 0;
					this.totalPedido = 0;
					this.titular = "";
					this.e1 = 1;
					this.selectip = 0;
					this.cancelarPedido();
				} else {
					this.e6 = 1;
				}
			});
			// window.Echo.channel("tickets." + this.$store.getters.getUserId).listen(
			// 	"ticketCreated",
			// 	(e) => {
			// 		this.$store.commit("settickets", e.tickets);
			// 	}
			// );
		},
		sumaresta(operacion, producto, index) {
			if (operacion == "suma") {
				producto.piezas += 1;
				this.refresher += 1;
				this.pedidoArray[index] = producto;
				this.totalPedido += parseFloat(this.pedidoArray[index].precio_articulo);
				return this.pedidoArray;
			} else if (operacion == "resta") {
				producto.piezas -= 1;
				this.refresher -= 1;
				this.pedidoArray[index] = producto;
				this.totalPedido -= parseFloat(this.pedidoArray[index].precio_articulo);
				return this.pedidoArray;
			}
		},
		cancelarPedido() {
			(this.pedidoArray = []),
				(this.countproductos = 0),
				(this.totalPedido = 0),
				(this.titular = ""),
				(this.e1 = 1),
				(this.selectip = 0),
				store.commit("setstatcode", null);
		},
		deleteProduct(index) {
			this.countproductos -= 1;
			this.totalPedido -=
				parseFloat(this.pedidoArray[index].precio_articulo) *
				this.pedidoArray[index].piezas;
			this.pedidoArray.splice(index, 1);
		},
		close() {
			this.$emit("update:dialogorden", false);
		},
		cambio(producto) {
			if (this.pedidoArray.includes(producto) === true) {
				if (store.getters.hasdarkflag === true) {
					return "success";
				} else if (store.getters.hasdarkflag === false) {
					return "#272727";
				}
			} else if (this.pedidoArray.includes(producto) === false) {
				if (store.getters.hasdarkflag === true) {
					return "#272727";
				} else {
					return "#FFF";
				}
			}
		},
		cajaProductos(producto) {
			if (this.pedidoArray.includes(producto) === false) {
				producto.piezas = 1;
				producto.tax = 0;
				producto.descuento = 0;
				this.pedidoArray.push(producto);
				this.countproductos = this.pedidoArray.length;
				this.totalPedido += parseFloat(producto.precio_articulo);

				return this.pedidoArray, this.countproductos;
			} else if (this.pedidoArray.includes(producto) === true) {
				this.pedidoArray.forEach((element) => {
					if (element.id === producto.id) {
						this.pedidoArray.splice(this.pedidoArray.indexOf(element), 1);
					}
				});
				this.countproductos = this.pedidoArray.length;
				this.cambio(producto);
			}
		},
		nextStep(n) {
			if (n === this.steps) {
				this.e1 = 1;
			} else {
				this.e1 = n + 1;
			}
		},
		nextPage() {
			if (this.page + 1 <= this.numberOfPages) this.page += 1;
		},
		formerPage() {
			if (this.page - 1 >= 1) this.page -= 1;
		},
		updateItemsPerPage(number) {
			this.itemsPerPage = number;
		},
	},
	mounted() {
		window.Echo.channel("mesas").listen("mesaCreated", (e) => {
			this.itemsc = e.mesas;
		});
		window.Echo.channel("articulos").listen("articuloCreated", (e) => {
			this.articulosArray = e.articulos;
		});
		getMesas(this.itemsmesa)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargando = true;
			});
		getArticulos(this.articulosArray)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
					store.commit("setsuccess", null); //para resetear el valor de la notificion en una nueva entrada
					store.commit("setdanger", null);
				}
			})
			.catch((e) => {
				console.error(e);
				this.cargando = true;
			});
	},
	computed: {
		numberOfPages() {
			return Math.ceil(this.items.length / this.itemsPerPage);
		},
		filteredKeys() {
			return this.keys.filter((key) => key !== "Name");
		},
	},
	watch: {
		pedidoArray(val) {
			val;
			if (this.pedidoArray.length < 1) {
				this.e6 = 1;
			}
		},
		steps(val) {
			if (this.e1 > val) {
				this.e1 = val;
			}
		},
	},
};
</script>

<style scoped>
.boton {
	left: 10%;
}

.arrowscounter {
	display: flex;
	flex-direction: row;
	justify-content: flex-end;
}
.buttonsproced {
	display: flex;
	flex-direction: row;
}
.back {
	display: flex;
	flex-direction: row;
	justify-content: flex-start;
}
.card-p {
	min-width: 90%;
	max-width: 90%;
}
.card-prod {
	text-align: start;
}
.blt {
	color: #272727;
}
.wt {
	color: #fff;
}
</style>