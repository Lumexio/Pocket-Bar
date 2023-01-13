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
				<v-btn icon @click.prevent="close()" x-large>
					<v-icon>mdi-close</v-icon>
				</v-btn>

				<v-toolbar-title>Ticket</v-toolbar-title>
			</v-toolbar>
			<!-- Importante revisar y corregir para el envio del request -->
			<v-card-text class="text-left pl-0 pr-0">
				<v-row class="ma-4">
					<v-col>
						Titular
						<h1>{{ ticket.titular }}</h1>
					</v-col>
					<v-spacer></v-spacer>
					<v-col cols="2">
						Estatus
						<h3>{{ ticket.status }}</h3>
					</v-col>
					<v-col cols="1">
						Mesa
						<h3>{{ ticket.nombre_mesa }}</h3>
					</v-col>
					<v-col cols="2">
						Fecha
						<h3>{{ ticket.fecha }}</h3>
					</v-col>
					<v-col cols="3"
						><v-select
							v-model="selectip"
							:items="itemstip"
							append-icon="mdi-percent-circle-outline"
							label="Propina %"
							outlined
							class="ma-2"
						></v-select
					></v-col>
				</v-row>
				<v-simple-table dense calculate-widths>
					<template v-slot:default>
						<thead>
							<tr>
								<th class="text-left">Nombre</th>
								<th class="text-left">Cantidad</th>
								<th class="text-left">Precio</th>

								<th class="text-left">Descuento</th>
								<th class="text-left">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="producto in ticket.productos" :key="producto.id">
								<td class="text-left">{{ producto.nombre }}</td>
								<td class="text-left">{{ producto.cantidad }}</td>
								<td class="text-left">{{ producto.precio }}</td>
								<td class="text-left">${{ producto.descuento }}</td>
								<td class="text-left">${{ producto.total }}</td>
							</tr>
						</tbody>
					</template>
				</v-simple-table>
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
		return { itemstip: [5, 10, 15, 20], selectip: 0 };
	},
	methods: {
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
	computed: {},
	watch: {},
};
</script>

<style scoped>
</style>