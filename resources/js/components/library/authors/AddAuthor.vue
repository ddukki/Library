<template>
    <div class="card mb-4">
        <div class="card-header">
            New Author
        </div>
        <div class="card-body container-fluid">
            <div class="row">
                Name
            </div>
            <div class="form-row">
                <div class="form-group col-4">
                    <input name="firstname"
                            type="text"
                            class="form-control"
                            placeholder="First Name"
                            v-model="firstname">
                </div>
                <div class="form-group col-4">
                    <input name="middlename"
                            type="text"
                            class="form-control"
                            placeholder="Middle Name"
                            v-model="middlename">
                </div>
                <div class="form-group col-4">
                    <input name="lastname"
                            type="text"
                            class="form-control"
                            placeholder="Last Name"
                            v-model="lastname">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12">
                    <button type="button" class="btn btn-outline-primary btn-sm"
                            @click.prevent="addAuthor">
                        <i class="fas fa-plus"></i> Create Author
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>
