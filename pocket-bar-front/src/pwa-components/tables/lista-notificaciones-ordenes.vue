<template>
	<v-dialog
		v-model="dialoglistorden"
		fullscreen
		hide-overlay
		transition="dialog-bottom-transition"
		><v-card :dark="this.$store.getters.hasdarkflag">
			<v-toolbar
				color="transparent"
				flat
				prominent
				v-touch="{
					left: () => swipe('Left'),
					right: () => swipe('Right'),
					up: () => swipe('Up'),
					down: () => swipe('Down'),
				}"
			>
				<v-btn large icon @click.prevent="close()">
					<v-icon>mdi-close</v-icon>
				</v-btn>

				<v-toolbar-title>Pedidos de {{ hasrol }} </v-toolbar-title>
			</v-toolbar>
			<v-card
				v-show="ticketsPWANotiArrayBarra"
				class="ml-1 mr-1 mt-4 mb-4 pa-1"
				style="min-width: 96%; max-width: 97%; text-align: start"
				v-for="(item, index) in ticketsPWANotiArrayBarra"
				:key="index"
			>
				<v-row
					><v-col cols="1"
						><span>{{ item.units }}</span></v-col
					><v-col cols="5"
						><span>{{ item.nombre_articulo }}</span>
					</v-col>
					<v-col cols="2"><span>para</span></v-col>
					<v-col cols="4"
						><span> {{ item.nombre_mesero }}</span>
					</v-col>
				</v-row>
				<v-card-actions class="statusbuton">
					<v-chip dark :color="colorchange(item.status)">{{
						item.status
					}}</v-chip>

					<v-btn
						large
						color="primary"
						dark
						v-if="item.status === 'En espera' && hasrol === 'bartender'"
						@click.prevent="sendStatusPrep(item.id, 'En preparacion')"
						>Preparar</v-btn
					>
					<v-btn
						large
						color="success"
						dark
						v-if="item.status === 'En preparacion' && hasrol === 'bartender'"
						@click.prevent="sendStatusPrep(item.id, 'Preparado')"
						>Terminar</v-btn
					>
					<v-btn
						dark
						v-if="item.status === 'Preparado' && hasrol === 'mesero'"
						@click.prevent="sendStatusRecived(item.id, item.status)"
						>Recibir</v-btn
					>
				</v-card-actions>
			</v-card>
			<v-card
				v-show="ticketsPWANotiArrayMesero"
				class="ml-1 mr-1 mt-4 mb-4 pa-1"
				style="min-width: 96%; max-width: 97%; text-align: start"
				v-for="item in ticketsPWANotiArrayMesero"
				:key="item.id"
			>
				<v-row
					><v-col cols="1"
						><span>{{ item.units }}</span></v-col
					><v-col cols="5"
						><span>{{ item.nombre_articulo }}</span>
					</v-col>
					<v-col cols="2"><span>para</span></v-col>
					<v-col cols="4"
						><span> {{ item.nombre_mesero }}</span>
					</v-col>
				</v-row>
				<v-card-actions class="statusbuton">
					<v-chip dark :color="colorchange(item.status)">{{
						item.status
					}}</v-chip>

					<v-btn
						large
						dark
						color="grey darken-1"
						v-if="item.status === 'En espera' && hasrol === 'bartender'"
						@click.prevent="sendStatusPrep(item.id, 'En preparacion')"
						>Preparar</v-btn
					>
					<v-btn
						large
						dark
						v-if="item.status === 'En preparacion' && hasrol === 'bartender'"
						@click.prevent="sendStatusPrep(item.id, 'Preparado')"
						>Terminar</v-btn
					>
					<v-btn
						dark
						v-if="item.status === 'Preparado' && hasrol === 'mesero'"
						@click.prevent="sendStatusRecived(item.id, item.status)"
						>Recibir</v-btn
					>
				</v-card-actions>
			</v-card>
		</v-card>
	</v-dialog>
</template>

