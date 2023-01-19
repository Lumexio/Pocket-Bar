<template>
	<v-dialog
		v-model="dialogticketviewer"
		fullscreen
		hide-overlay
		transition="dialog-bottom-transition"
	>
		<v-card>
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
				<v-btn icon @click.prevent="close()" large>
					<v-icon>mdi-close</v-icon>
				</v-btn>

				<v-toolbar-title>Ticket</v-toolbar-title>
			</v-toolbar>

			<v-card-text class="text-left pl-0 pr-0">
				<v-row class="ma-4">
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
				<v-row class="mb-3">
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
				<v-simple-table dense calculate-widths>
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
				<v-row class="ma-4">
					Propina: {{ selectip }}% [${{ calctip(ticket.total) }}] Total neto:
					${{ calctotalneto(ticket.total) }}
				</v-row>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	props: {
		dialogticketviewer: { default: false },
		ticket: { default: null },
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
		};
	},
	computed: {},
	methods: {
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

<style scoped>
</style>