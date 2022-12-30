<template>
	<v-col cols="12" sm="9" md="9" lg="8">
		<v-data-iterator
			:dark="this.$store.getters.hasdarkflag"
			:items="workshift_report"
			:items-per-page.sync="itemsPerPage"
			:page.sync="page"
			:search="search"
			:sort-by="sortBy.toLowerCase()"
			:sort-desc="sortDesc"
			hide-default-footer
		>
			<template v-slot:header>
				<v-toolbar class="mb-1">
					<v-text-field
						v-model="search"
						clearable
						flat
						solo-inverted
						hide-details
						prepend-inner-icon="mdi-magnify"
						label="Search"
					></v-text-field>
					<template v-if="$vuetify.breakpoint.mdAndUp">
						<v-spacer></v-spacer>
						<v-select
							v-model="sortBy"
							flat
							solo-inverted
							hide-details
							:items="keys"
							prepend-inner-icon="mdi-magnify"
							label="Sort by"
						></v-select>
						<v-spacer></v-spacer>
						<v-btn-toggle v-model="sortDesc" mandatory>
							<v-btn large depressed color="blue" :value="false">
								<v-icon>mdi-arrow-up</v-icon>
							</v-btn>
							<v-btn large depressed color="blue" :value="true">
								<v-icon>mdi-arrow-down</v-icon>
							</v-btn>
						</v-btn-toggle>
					</template>
				</v-toolbar>
			</template>

			<template v-slot:default="props">
				<v-row>
					<v-col
						v-for="item in props.items"
						:key="item.name"
						cols="12"
						sm="6"
						md="4"
						lg="3"
					>
						<v-card>
							<v-card-title class="subheading font-weight-bold">
								{{ item.name }}
							</v-card-title>

							<v-divider></v-divider>

							<v-list dense>
								<v-list-item>
									<v-list-item-content class="text-left">
										Tipo: {{ item.rol }}<br />
										Total de venta: ${{ item.total_workshift_sales }}<br />
										Total de propinas: ${{ item.totalTips }}
									</v-list-item-content>
								</v-list-item>
								<!-- <v-list-item v-for="(key, index) in filteredKeys" :key="index">
									<v-list-item-content
										:class="{ 'blue--text': sortBy === key }"
									>
										{{ key }}:
									</v-list-item-content>
									<v-list-item-content
										class="align-end"
										:class="{ 'blue--text': sortBy === key }"
									>
										{{ item[key.toLowerCase()] }}
									</v-list-item-content>
								</v-list-item> -->
							</v-list>
						</v-card>
					</v-col>
				</v-row>
			</template>

			<template v-slot:footer>
				<v-row class="ma-2" align="center" justify="center">
					<span class="grey--text">Items per page</span>
					<v-menu offset-y>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								dark
								text
								color="primary"
								class="ml-2"
								v-bind="attrs"
								v-on="on"
							>
								{{ itemsPerPage }}
								<v-icon>mdi-chevron-down</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item
								v-for="(number, index) in itemsPerPageArray"
								:key="index"
								@click="updateItemsPerPage(number)"
							>
								<v-list-item-title>{{ number }}</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-menu>

					<v-spacer></v-spacer>

					<span class="mr-4 grey--text">
						Page {{ page }} of {{ numberOfPages }}
					</span>
					<v-btn
						fab
						dark
						color="blue darken-3"
						class="mr-1"
						@click="formerPage"
					>
						<v-icon>mdi-chevron-left</v-icon>
					</v-btn>
					<v-btn fab dark color="blue darken-3" class="ml-1" @click="nextPage">
						<v-icon>mdi-chevron-right</v-icon>
					</v-btn>
				</v-row>
			</template>
		</v-data-iterator>
	</v-col>
</template>

<script>
export default {
	name: "report",
	props: {
		workshift_report: {
			type: Array,
			default: () => [],
		},
	},
	data: () => ({
		itemsPerPageArray: [4, 10, 12],
		search: "",
		filter: {},
		sortDesc: false,
		page: 1,
		itemsPerPage: 10,
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
	}),
	computed: {
		numberOfPages() {
			return Math.ceil(this.workshift_report.length / this.itemsPerPage);
		},
		filteredKeys() {
			return this.keys.filter((key) => key !== "Name");
		},
	},
	methods: {
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
};
</script>

<style>
</style>