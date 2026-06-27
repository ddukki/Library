<div class="edition-row">
    <div>
        <a class="small" x-bind:href="editionURL(edition)" x-show="!editingRows[index]" x-text="editData[index].name"></a>
        <input aria-label="Edit edition name" x-show="editingRows[index]" class="form-input__field"
                type="text"
                x-model="editData[index].name">
    </div>
    <div>
        <p class="small" x-show="!editingRows[index]" x-text="locationTypeName(edition.location_type?.id)"></p>
        <select x-show="editingRows[index]" class="form-input__field"
                x-model="editData[index].location_type_id">
            <template x-for="type in locationTypes">
                <option :value="type.id" x-text="type.name"></option>
            </template>
        </select>
    </div>
    <div>
        <p class="small" x-show="!editingRows[index]" x-text="editData[index].location_size"></p>
        <input aria-label="Edit edition size" x-show="editingRows[index]" class="form-input__field"
                type="text" x-model="editData[index].location_size">
    </div>
    <div>
        @include('library.editions._shelve')
    </div>
    <div>
        <button aria-label="Edit edition" x-show="!editingRows[index]" class="btn btn--primary btn--sm" x-on:click="toggleEdit(index)">
            <i class="fas fa-edit"></i>
        </button>
        <div x-show="editingRows[index]" class="btn-group">
            <button aria-label="Save edition" class="btn btn--primary btn--sm" x-on:click="updateEdition(index)">
                <i class="fas fa-save"></i>
            </button>
            <button aria-label="Cancel editing" class="btn btn--secondary btn--sm" x-on:click="toggleEdit(index)">
                <i class="fas fa-window-close"></i>
            </button>
        </div>
        <button aria-label="Delete edition" class="btn btn--danger btn--sm" x-on:click="deleteEdition(index)">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</div>
