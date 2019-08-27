<template>
<div class="col-12">
    <div class="form-group">
        <label for="shelf_name">Shelf Name</label>
        <input class="form-control"
                id="shelf_name"
                name="shelf_name"
                placeholder="Shelf Name"
                v-model="shelf.name"></input>
    </div>
    <button class="btn btn-primary" @click="addShelf" v-if="!editShelf">
        + Add
    </button>
    <button class="btn btn-primary" @click="updateShelf" v-else>
        + Update
    </button>
</div>
</template>

<script>
export default {
    props: ['editShelf'],
    mounted: function() {
        if (this.editShelf) {
            this.shelf = this.editShelf;
        }
    },
    methods: {
        addShelf() {
            axios.post(route('shelves.store'), {
                shelf: this.shelf,
            }).then(response => {
                // Redirect to authors page
                window.location.replace(route('shelves.show', { id: this.shelf.id }).url());
            }).catch(error => {});
        },
        updateShelf() {
            axios.put(route('shelves.update', { id: this.shelf.id, }), {
                shelf: this.shelf,
            }).then(response => {
                // Redirect to authors page
                window.location.replace(route('shelves.show', { id: this.shelf.id }).url());
            })
            .catch(error => {});
        },
    },
    data() {
        return {
            shelf: {
                name: '',
            }
        }
    }
}
</script>
