<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-3 text-center">
				<div class="card">
					<div class="card-body">
						Add New Book<br/>
						<h1>+</h1>
					</div>
				</div>
			</div>
			<div v-for="shelf in shelves" class="col-3">

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
	mounted: {

	},
	methods: {

	},
}
</script>
