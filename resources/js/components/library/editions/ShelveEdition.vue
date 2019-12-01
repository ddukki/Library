<template>
    <div>
        <div v-if="editing" class="row no-gutters mb-2">
            <div class="col-10">
                <select class="custom-select custom-select-sm"
                        id="locationTypes"
                        v-model="shelf">
                    <option v-for="(userShelf, index) in userShelves"
                            :value="userShelf">
                        {{ userShelf.name }}
                    </option>
                </select>
            </div>
            <div class="col-2">
                <button class="btn btn-primary btn-sm ml-2" @click="shelveEdition">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-12">
                <span v-for="(shelf, index) in shelves"
                        class="badge badge-pill badge-primary">
                    <a class="text-light mr-1" :href="shelfURL(shelf)">{{ shelf.name }}</a>
                    <a v-if="editing" @click="unshelveEdition(index)">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </span>
                <a v-if="!editing" class="badge badge-pill badge-primary text-light"
                        @click.prevent="toggleEdit">
                    <i class="fas fa-edit"></i>
                </a>
                <a v-if="editing" class="badge badge-pill badge-danger text-light"
                        @click.prevent="toggleEdit">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        edition: Object,
        parentEditing: Boolean,
    },
    data() {
        return {
            shelves: [ ... this.edition.shelves ],
            userShelves: [],
            shelf: null,
            editing: this.parentEditing,
        }
    },
    watch: {
        parentEditing: function(val) {
            this.editing = val;
        }
    },
    mounted() {
        axios.get(route('shelves.user')).then(response => {
            this.userShelves = response.data.shelves;
        }).catch(error => {});
    },
    methods: {
        shelfURL(shelf) {
            return route('shelves.show', { id: shelf.id });
        },
        toggleEdit() {
            this.editing = !this.editing;
        },
        isShelved(shelf) {
            var s;
            for(s of this.shelves) {
                if(s.id == shelf.id) {
                    return true;
                }
            }

            return false;
        },
        shelveEdition() {
            // Don't shelve if this edition is already in selected shelf
            if(this.isShelved(this.shelf)) {
                return;
            }

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
