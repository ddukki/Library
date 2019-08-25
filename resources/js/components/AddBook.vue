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
            <div class="col-12">
                Author Name(s)
            </div>
        </div>
        <div class="row" v-for="(author, index) in authors">
            <div class="form-group col-4">
                <input name="firstname"
                        type="text"
                        class="form-control"
                        placeholder="First Name"
                        v-model="author.firstname">
            </div>
            <div class="form-group col-3">
                <input name="middlename"
                        type="text"
                        class="form-control"
                        placeholder="Middle Name"
                        v-model="author.middlename">
            </div>
            <div class="form-group col-4">
                <input name="lastname"
                        type="text"
                        class="form-control"
                        placeholder="Last Name"
                        v-model="author.lastname">
            </div>
            <div class="col-1">
                <button v-if="authors.length > 1"
                        class="btn btn-danger"
                        @click.prevent="deleteAuthor(index)">
                    <i class="fas fa-minus"></i>
                </button>
                <button v-else="authors.length > 1"
                        class="btn btn-danger"
                        disabled>
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-12">
                <button class="btn btn-primary"
                        @click.prevent="addAuthor">
                    <i class="fas fa-plus"></i> Add Another Author
                </button>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-12">
                <button class="btn btn-primary"
                        @click.prevent="addBook">
                    Create Book
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: '',
            authors: [
                {
                    firstname: '',
                    middlename: '',
                    lastname: ''
                },
            ],
        }
    },
    methods: {
        addBook() {
            axios.post(route('books.store'), {
                title: this.title,
                authors: this.authors,
            }).then(response => {
                // Redirect to books page
                window.location.replace(route('books.all').url());
            }).catch(error => {});
        },
        addAuthor() {
            this.authors.push(
                {
                    firstname: '',
                    middlename: '',
                    lastname: ''
                });
        },
        deleteAuthor(index) {
            this.authors.splice(index,1);
        }
    },
}
</script>
