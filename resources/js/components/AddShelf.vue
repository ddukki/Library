<template>
<div class="col-12">
	<form method="POST" :action="getAddRoute">
		<div class="form-group">
			<label for="shelf_name">Shelf Name</label>
			<input class="form-control"
					id="shelf_name"
					name="shelf_name"
					placeholder="Shelf Name"
					v-model="shelfname"></input>
		</div>
		<button class="btn btn-primary" type="submit">
			+ Add
		</button>
	</form>
</div>
</template>

<script>
export default {
	data() {
		return {
			shelfname: '',
		}
	},
	methods: {
		getAddRoute() {
			return route('shelves.store');
		},
	}
}
</script>
