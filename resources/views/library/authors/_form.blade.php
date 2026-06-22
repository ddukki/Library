<div x-data="authorForm(@js($author))" class="container">
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
                                    x-model="author.first_name">
                        </div>
                        <div class="form-group col-4">
                            <input name="middlename"
                                    type="text"
                                    class="form-control"
                                    placeholder="Middle Name"
                                    x-model="author.middle_name">
                        </div>
                        <div class="form-group col-4">
                            <input name="lastname"
                                    type="text"
                                    class="form-control"
                                    placeholder="Last Name"
                                    x-model="author.last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <button x-show="!editAuthor" type="button" class="btn btn-primary"
                                    x-on:click="addAuthor">
                                <i class="fas fa-plus"></i> Create Author
                            </button>
                            <button x-show="editAuthor" type="button" class="btn btn-primary"
                                    x-on:click="updateAuthor">
                                <i class="fas fa-plus"></i> Update Author
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
