<template>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    New Author
                </div>
                <div class="card-body container-fluid">
                    <div class="row">
                        <div class="col-12">
                            Name
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-4">
                            <input name="firstname"
                                    type="text"
                                    class="form-control"
                                    placeholder="First Name"
                                    v-model="author.first_name">
                        </div>
                        <div class="form-group col-4">
                            <input name="middlename"
                                    type="text"
                                    class="form-control"
                                    placeholder="Middle Name"
                                    v-model="author.middle_name">
                        </div>
                        <div class="form-group col-4">
                            <input name="lastname"
                                    type="text"
                                    class="form-control"
                                    placeholder="Last Name"
                                    v-model="author.last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <button v-if="!editAuthor" type="button" class="btn btn-primary"
                                    @click.prevent="addAuthor">
                                <i class="fas fa-plus"></i> Create Author
                            </button>
                            <button v-else type="button" class="btn btn-primary"
                                    @click.prevent="updateAuthor">
                                <i class="fas fa-plus"></i> Update Author
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['editAuthor'],
    mounted: function() {
        if (this.editAuthor) {
            this.author = this.editAuthor;
        }
    },
    methods: {
        addAuthor() {
            axios.post(route('authors.store'), {
                author: this.author,
            }).then(response => {
                // Redirect to authors page
                window.location.replace(route('authors.index').url());
            }).catch(error => {});
        },
        updateAuthor() {
            axios.put(route('authors.update', { id: this.author.id, }), {
                author: this.author,
            }).then(response => {
                // Redirect to authors page
                window.location.replace(route('authors.index').url());
            })
            .catch(error => {});
        },
    },
    data() {
        return {
            author: {
                first_name: '',
                middle_name: '',
                last_name: ''
            }
        }
    }
}
</script>
