<template>
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                Selected Authors
            </div>
            <div class="card-body">
                <p v-if="selected.length == 0">
                    Select authors for the book from the list of <b>Available
                    Authors</b>! If the author of this book is not available
                    (be sure to use the search function), then add a new author
                    in the <b>New Author Name(s)</b> section.
                </p>
                <h3><span class="badge badge-primary mr-2" v-for="select in selected">
                    {{ select.first_name }} {{ select.middle_name }} {{ select.last_name }}
                    <a @click.prevent="unselectAuthor(select)">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </span></h3>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                Available Authors
            </div>
            <div class="card-body">
                <div class="input-group mb-2">
                    <input type="text"
                            class="form-control"
                            v-model="searchTerm"
                            placeholder="Search Available Authors">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" @click="searchAuthors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <table class="table table-sm">
                    <thead>
                        <th scope="col"></th>
                        <th scope="col">Author Name</th>
                        <th scope="col">Books</th>
                    </thead>
                    <tr v-for="(author, index) in authors">
                        <td>
                            <button v-if="!isSelected(author)"
                                    class="btn btn-sm btn-outline-primary"
                                    @click="selectAuthor(author)">
                                <i class="fas fa-plus"></i>
                            </button>

                            <button v-else
                                    class="btn btn-sm btn-outline-danger"
                                    @click="unselectAuthor(author)">
                                <i class="fas fa-minus"></i>
                            </button>
                        </td>
                        <td>{{ author.first_name }} {{ author.middle_name }} {{ author.last_name }}</td>
                        <td>
                            <span class="badge badge-primary mr-1" v-for="book in author.books">
                                {{ book.title }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
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
import Pagination from '../Pagination.vue';
export default {
    props: {
        initialPage: Object,
    },
    mounted() {
        this.page = this.initialPage;
    },
    data() {
        return {
            paginationRoute: 'authors.page',
            selected: [],
            page: null,
            searchColumn: new Array("first_name", "last_name"),
            searchTerm: null,
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
    methods: {
        searchAuthors() {
            this.$refs.pagination.getPage(this.page.current_page);
        },
        updateAuthors(e) {
            this.page = e;
        },
        isSelected(author) {
            var found = false;
            this.selected.forEach(e => {
                if (e.id == author.id) {
                    found = true;
                    return;
                }
            });

            return found;
        },
        selectAuthor(author) {
            if (!this.isSelected(author)) {
                this.selected.push(author);
                this.$emit('selected', author);
            }
        },
        unselectAuthor(select) {
            var index = -1;
            this.selected.forEach((e, i) => {
                if (e.id == select.id) {
                    index = i;
                    return;
                }
            });

            if (index > -1) {
                this.selected.splice(index,1);
                this.$emit('unselected', index);
            }
        }
    },
}
</script>
