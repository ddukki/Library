<template>
    <div class="row">
        <div class="col-12">
            <p class="small mb-0">Your Shelves with this Edition:</p>
            <select class="custom-select"
                    id="locationTypes"
                    v-model="shelf">
                <option v-for="(userShelf, index) in userShelves"
                        :value="userShelf">
                    {{ userShelf.name }}
                </option>
            </select>
            <span v-for="(shelf, index) in shelves"
                    class="badge badge-pill badge-primary mr-1">
                {{ shelf.name }}
                <a @click="unshelveEdition(index)">
                    <i class="fas fa-times-circle"></i>
                </a>
            </span>
            <button class="btn btn-primary mt-3" @click="shelveEdition">
                Add to Shelf
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        edition: Object,
    },
    data() {
        return {
            shelves: [ ... this.edition.shelves ],
            userShelves: [],
            shelf: null,
        }
    },
    mounted() {
        axios.get(route('shelves.user')).then(response => {
            this.userShelves = response.data.shelves;
        }).catch(error => {});
    },
    methods: {
        shelveEdition() {
            axios.post(route('editions.shelve', {
                edition: this.edition.id,
                shelf: this.shelf.id,
            })).then(response => {
                this.shelves.push(this.shelf);
            }).catch(error => {});
        },
        unshelveEdition(index) {
            axios.post(route('editions.unshelve', {
                edition: this.edition.id,
                shelf: this.shelves[index].id,
            })).then(response => {
                this.shelves.splice(index,1);
            }).catch(error => {});
        }
    }
}
</script>
