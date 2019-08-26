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
                                v-model="title">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-4">
                <select-authors :initial-page="initialAuthors"
                        :initial-selected="[]"
                        @selected="addAuthor"
                        @unselected="removeAuthor">
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
    props: {
        initialAuthors: Object,
    },
    data() {
        return {
            title: '',
            newAuthors: [],
            authors: [],
        }
    },
    methods: {
        addBook() {
            axios.post(route('books.store'), {
                title: this.title,
                authors: this.newAuthors,
                existingAuthors: this.authors,
            }).then(response => {
                // Redirect to books page
                window.location.replace(route('books.all').url());
            }).catch(error => {});
        },
        addAuthor(e) {
            this.authors.push(e);
        },
        removeAuthor(e) {
            this.authors.splice(e,1);
        },
        addNewAuthor() {
            this.newAuthors.push({
                firstname: '',
                middlename: '',
                lastname: ''
            });
        },
        deleteAuthor(index) {
            this.newAuthors.splice(index,1);
        }
    },
}
</script>
