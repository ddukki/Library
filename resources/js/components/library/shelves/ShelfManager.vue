<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center">
                <h2>Your Shelves</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-3 text-center">
                <a :href="route('shelves.create')">
                    <div class="card">
                        <div class="card-body">
                            Add New Shelf<br/>
                            <h1>+</h1>
                        </div>
                    </div>
                </a>
            </div>
            <div v-for="(shelf, index) in shelves" class="col-3">
                <div class="card">
                    <div class="p-1 text-right">
                        <a class="badge-danger badge-pill text-small text-light"
                                @click="deleteShelf(shelf.id, index)">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <a class="card-link" :href="route('shelves.show', { id: shelf.id })">
                    <div class="card-body text-center">
                        {{ shelf.name }}
                        <h1><i class="fas fa-archive"></i></h1>
                    </div>
                    </a>
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
    },
    methods: {
        deleteShelf(shelfID, index) {
            console.log('pressed');

            axios.delete(route('shelves.destroy', {
                shelf: shelfID
            })).then(response => {
                this.shelves.splice(index,1);
            }).catch(error => {
                console.log(error);
            });
        }
    }
}
</script>
