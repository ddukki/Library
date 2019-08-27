<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        Book Information
                    </div>
                    <div class="card-body">
                        <input id="title" name="title"
                                type="text"
                                class="form-control"
                                placeholder="Book Title"
                                v-model="book.title">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-4">
                <select-authors
                    :initial-page="initialAuthors"
                    :initial-selected="book.authors">
                </select-authors>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="form-group col-12">
                        <button v-if="!editBook" class="btn btn-primary btn-lg"
                                @click="addBook">
                            Create Book
                        </button>
                        <button v-else class="btn btn-primary btn-lg"
                                @click="updateBook">
                            Update Book
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectAuthors from '../authors/SelectAuthors.vue';
export default {
    props: ['editBook', 'initialAuthors'],
    mounted: function() {
        if (this.editBook) {
            this.book = this.editBook;
        }
    },
    data() {
        return {
            book: {
                title: '',
                authors: [],
            },
        }
    },
    methods: {
        addBook() {
            axios.post(route('books.store'), {
                book: this.book,
            }).then(response => {
                // Redirect to books page
                window.location.replace(route('books.index').url());
            }).catch(error => {});
        },
        updateBook() {
            axios.put(route('books.update', { id: this.book.id, }), {
                book: this.book,
            }).then(response => {
                // Redirect to books page
                window.location.replace(route('books.index').url());
            })
            .catch(error => {});
        },
        addAuthor(e) {
            this.book.authors.push(e);
        },
        removeAuthor(e) {
            this.book.authors.splice(e,1);
        },
    },
}
</script>
