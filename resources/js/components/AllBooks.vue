<template>
    <div class="col-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-9">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"
                                placeholder="Search Books"
                                aria-label="Search for books"
                                aria-describedby="basic-addon2"
                                v-model="title">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" @click.prevent="search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <a :href="route('books.create')"
                            class="btn btn-primary"
                            role="button">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
            </div>
            <div class="row">
                <book-card v-for="(book, index) in books"
                    :book="book"
                    :key="index">
                </book-card>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            books: [],
            title: '',
        }
    },
    mounted: function() {
        axios.get(route('books.index')).then(response => {
            this.books = response.data.books;
        }).catch(error => {

        });
    },
    methods: {
        search() {
            var formdata = {
                title: this.title ? this.title : null,
            }

            axios.get(route('books.index'), formdata).then(response => {
                this.books = response.data.books;
            }).catch(error => {

            });
        }
    }
}
</script>
