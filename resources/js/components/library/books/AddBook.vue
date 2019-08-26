<template>
    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-12">
                <label for="title" class="form-label">
                    Book Title
                </label>
                <input id="title" name="title"
                        type="text"
                        class="form-control"
                        placeholder="Book Title"
                        v-model="title">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 mb-4">
                <select-authors :initialPage="initialAuthors"
                        @selected="addAuthor"
                        @unselected="removeAuthor">
                </select-authors>
            </div>
            <div class="col-12 col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        New Author Name(s)
                    </div>
                    <div class="card-body container-fluid">
                        <div class="form-row" v-for="(newAuthor, index) in newAuthors">
                            <div class="form-group col-4">
                                <input name="firstname"
                                        type="text"
                                        class="form-control"
                                        placeholder="First Name"
                                        v-model="newAuthor.firstname">
                            </div>
                            <div class="form-group col-3">
                                <input name="middlename"
                                        type="text"
                                        class="form-control"
                                        placeholder="Middle Name"
                                        v-model="newAuthor.middlename">
                            </div>
                            <div class="form-group col-4">
                                <input name="lastname"
                                        type="text"
                                        class="form-control"
                                        placeholder="Last Name"
                                        v-model="newAuthor.lastname">
                            </div>
                            <div class="col-1">
                                <button v-if="newAuthors.length > 1"
                                        class="btn btn-danger"
                                        @click.prevent="deleteAuthor(index)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button v-else
                                        class="btn btn-danger"
                                        disabled>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                        @click.prevent="addNewAuthor">
                                    <i class="fas fa-plus"></i> Add Another Author
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
            newAuthors: [{
                firstname: '',
                middlename: '',
                lastname: ''
            }],
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
