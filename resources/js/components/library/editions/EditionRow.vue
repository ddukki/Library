<template>
    <div class="row border-bottom mt-2">
        <div class="col-3 form-group">
            <p v-if="!editing">{{ editEdition.name }}</p>
            <input v-else class="form-control form-control-sm"
                    type="text"
                    v-model="editEdition.name">
        </div>
        <div class="col-1 form-group">
            <p v-if="!editing">{{ editEdition.location_type.name }}</p>
            <select v-else class="form-control form-control-sm"
                    id="locationTypes"
                    v-model="editEdition.location_type">
                <option v-for="(type, index) in locationTypes"
                        :value="type">
                    {{ type.name }}
                </option>
            </select>
        </div>
        <div class="col-2 form-group">
            <p v-if="!editing">{{ editEdition.location_size }}</p>
            <input v-else class="form-control form-control-sm" type="text" v-model="editEdition.location_size">
        </div>
        <div class="col-4 form-group">
            <shelve-edition :edition="edition">
            </shelve-edition>
        </div>
        <div class="col-2">
            <button v-if="!editing" class="btn btn-sm btn-primary" @click="toggleEdit">
                <i class="fas fa-edit"></i>
            </button>
            <div v-else class="btn-group" role="group">
                <button class="btn btn-primary btn-sm" @click="updateEdition">
                    <i class="fas fa-save"></i>
                </button>
                <button class="btn btn-secondary btn-sm" @click="toggleEdit">
                    <i class="fas fa-window-close"></i>
                </button>
            </div>
            <button class="btn btn-sm btn-danger" @click="deleteEdition">
                <i class="fas fa-trash-alt"></i>
            </button>
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
