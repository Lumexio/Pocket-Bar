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
			:ticketsArray="ticketsArray"
			@update:ticketsArray="getTickets()"
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
							<v-row>
								<v-col>
									<b>Nombre mesero:</b>
									<p>{{ editedItem.user_name }}</p>

									<b>Estatus de ticket:</b>
									<p>{{ editedItem.status }}</p>

									<b>Tootal a pagar:</b>
									<p>{{ editedItem.total }}</p>
								</v-col>
								<v-col>
									<v-subheader>Tipo de pago</v-subheader>
									<v-checkbox
										v-model="type_pay_cash"
										label="Efectivo"
										value="cash"
									></v-checkbox>
									<v-checkbox
										v-model="type_pay_card"
										label="Tarjeta"
										value="card"
									></v-checkbox>
								</v-col>
							</v-row>
						</v-card-text>
						<v-row class="ml-3 mr-3">
							<v-col>
								<v-subheader v-show="type_pay_cash == 'cash'"
									>Pago en efectivo</v-subheader
								>
								<v-text-field
									v-show="type_pay_cash == 'cash'"
									label="Dinero en efectivo"
									:dark="$store.getters.hasdarkflag"
									outlined
									v-model="amount_cash"
									prefix="$"
								></v-text-field>
								<v-text-field
									v-show="type_pay_card == 'card' || type_pay_cash == 'cash'"
									label="Propina efectivo"
									:dark="$store.getters.hasdarkflag"
									outlined
									v-model="tip_cash"
									prefix="$"
								></v-text-field>
							</v-col>
							<v-col>
								<v-subheader v-show="type_pay_card == 'card'"
									>Pago con tarjeta</v-subheader
								>
								<v-text-field
									v-show="type_pay_card == 'card'"
									label="Pago con tarjeta"
									:dark="$store.getters.hasdarkflag"
									outlined
									v-model="amount_card"
									prefix="$"
								></v-text-field>
								<v-text-field
									v-show="type_pay_card == 'card'"
									label="Voucher"
									v-model="voucher"
									:dark="$store.getters.hasdarkflag"
									outlined
									prefix="$"
								></v-text-field>
								<v-text-field
									v-show="type_pay_card == 'card'"
									label="Propina tarjeta"
									:dark="$store.getters.hasdarkflag"
									outlined
									v-model="tip_card"
									prefix="$"
								></v-text-field>
							</v-col>
						</v-row>
						<v-card-actions>
							<v-btn color="blue darken-1" outlined @click="close">
								Cancelar
							</v-btn>
							<v-spacer></v-spacer>
							<v-btn
								color="success"
								v-on:keyup.enter="save"
								outlined
								:ticketsArray="ticketsArray"
								@update:ticketsArray="getTickets()"
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
					max-width="550px"
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
				<v-dialog
					:dark="$store.getters.hasdarkflag"
					v-model="dialogCancel"
					max-width="550px"
				>
					<v-card>
						<v-card-title class="headline"
							>¿Estas seguro de querer cancelar este ticket?</v-card-title
						>
						<v-card-actions>
							<v-btn color="danger" outlined @click="closeCancelTicket"
								>Cancelar</v-btn
							>
							<v-spacer></v-spacer>
							<v-btn color="success" outlined @click="cancelConfirm"
								>Aceptar</v-btn
							>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-icon
					v-show="(item.status == 'Entregado'&& $store.getters.hasrol===3)"
					small
					class="mr-2"
					@click="editItem(item)"
				>
					mdi-cash-100
				</v-icon>
				<v-icon
					v-show="(item.cancel_confirm == null||item.cancel_confirm==false)&&item.status != 'Cerrado'"
					small
					class="mr-2"
					@click="editItemCancel(item)"
				>
					mdi-close
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
				<template v-slot:[`item.status`]="{ item }">
			<v-chip :color="getColor(item.status)" dark>
				{{ item.status }}
			</v-chip>
		</template>
		</v-data-table>
	</v-card>
</template>