<script lang="js">
import { getTicketsNotiPWA } from "@/api/tickets.js";
import { postTicketsNotiPWA } from "@/api/tickets.js";
import store from "@/store";
export default {
	name: "listaNotiOrdenes",
	props: {
		dialoglistorden: { default: false },
	} /*data de llegado de componente padre creacion*/,
	data: () => ({
		swipeDirection: "None",
		ticketsPWANotiArrayMesero: [],
		ticketsPWANotiArrayBarra: [],
		tempTicketsArray: [],
		sendStatusPrepBox: { id: null, status: "Preparado" },
		sendStatusRecivedBox: { id: null, status: "Recibido" },
	}),

	methods: {
		swipe(direction) {
			if (direction === "Down") {
				this.$emit("update:dialoglistorden", false);
			}
			this.swipeDirection = direction;
		},
		colorchange(status) {
			var st = null;
			switch (status) {
				case "Preparado":
					st = "green";
					break;
				case "En preparacion":
					st = "blue";
					break;
				case "En espera":
					st = "grey";
					break;

				default:
					st = "grey";
					break;
			}
			return st;
		},

		sendStatusPrep(id, status) {
			this.sendStatusPrepBox.id = id;
			this.sendStatusPrepBox.status = status;
			postTicketsNotiPWA(this.sendStatusPrepBox);
		},
		sendStatusRecived(id) {
			this.sendStatusRecivedBox.id = id;
			this.sendStatusRecivedBox.status = "Recibido";

			postTicketsNotiPWA(this.sendStatusRecivedBox);
		},
		close() {
			this.$emit("update:dialoglistorden", false);
		},
		parseNotifications(tickets) {
			let ticketsPWANotiArray = [];
			tickets.forEach((element) => {
				let datos = {
					nombre_articulo: element.articulo.nombre_articulo,
					id: element.id,
					units: element.units,
					status: element.status,
				};
				if (element.mesero) {
					datos.nombre_mesero = element.mesero.name;
				}

				ticketsPWANotiArray.push(datos);
			});
			return ticketsPWANotiArray;
		},
		connectToSocket(channel, event, variableName, callbackVariableName) {
			window.Echo.channel(channel + this.$store.getters.getUserId).listen(
				event,
				(e) => {
					this[variableName] = this.parseNotifications(e[callbackVariableName]);
					store.commit("setorder", this[variableName].length);
				}
			);
		},
		getNotificationsFromAPI(variableName) {
			getTicketsNotiPWA(this[variableName])
				.then((response) => {
					this[variableName] = response.ticketsPWANotiArray;
					this.tempTicketsArray = response.ticketsPWANotiArray;
					store.commit("setorder", this.tempTicketsArray.length);
				})
				.catch((e) => {
					console.log(e);
				});
		},
	},
	whatch: {},
	computed: {
		hasrol() {
			var rol = null;
			/*para obtener el rol al que pertenece el usuario y dependiendo de eso podra observar el componente */
			if (this.$store.getters.hasrol === 4) {
				rol = "mesero";
				this.connectToSocket(
					"mesero.",
					"MeseroEvents",
					"ticketsPWANotiArrayMesero",
					"TicketsARecibir"
				);
			} else if (this.$store.getters.hasrol === 5) {
				rol = "bartender";
				this.connectToSocket(
					"barra.",
					"BarraEvents",
					"ticketsPWANotiArrayBarra",
					"notificacionesBarra"
				);
			}

			return rol;
		},
	},
	mounted() {
		if (this.$store.getters.hasrol === 4) {
			this.getNotificationsFromAPI("ticketsPWANotiArrayMesero");
			this.connectToSocket(
				"mesero.",
				"MeseroEvents",
				"ticketsPWANotiArrayMesero",
				"TicketsARecibir"
			);
		} else if (this.$store.getters.hasrol === 5) {
			this.getNotificationsFromAPI("ticketsPWANotiArrayBarra");
			this.connectToSocket(
				"barra.",
				"BarraEvents",
				"ticketsPWANotiArrayBarra",
				"notificacionesBarra"
			);
		}
	},
};
</script>

<style scoped>
.statusbuton {
	display: flex;
	flex-direction: row;

	justify-content: space-between;
}
</style>