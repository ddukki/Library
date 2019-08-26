<template>
    <div class="col-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"
                                placeholder="Search Books"
                                aria-label="Search for books"
                                v-model="searchTerm">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" @click.prevent="search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <a :href="route('books.create')"
                            class="btn btn-primary"
                            role="button">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="row mb-2" v-for="(book, index) in books">
                <book-card :book="book" :key="index">
                </book-card>
            </div>
            <div class="row">
                <div class="col-12">
                    <pagination-vue @paginated="updateBooks"
                            :pagination-route="paginationRoute"
                            :search-column="searchColumn"
                            :search-term="searchTerm"
                            ref="pagination">
                    </pagination-vue>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BookCard from './BookCard.vue';
export default {
    components: {
        BookCard,
    },
    props: {
        initialSearchTerm: String,
        initialSearchColumn: Array,
    },
    data() {
        return {
            page: null,
            searchTerm: '',
            searchColumn: ['title'],
            paginationRoute: 'books.page',
        }
    },
    computed: {
        books: function() {
            if (this.page) {
                return this.page.data;
            }
            return [];
        }
    },
    mounted: function() {
        this.searchTerm = this.initialSearchTerm;
        this.searchColumn = this.initialSearchColumn;

        axios.get(route('books.page'), {
            page: 1,
            searchTerm: this.searchTerm,
            searchColumn: this.searchColumn,
        }).then(response => {
            this.page = response.data.page;
        }).catch(error => {

        });
    },
    methods: {
        updateBooks(e) {
            this.page = e;
        },
        search() {
            this.$refs.pagination.getPage(1);
        }
    }
}
</script>
