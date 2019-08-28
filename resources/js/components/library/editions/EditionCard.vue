<template>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="edition_name" class="small">Name:</label>
                        <p v-if="!editing">{{ editEdition.name }}</p>
                        <input v-else class="form-control"
                                type="text"
                                v-model="editEdition.name">
                    </div>
                    <div class="form-group">
                        <label for="edition_name" class="small">Format:</label>
                        <p v-if="!editing">{{ editEdition.location_type.name }}</p>
                        <select v-else class="form-control"
                                id="locationTypes"
                                v-model="editEdition.location_type">
                            <option v-for="(type, index) in locationTypes"
                                    :value="type">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edition_name" class="small">Size (Pages/Location):</label>
                        <p v-if="!editing">{{ editEdition.location_size }}</p>
                        <input v-else class="form-control" type="text" v-model="editEdition.location_size">
                    </div>
                </div>
                <div class="col-6">
                    <shelve-edition
                            :edition="edition">
                    </shelve-edition>
                </div>
            </div>
            <div class="row">
                <div class="col-9">
                    <button v-if="!editing" class="btn btn-primary" @click="toggleEdit">
                        Edit
                    </button>
                    <div v-else class="btn-group" role="group">
                        <button class="btn btn-primary" @click="updateEdition">
                            Save
                        </button>
                        <button class="btn btn-secondary" @click="toggleEdit">
                            Cancel
                        </button>
                    </div>
                </div>
                <div class="col-3 pull-right">
                    <button class="btn btn-danger" @click="deleteEdition">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        initialEdition: Object,
        locationTypes: Array,
    },
    data() {
        return {
            edition: { ... this.initialEdition},
            editEdition: { ... this.initialEdition},
            editing: false,
        }
    },
    methods: {
        toggleEdit() {
            this.editing = !this.editing;
            this.editEdition = { ... this.edition};
        },
        updateEdition() {
            axios.put(route('editions.update', { id: this.edition.id }), {
                edition: this.editEdition,
            }).then(response => {
                this.edition = { ... this.editEdition};
                this.editing = false;
            }).catch(error => {});
        },
        deleteEdition() {
            axios.delete(route('editions.destroy', {
                id: this.edition.id
            })).then(response => {
                this.$emit('deleted');
            }).catch(error => {});
        },
    }
}
</script>
