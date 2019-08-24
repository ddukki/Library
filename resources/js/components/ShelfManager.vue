<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-3 text-center">
				<a class="card" :href="route('shelves.add')">
					<div class="card">
						<div class="card-body">
							Add New Book<br/>
							<h1>+</h1>
						</div>
					</div>
				</a>
			</div>
			<div v-for="shelf in shelves" class="col-3">
				<div class="card text-center">
					<div class="card-body">
						{{ shelf.name }}
						<h1><i class="fas fa-archive"></i></h1>
					</div>
				</div>
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
