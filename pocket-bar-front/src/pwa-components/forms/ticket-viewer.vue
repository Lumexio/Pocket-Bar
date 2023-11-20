<template>
	<v-dialog v-model="dialogticketviewer" fullscreen hide-overlay transition="dialog-bottom-transition">
		<v-card>
			<v-toolbar color="transparent" elevation="0" prominent v-touch="{
				down: () => swipe('Down'),
			}">
				<v-btn icon @click.prevent="close()" large>
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title>Ticket</v-toolbar-title>
			</v-toolbar>
			<v-row class="ma-4 pa-0 text-left  ">
				<div class="d-flex col">
					<span> Titular:{{ ticket.titular }}</span>
				</div>
				<div class="d-flex col">
					<span>{{ ticket.status }}</span>
				</div>
				<div class="d-flex col">
					<span> Mesa:{{ ticket.nombre_mesa }}</span>
				</div>
				<div class="d-flex col">
					<span>{{ ticket.fecha }}</span>
				</div>
			</v-row>

			<v-row class="ma-0 pa-0" v-show="statCheck(ticket.status)">
				<template>
					<v-col v-for="(item, index) in itemstip" :item="item" class="d-flex justify-start" :key="index">
						<v-btn dark color="primary" @click="toTip(item.value)" rounded>{{ item.name
						}}</v-btn>
					</v-col>
				</template>
			</v-row>
			<div class="d-flex justify-center align-start ">
				<v-text-field label="Monto	de propina" append-icon="mdi-currency-usd" append-outer-icon="mdi-cash"
					style="max-inline-size: 20rem" v-show="statCheck(ticket.status)" v-model="specifictip" class="ma-5"
					outlined></v-text-field>
			</div>

			<v-simple-table dense calculate-widths class="pa-0">
				<template v-slot:default>
					<thead>
						<tr>
							<th class="text-start ">Nombre</th>
							<th class="text-center pa-0">Cantidad</th>
							<th class="text-center pa-0">Precio</th>

							<th class="text-center pa-0">Total</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="producto in ticket.productos" :key="producto.id">
							<td class="text-start">{{ producto.nombre }}</td>
							<td class="text-center">{{ producto.cantidad }}</td>
							<td class="text-center">${{ producto.precio }}</td>

							<td class="text-center">${{ producto.total }}</td>
						</tr>
					</tbody>
				</template>
			</v-simple-table>
			<div class="d-flex justify-end">

				<v-col class="pa-3 ">
					<div v-if="selectip > 0" class="d-flex flex-row justify-space-between">
						<p>Propina:</p>
						<b>{{ selectip }}% ${{ calctip(ticket.total) }}</b>
					</div>
					<div v-if="specifictip > 0" class="d-flex flex-row justify-space-between">
						<p>Propina directa:</p>
						<b>${{ specifictip }}</b>
					</div>
					<div class="d-flex flex-row justify-space-between">
						<p> Subtotal:</p>
						<b>${{ ticket.total }}</b>
					</div>
					<div class="d-flex flex-row justify-space-between">
						<h4>
							Total neto:
						</h4>
						<h4>
							${{ calctotalneto(ticket.total) }}
						</h4>
					</div>
				</v-col>
			</div>
			<v-row v-show="statCheck(ticket.status)" class="justify-end pa-0 ma-5">
				<v-btn @click="resetTip">Limpiar</v-btn>
				<v-spacer></v-spacer>
				<v-btn color="success" @click="sendTip()">Guardar propina</v-btn></v-row>
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
		resetTip() {
			this.selectip = 0;
			this.specifictip = null;
		},
		sendTip() {
			console.log(Number(this.calctip(this.ticket.total)), Number(this.specifictip))
			putTipUpdate({
				id: this.ticket.id,
				tip: Number(this.calctip(this.ticket.total)) + Number(this.specifictip),
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

			return (Number(total) * (this.selectip / 100)).toFixed(2);
		},
		calctotalneto(total) {
			total = Number(total) + Number(this.calctip(total)) + Number(this.specifictip);
			total = parseFloat(total).toFixed(2);
			return total;
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
};
</script>


