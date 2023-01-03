<template>
	<v-dialog
		v-model="dialogticketviewer"
		fullscreen
		hide-overlay
		transition="dialog-bottom-transition"
	>
		<v-card :dark="this.$store.getters.hasdarkflag === true">
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
				<v-btn
					icon
					:dark="this.$store.getters.hasdarkflag === true"
					@click.prevent="close()"
					x-large
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>

				<v-toolbar-title>Ver ticket</v-toolbar-title>
			</v-toolbar>
			{{ ticket }}
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
		return {};
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