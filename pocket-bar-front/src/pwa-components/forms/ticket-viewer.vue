<template>
	<v-dialog
		v-model="dialogticketviewer"
		fullscreen
		hide-overlay
		transition="dialog-bottom-transition"
	>
		<!-- Comment cotorro -->
		<v-card  >
			<v-toolbar
				color="transparent"
				elevation="0"
				prominent
				v-touch="{
					down: () => swipe('Down'),
				}"
			>
				<v-btn
					icon
					@click.prevent="close()"
					large
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>

				<v-toolbar-title>Ticket</v-toolbar-title>
			</v-toolbar>
			
				<v-row class="ma-4 pa-0 text-left ">
					<v-col>
						Titular
						<h1>{{ ticket.titular }}</h1>
					</v-col>

					<v-col>
						Estatus
						<h3>{{ ticket.status }}</h3>
					</v-col>
					<v-col>
						Mesa
						<h3>{{ ticket.nombre_mesa }}</h3>
					</v-col>
					<v-col>
						Fecha
						<h3>{{ ticket.fecha }}</h3>
					</v-col>
				</v-row>
			
			<v-row class="ma-0 pa-0" v-show="statCheck(ticket.status)">
					<template>
						<v-col
							v-for="(item, index) in itemstip"
							:item="item"
							class="d-flex justify-center"
							:key="index"
						>
							<v-btn
								dark
								color="primary"
								@click="toTip(item.value)"
								large
								rounded
								depressed
								>{{ item.name }}</v-btn
							>
						</v-col>
					</template>
				</v-row>
				<div class="d-flex justify-center align-center">
					<v-text-field
						label="Monto	de propina"
						append-icon="mdi-currency-usd"
						append-outer-icon="mdi-cash"
						style="max-width: 20rem"
						v-show="statCheck(ticket.status)"
						v-model="specifictip"
						class="ma-5"
						outlined
					></v-text-field>
				</div>
				<v-simple-table dense calculate-widths class="pa-0">
					<template v-slot:default>
						<thead>
							<tr>
								<th class="text-center pa-0">Nombre</th>
								<th class="text-center pa-0">Cantidad</th>
								<th class="text-center pa-0">Precio</th>
								<th class="text-center pa-0">Descuento</th>
								<th class="text-center pa-0">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="producto in ticket.productos" :key="producto.id">
								<td class="text-center">{{ producto.nombre }}</td>
								<td class="text-center">{{ producto.cantidad }}</td>
								<td class="text-center">${{ producto.precio }}</td>
								<td class="text-center">${{ producto.descuento }}</td>
								<td class="text-center">${{ producto.total }}</td>
							</tr>
						</tbody>
					</template>
				</v-simple-table>
				<div class="d-flex justify-end">
					
					<v-col style="max-width: 9rem;" class="pa-0 text-left">
						Propina:
						<b>{{ selectip }}% ${{ calctip(ticket.total) }}</b>
						Subtotal:
						<b>${{ ticket.total }}</b>
						<h3>
							Total neto:
							<b class="text--primary">${{ calctotalneto(ticket.total) }}</b>
						</h3></v-col
					>
					</div>
					<v-row class="justify-end pa-0 ma-5">
				<v-btn color="success"    x-large   depressed @click="sendTip()"
							>Guardar propina</v-btn
						></v-row>
		</v-card>
	</v-dialog>
</template>

<script>
import { putTipUpdate } from "@/api/tickets.js";
export default {
	props: {
		dialogticketviewer: { default: false },
		ticket: { default: null },
		status: { default: null },
	} /*data de llegado de componente padre creacion*/,
	data() {
		return {
			swipeDirection: "None",
			itemstip: [
				{ name: "5%", value: 5 },
				{ name: "10%", value: 10 },
				{ name: "15%", value: 15 },
				{ name: "20%", value: 20 },
			],
			selectip: 0,
			specifictip: null,
		};
	},

	methods: {
		sendTip() {
			putTipUpdate({
				id: this.ticket.id,
				tip: this.selectip,
				specifictip: this.specifictip,
			}).then((res) => {
				if (res) {
					this.$emit("update:dialogticketviewer", false);
				}
			});
		},
		statCheck(status) {
			if (status != "Por entregar") {
				return true;
			} else {
				return false;
			}
		},
		calctip(total) {
			this.selectip = Number(this.selectip);

			return Math.round(Number(total) * (this.selectip / 100));
		},
		calctotalneto(total) {
			return Number(total) + this.calctip(total);
		},
		toTip(tip) {
			this.selectip = tip;
		},
		swipe(direction) {
			if (direction === "Down") {
				this.$emit("update:dialogticketviewer", false);
			}
			this.swipeDirection = direction;
		},

		close() {
			this.$emit("update:dialogticketviewer", false);
		},
	},
	mounted() {},
	watch: {},
};
</script>

