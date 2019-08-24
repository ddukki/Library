<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-3 text-center">
				<a :href="route('shelves.add')">
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
					<div class="card-header">
						{{ shelf.name }}
					</div>
					<div class="card-body">
						<h1><i class="fa fa-archive"></i></h1>
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
}
</script>
