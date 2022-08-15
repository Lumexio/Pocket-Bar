<template>
	<v-card :dark="this.$store.getters.hasdarkflag">
		<v-toolbar flat color="transparent">
			<v-toolbar-title>Tabla de ordenes</v-toolbar-title>
			<v-divider inset vertical class="ml-4"></v-divider>
			<v-spacer></v-spacer>
			<v-text-field
				:dark="this.$store.getters.hasdarkflag"
				v-model="search"
				label="Buscar orden"
				class="mt-6"
				id="onsearch"
			></v-text-field>
		</v-toolbar>

		<v-data-table
			:dark="this.$store.getters.hasdarkflag"
			id="tabla"
			:headers="headers"
			show-expand
			:expanded.sync="expanded"
			:items="ticketsArray"
			sort-by="cantidad_articulo"
			:search="search"
			:custom-filter="filterOnlyCapsText.toUpperCase"
		>
			<template v-slot:top>
				<v-progress-linear
					height="6"
					indeterminate
					color="cyan"
					:active="cargando"
				></v-progress-linear>
				<v-dialog
					:dark="$store.getters.hasdarkflag"
					v-model="dialog"
					max-width="500px"
				>
					<v-card>
						<v-card-title>
							<h1 class="headline">{{ formTitle }}</h1>
						</v-card-title>

						<v-card-text style="text-align: start">
							<b>Nombre mesero:</b>
							<p>{{ editedItem.nombre_mesero }}</p>

							<b>Estatus de ticket:</b>
							<p>{{ editedItem.status_ticket }}</p>

							<b>Tootal a pagar:</b>
							<p>{{ editedItem.monto_total }}</p>
						</v-card-text>

						<v-card-actions>
							<v-btn color="blue darken-1" outlined @click="close">
								Cancelar
							</v-btn>
							<v-spacer></v-spacer>
							<v-btn
								color="success"
								v-on:keyup.enter="save"
								outlined
								@click="save"
							>
								Efectuar pago caja
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
				<v-dialog
					:dark="$store.getters.hasdarkflag"
					v-model="dialogCierreConfirm"
					max-width="500px"
				>
					<v-card>
						<v-card-title class="headline"
							>¿Estas seguro de querer cerrar este ticket?</v-card-title
						>
						<v-card-actions>
							<v-btn color="danger" outlined @click="closeCierreTicket"
								>Cancelar</v-btn
							>
							<v-spacer></v-spacer>
							<v-btn color="success" outlined @click="cierreItemConfirm"
								>Aceptar</v-btn
							>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-icon small class="mr-2" @click="editItem(item)">
					mdi-cash-100
				</v-icon>
			</template>
			<template v-slot:no-data>
				<span>Datos no disponibles.</span>
			</template>
			<template v-slot:expanded-item="{ headers, item }">
				<td :colspan="headers.length">
					{{ item.properties }}
				</td>
			</template>
		</v-data-table>
	</v-card>
</template>

<script>
import { getTickets } from "@/api/tickets.js";
import { postCerrarticket } from "@/api/cortes.js";
export default {
	name: "tabla-activitylog",
	data: () => ({
		editedIndex: -1,
		editedItem: {
			id: "",
			nombre_mesero: "",
			status_ticket: "",
			monto_total: "",
		},
		defaultItem: {
			id: "",
			nombre_mesero: "",
			status_ticket: "",
			monto_total: "",
		},
		dialog: false,
		dialogCierreConfirm: false,
		search: "",
		cargando: true,
		expanded: [],
		headers: [
			{
				text: "Mesero",
				align: "start",
				sortable: false,
				value: "nombre_mesero",
			},
			{
				text: "Total de compra",
				align: "start",
				sortable: false,
				value: "monto_total",
			},
			{
				text: "Estado",
				align: "start",
				sortable: false,
				value: "status_ticket",
			},
			{
				text: "Creado en",
				align: "start",
				sortable: false,
				value: "ticket_date",
			},
			{
				text: "Cerrado en",
				align: "start",
				sortable: false,
				value: "ticket_date",
			},
			{ text: "Descripción", align: "start", value: "data-table-expand" },
			{ text: "Cortes", value: "actions" },
		],

		ticketsArray: [],
	}),
	computed: {
		formTitle() {
			return this.editedIndex === -1 ? "New Item" : "Pagar ticket";
		},
	},
	watch: {
		dialog(val) {
			val || this.close();
		},
		dialogCierreConfirm(val) {
			val || this.closeCierreTicket();
		},
	},
	mounted() {
		this.onFocus();
		// window.Echo.channel("activitylog").listen("activitylogCreated", (e) => {
		//   this.ticketsArray = e.activitylog;
		// });
		getTickets(this.ticketsArray)
			.then((response) => {
				if (response.stats === 200) {
					this.cargando = false;
				}
			})
			.catch((e) => {
				console.log(e);
				this.cargando = true;
			});
	},
	methods: {
		close() {
			this.dialog = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		closeCierreTicket() {
			this.dialogCierreConfirm = false;
			// this.$nextTick(() => {
			// 	this.editedItem = Object.assign({}, this.defaultItem);
			// 	this.editedIndex = -1;
			// });
		},
		cierreItemConfirm() {
			this.ticketsArray.splice(this.editedIndex, 1);
			postCerrarticket(this.editedItem);
		},
		editItem(item) {
			console.log("Datos ticket:", item);
			this.editedIndex = this.ticketsArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialog = true;
		},
		save() {
			this.dialogCierreConfirm = true;
			console.log("Guardando....");
			// if (this.editedIndex > -1) {
			// 	Object.assign(this.ticketsArray[this.editedIndex], this.editedItem);
			// } else {
			// 	this.desserts.push(this.editedItem);
			// }
			// this.close();
		},
		onFocus() {
			let stext = document.getElementById("onsearch");
			stext;
			stext = addEventListener("keydown", (e) => {
				if (e.altKey) {
					document.getElementById("onsearch").focus();
				}
			});
		},
		filterOnlyCapsText(value, search) {
			return (
				value != null &&
				search != null &&
				typeof value === "string" &&
				value.toString().toLocaleUpperCase().indexOf(search) !== -1
			);
		},
	},
};
</script>

<style scoped>
#tabla {
	width: 100%;
}
.tabla {
	width: 100%;
}
</style>