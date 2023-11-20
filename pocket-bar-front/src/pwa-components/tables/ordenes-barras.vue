<template >
	<v-expansion-panels :dark="darkonchange" popout>
		<v-progress-linear color="deep-purple accent-4" indeterminate rounded height="6"
			:active="carga"></v-progress-linear>
		<v-card style="inline-size:100%;" v-for="item in ticketsPWAArray" :key="item.id" v-show="item.status == hasstatus">
			<v-card-title class="text-start">
				<span>
					<b>Titular: </b>{{ item.titular }}
					<br />
					<b>Mesa: </b>{{ item.nombre_mesa }}
					<br />
					{{ item.fecha }}
				</span>
			</v-card-title>
			<v-card-text class="text-start">
				<div v-if="item.tip > 0" class="d-flex flex-row justify-space-between align-center">
					<span> Propina:</span>
					<v-divider></v-divider>
					<b>${{ item.tip }}</b>
				</div>
				<br />
				<div class="d-flex flex-row justify-space-between align-center">
					<span> Subtotal:</span><v-divider></v-divider> <b> ${{ item.total }}</b>
				</div>
			</v-card-text>
			<v-card-actions>

				<v-btn :dark="darkonchange" color="cyan darken-3"
					@click.prevent="(dialogticketviewer = true), (onticket = item)"><v-icon>mdi-eye-circle</v-icon>
				</v-btn>
				<v-btn :dark="darkonchange" color="#1E88E5" v-if="item.status != 'Cerrado'"
					@click.prevent="(dialogaddproduct = true), (ticket_id = item.id)"><v-icon>mdi-plus</v-icon>
				</v-btn>
			</v-card-actions>
		</v-card>
		<ticketViewer :dialogticketviewer.sync="dialogticketviewer" :ticket="onticket" />
		<addProducts :dialogaddproduct.sync="dialogaddproduct" :ticket_id="ticket_id" />
	</v-expansion-panels>
</template>
<script>
import addProducts from "@/pwa-components/forms/add-product.vue";
import ticketViewer from "@/pwa-components/forms/ticket-viewer.vue";
import store from "@/store";
import { getTicketsPWA } from "@/api/tickets.js";
export default {
	name: "ordenesBarra",
	components: {
		addProducts,
		ticketViewer,
	},
	data: () => ({
		ticket_id: null,
		onticket: {},
		dialogaddproduct: false,
		dialogticketviewer: false,
		ticketsPWAArray: [],
		carga: false,
	}),
	whatch: {
		ticketsPWAArray(val) {
			val;
			if (store.getters.hastickets) {
				this.ticketsPWAArray = store.getters.hastickets;
			}
		},
	},
	mounted() {
		window.Echo.channel("ticketCreatedBarra.").listen(
			"ticketCreatedBarra",
			(e) => {
				this.ticketsPWAArray = e.tickets;
			}
		);
		getTicketsPWA(this.ticketsPWAArray, store.getters.hasstatus).then(
			(response) => {
				response.stats === 200 ? this.carga = false : this.carga = true;
			}
		).catch((error) => {
			console.error(error);
		});
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
.v-expansion-panels {
	gap: 10px;

	max-height: 10% !important;
}

.row {
	display: flex;
	flex-direction: row;
}

.sizes {
	max-width: calc(100% - 0px) !important;
}
</style>