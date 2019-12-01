<template>
    <div class="row">
        <div class="col-12">
            <div class="row border-bottom">
                <div class="col-3">
                    Name
                </div>
                <div class="col-1">
                    Format
                </div>
                <div class="col-2">
                    Size
                </div>
                <div class="col-4">
                    Shelves
                </div>
                <div class="col-2">
                    Actions
                </div>
            </div>
            <edition-row v-for="(edition, index) in editions"
                    :initial-edition="edition"
                    :location-types="locationTypes"
                    @deleted="deleteEdition(index)"
                    :key="index">
            </edition-row>

            <button @click="showAddForm"
                    class="btn btn-primary mt-2" role="button">
                <i class="fas fa-plus"></i> Add Edition
            </button>
            <div id="addForm" class="row mt-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name">Edition Name</label>
                        <input type="text" id="name"
                                class="form-control"
                                placeholder="Edition Name"
                                v-model="edition.name">
                    </div>
                    <div class="form-group">
                        <label for="type">Edition Type</label>
                        <select class="form-control"
                                id="locationTypes"
                                v-model="edition.type">
                            <option v-for="(type, index) in locationTypes"
                                    :value="type">
                                {{ type.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="size">Edition Size</label>
                        <input type="text" id="size"
                                class="form-control"
                                placeholder="Size"
                                v-model="edition.size">
                    </div>
                    <button @click="addEdition"
                            class="btn btn-primary" role="button">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EditionCard from './EditionCard.vue'
export default {
    components: { EditionCard },
    props: ['book'],
    data() {
        return {
            locationTypes: [],
            editions: this.book.editions,
            edition: {
                name: '',
                type: null,
                size: 0,
            }
        }
    },
    mounted: function() {
        $('#addForm').toggle();
        axios.get(route('locationtypes.all')).then(response => {
            this.locationTypes = response.data.locationTypes;
        }).catch(error => {});
    },
    methods: {
        showAddForm() {
            $('#addForm').toggle();
        },
        addEdition() {
            axios.post(route('editions.store'), {
                book: this.book,
                edition: this.edition,
            }).then(response => {
                this.editions.push(response.data.added);
                this.edition = {
                    name: '',
                    type: null,
                    size: 0,
                };
                $('#addForm').toggle();
            }).catch(error => {});
        },
        deleteEdition(index) {
            this.editions.splice(index,1);
        }
    }
}
</script>
