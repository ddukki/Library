<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="form-group">
                        <label for="title" class="form-label">
                            Book Title
                        </label>
                        <input id="title" name="title"
                                type="text"
                                class="form-control"
                                v-model="title">
                    </div>
                </div>
                <div class="row" v-for="(author, index) in authors">
                    <div class="form-group col-10">
                        <label for="title" class="form-label">
                            Author
                        </label>
                        <input name="firstname"
                                type="text"
                                class="form-control"
                                v-model="author.firstname">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-danger">
                            <i class="fas fa-plus">Remove Author</i>
                        </button>
                    </div>
                </div>
                <div class="form-group col-12">
                    <div class="form-group" v-for="(author, index) in authors">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus">Add Another Author</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
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
        addAuthor() {
            this.authors.push({
                {
                    firstname: '',
                    middlename: '',
                    lastname: ''
                },
            });
        }
    },
}
</script>
