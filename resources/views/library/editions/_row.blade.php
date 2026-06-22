<div class="row border-bottom mt-1">
    <div class="col-3">
        <a class="small" x-bind:href="editionURL(edition)" x-show="!editingRows[index]" x-text="editData[index].name"></a>
        <input x-show="editingRows[index]" class="form-control form-control-sm"
                type="text"
                x-model="editData[index].name">
    </div>
    <div class="col-2">
        <p class="small" x-show="!editingRows[index]" x-text="locationTypeName(edition.location_type?.id)"></p>
        <select x-show="editingRows[index]" class="form-control form-control-sm"
                x-model="editData[index].location_type_id">
            <template x-for="type in locationTypes">
                <option :value="type.id" x-text="type.name"></option>
            </template>
        </select>
    </div>
    <div class="col-2">
        <p class="small" x-show="!editingRows[index]" x-text="editData[index].location_size"></p>
        <input x-show="editingRows[index]" class="form-control form-control-sm"
                type="text" x-model="editData[index].location_size">
    </div>
    <div class="col-3">
        @include('library.editions._shelve')
    </div>
    <div class="col-2">
        <button x-show="!editingRows[index]" class="btn btn-sm btn-primary" x-on:click="toggleEdit(index)">
            <i class="fas fa-edit"></i>
        </button>
        <div x-show="editingRows[index]" class="btn-group" role="group">
            <button class="btn btn-primary btn-sm" x-on:click="updateEdition(index)">
                <i class="fas fa-save"></i>
            </button>
            <button class="btn btn-secondary btn-sm" x-on:click="toggleEdit(index)">
                <i class="fas fa-window-close"></i>
            </button>
        </div>
        <button class="btn btn-sm btn-danger" x-on:click="deleteEdition(index)">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</div>