<script>
import { getTickets } from "@/api/tickets.js";
import { postCerrarticket, postCancelticket } from "@/api/cortes.js";
export default {
	name: "tabla-ordenes",
	data: () => ({
		type_pay_cash: "cash",
		type_pay_card: "",
		amount_card: null,
		amount_cash: null,
		voucher: null,
		tip_cash: null,
		tip_card: null,
		payments: [],
		obj_card: {},
		obj_cash: {},
		packClose: {},
		editedIndex: -1,
		editedItem: {
			id: "",
			user_name: "",
			status: "",
			total: "",
		},
		editedItemCancel: {
			id: "",
			user_name: "",
			status: "",
			total: "",
		},
		defaultItem: {
			id: "",
			user_name: "",
			status: "",
			total: "",
		},
		dialog: false,
		dialogCancel: false,
		dialogCierreConfirm: false,
		
		search: "",
		cargando: true,
		expanded: [],
		headers: [
			{
				text: "Mesero",
				align: "start",
				sortable: false,
				value: "user_name",
			},
						{
				text: "Cliente",
				align: "start",
				sortable: false,
				value: "client_name",
			},
			{
				text: "Total de compra",
				align: "center",
				sortable: false,
				value: "total",
			},
			{
				text: "Estado",
				align: "center",
				sortable: false,
				value: "status",
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
			{ text: "Acciones",align: "center", value: "actions" },
			{ text: "Detalles",align: "center",  value: "data-table-expand" },
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
		dialogCancel(val) {
			val || this.closeCancelTicket();
		},
	},
	mounted() {
		window.Echo.channel("tickets.").listen("ticketCreated", (e) => {
			this.$store.commit("settickets", e.tickets);
			this.ticketsArray = e.tickets;
		});
		this.onFocus();
		// window.Echo.channel("activitylog").listen("activitylogCreated", (e) => {
		//   this.ticketsArray = e.activitylog;
		// });
		this.getTickets();
	},

	methods: {
		getColor(status) {
			if (status === "Por entregar") return "orange lighten-2";
			else if (status === "Entregado") return "blue darken-1";
			else if (status === "Cerrado") return "red lighten-1";
			else if (status === "Cancelado") return "grey darken-3";
			else return "deep-orange darken-4";
		},
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
			// 	this.editedItemCancel = Object.assign({}, this.defaultItem);
			// 	this.editedIndex = -1;
			// });
		},
		closeCancelTicket() {
			this.dialogCancel = false;
			this.$nextTick(() => {
				this.editedItemCancel = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
			cierreItemConfirm() {
			// aqui armo el pago final
			postCerrarticket(this.packClose)
				.then((response) => {
					
					window.Echo.channel("tickets.").listen("ticketCreated", (e) => {
						this.$store.commit("settickets", e.tickets);
						this.ticketsArray = e.tickets;
					});
					if (response.response.status == 200) {
						this.dialogCierreConfirm = false;
						this.dialog = false;
						this.payments = [];
						this.obj_cash = {};
						this.obj_card = {};
						this.amount_card = null;
						this.amount_cash = null;
						this.tip_cash = null;
						this.tip_card = null;
						this.voucher = null;
						this.refresh = 0;
					}
				})
				.catch((e) => {
					console.log(e);
					this.cargando = true;
				});
		},
		cancelConfirm() {
			// aqui armo la cancelacion
			postCancelticket({ id: this.editedItemCancel.id,confirm_ticket:false })
				.then((response) => {
					console.log("response cancel comp:",response);
					window.Echo.channel("tickets.").listen("ticketCreated", (e) => {
						this.$store.commit("settickets", e.tickets);
						this.ticketsArray = e.tickets;
					});
					if (response.response.data.status == 200) {
		
						this.dialogCancel = false;
						this.cargando = false;
					}
				})
				.catch((e) => {
					console.log(e);
					this.cargando = true;
				});
		},
		RESET_CLOSE_DATA() {
			return (
				(this.payments = []),
				(this.obj_cash = {}),
				(this.obj_card = {}),
				(this.amount_card = null),
				(this.amount_cash = null),
				(this.tip_cash = null),
				(this.tip_card = null),
				(this.voucher = null),
				(this.refresh = 0)
			);
		},
		getTickets() {
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
		editItem(item) {
			this.editedIndex = this.ticketsArray.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialog = true;
		},
		editItemCancel(item) {
			this.editedIndex = this.ticketsArray.indexOf(item);
			this.editedItemCancel = Object.assign({}, item);
			this.dialogCancel = true;
		},
		save() {
			if (this.amount_card != null) {
				this.obj_card = {
					payment_type: this.type_pay_card,
					amount: Number(this.amount_card),
					voucher: this.voucher,
					tip: Number(this.tip_card),
				};
				this.payments.push(this.obj_card);
			}
			if (this.amount_cash != null) {
				this.obj_cash = {
					payment_type: this.type_pay_cash,
					amount: Number(this.amount_cash),
					tip: Number(this.tip_cash),
				};
				this.payments.push(this.obj_cash);
			}
			this.packClose = {
				ticket_id: this.editedItem.id,
				payments: this.payments,
			};

			this.dialogCierreConfirm = true;
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