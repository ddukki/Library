<template>
    <div class="col-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"
                                placeholder="Search Authors"
                                aria-label="Search for authors"
                                v-model="searchTerm">
                        <div class="input-group-append">
                            <button class="btn btn-primary" @click.prevent="search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <a :href="route('authors.create')"
                            class="btn btn-primary"
                            role="button">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="row mb-2" v-for="(author, index) in authors">
                <author-card :author="author" :key="index">
                </author-card>
            </div>
            <div class="row">
                <div class="col-12">
                    <pagination-vue @paginated="updateAuthors"
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
import AuthorCard from './AuthorCard.vue';
export default {
    components: {
        AuthorCard,
    },
    props: {
        initialSearchTerm: String,
        initialSearchColumn: Array,
    },
    data() {
        return {
            page: null,
            searchTerm: '',
            searchColumn: ['first_name', 'last_name'],
            paginationRoute: 'authors.page',
        }
    },
    computed: {
        authors: function() {
            if (this.page) {
                return this.page.data;
            }
            return [];
        }
    },
    mounted: function() {
        this.searchTerm = this.initialSearchTerm;
        this.searchColumn = this.initialSearchColumn;

        axios.get(route('authors.page'), {
            page: 1,
            searchTerm: this.searchTerm,
            searchColumn: this.searchColumn,
        }).then(response => {
            this.page = response.data.page;
        }).catch(error => {

        });
    },
    methods: {
        updateAuthors(e) {
            this.page = e;
        },
        search() {
            this.$refs.pagination.getPage(1);
        }
    }
}
</script>
