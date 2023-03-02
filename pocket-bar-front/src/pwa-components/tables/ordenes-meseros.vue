<template >
	<v-expansion-panels :dark="darkonchange" popout>
		<v-expansion-panel
			class="sizes"
			v-for="item in ticketsPWAArray"
			:key="item.titular"
			v-show="item.status == hasstatus"
		>
			<v-expansion-panel-header>
				<span>
					<span style="font-size: 18px">
						<b>Titular: </b>{{ item.titular }}</span
					>
					<br />
					<span style="font-size: 18px"
						><b>Mesa: </b>{{ item.nombre_mesa }}</span
					>
					<br />
					<span style="font-size: 18px"> <b>Fecha: </b>{{ item.fecha }}</span>
				</span>
				<v-btn
					class="mr-1"
					max-width="25px"
					large
					:dark="darkonchange"
					color="cyan darken-3"
					@click.prevent="dialogticketviewer = true"
					><v-icon>mdi-eye-circle</v-icon>
				</v-btn>
				<v-btn
					class="mr-2"
					max-width="25px"
					large
					:dark="darkonchange"
					color="#1E88E5"
					v-if="item.status != 'Cerrado'"
					@click.prevent="(dialogaddproduct = true), (ticket_id = item.id)"
					><v-icon>mdi-plus</v-icon>
				</v-btn>
			</v-expansion-panel-header>
			<v-expansion-panel-content class="expansion-panel">
				<v-simple-table dense calculate-widths>
					<template v-slot:default>
						<thead>
							<tr>
								<th class="text-left">Nombre</th>
								<th class="text-left">Cantidad</th>
								<th class="text-left">Precio</th>
								<th class="text-left">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="producto in item.productos" :key="producto.id">
								<td class="text-left">{{ producto.nombre }}</td>
								<td class="text-left">{{ producto.cantidad }}</td>
								<td class="text-left">${{ producto.precio }}</td>
								<td class="text-left">${{ producto.total }}</td>
							</tr>
						</tbody>
					</template>
				</v-simple-table>
				
				<p style="text-align: end" class="mr-6 font-weight-bold">
					<span class="mr-2"> Propina:</span> {{ item.tip }}% 
					<span class="mr-2"> Subtotal:</span> ${{ item.total }}
				</p>
				
			</v-expansion-panel-content>
			<ticketViewer
				:dialogticketviewer.sync="dialogticketviewer"
				:ticket="item"
			/>
		</v-expansion-panel>
		<addProducts
			:dialogaddproduct.sync="dialogaddproduct"
			:ticket_id="ticket_id"
		/>
	</v-expansion-panels>
</template>
<script>
import addProducts from "@/pwa-components/forms/add-product.vue";
import ticketViewer from "@/pwa-components/forms/ticket-viewer.vue";
import store from "@/store";
import { getTicketsPWA } from "@/api/tickets.js";
export default {
	name: "ordenesMesero",
	components: {
		addProducts,
		ticketViewer,
	},
	data: () => ({
		ticket_id: null,
		dialogaddproduct: false,
		dialogticketviewer: false,
		ticketsPWAArray: [],
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
		window.Echo.channel("ticketCreatedMesero.").listen(
			"ticketCreatedMesero",
			(e) => {
				this.ticketsPWAArray = e.tickets;
			}
		);
		getTicketsPWA(this.ticketsPWAArray, store.getters.hasstatus);
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