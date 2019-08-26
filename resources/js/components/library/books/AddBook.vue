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
                <select-authors :initial-selected="book.authors">
                </select-authors>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="form-group col-12">
                        <button class="btn btn-primary btn-lg"
                                @click.prevent="addBook">
                            Create Book
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['editBook'],
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
            if (this.editBook) {
                axios.post(route('books.update'), {
                    book: this.book,
                }).then(response => {
                    // Redirect to books page
                    window.location.replace(route('books.all').url());
                }).catch(error => {});
            }
            else {
                axios.post(route('books.store'), {
                    book: this.book,
                }).then(response => {
                    // Redirect to books page
                    window.location.replace(route('books.all').url());
                }).catch(error => {});
            }
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
