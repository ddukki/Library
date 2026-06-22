<div x-data="bookEditions(@js($book))" class="row">
    <div class="col-12">
        <div class="row border-bottom">
            <div class="col-3">Name</div>
            <div class="col-2">Format</div>
            <div class="col-2">Size</div>
            <div class="col-3">Shelves</div>
            <div class="col-2">Actions</div>
        </div>

        <template x-for="(edition, index) in editions">
            @include('library.editions._row')
        </template>

        <button x-on:click="toggleAddForm"
                class="btn btn-primary mt-2" role="button">
            <i class="fas fa-plus"></i> Add Edition
        </button>

        <div x-show="showAddForm" class="row mt-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="name">Edition Name</label>
                    <input type="text" id="name"
                            class="form-control"
                            placeholder="Edition Name"
                            x-model="newEdition.name">
                </div>
                <div class="form-group">
                    <label for="type">Edition Type</label>
                    <select class="form-control"
                            id="locationTypes"
                            x-model="newEdition.type_id">
                        <template x-for="type in locationTypes">
                            <option :value="type.id" x-text="type.name"></option>
                        </template>
                    </select>
                </div>
                <div class="form-group">
                    <label for="size">Edition Size</label>
                    <input type="text" id="size"
                            class="form-control"
                            placeholder="Size"
                            x-model="newEdition.size">
                </div>
                <button x-on:click="addEdition"
                        class="btn btn-primary" role="button">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>
