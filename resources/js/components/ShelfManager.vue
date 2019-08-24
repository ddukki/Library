<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 text-center">
				<h2>Your Shelves</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-3 text-center">
				<a class="card" :href="route('shelves.create')">
					<div class="card">
						<div class="card-body">
							Add New Shelf<br/>
							<h1>+</h1>
						</div>
					</div>
				</a>
			</div>
			<div v-for="shelf in shelves" class="col-3">
				<a class="card" :href="route('shelves.show', { id: shelf.id })">
					<div class="card text-center">
						<div class="card-body">
							{{ shelf.name }}
							<h1><i class="fas fa-archive"></i></h1>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			shelves: [],
		}
	},
	mounted: function() {
		axios.get(route('shelves.index')).then(response => {
			this.shelves = response.data.shelves;
		}).catch(error => {

		});
	}
}
</script>
