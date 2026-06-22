<div>
    <div x-show="shelveEditRows[index]" class="row no-gutters mb-2">
        <div class="col-10">
            <select class="custom-select custom-select-sm"
                    name="selectedShelf"
                    x-model="selectedShelf">
                <template x-for="userShelf in userShelves">
                    <option :value="userShelf.id" x-text="userShelf.name"></option>
                </template>
            </select>
        </div>
        <div class="col-2">
            <button class="btn btn-primary btn-sm ml-2" x-on:click="shelveEdition(index, selectedShelf)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-12">
            <template x-for="(shelf, sIndex) in (edition.shelves || [])">
                <span class="badge badge-pill badge-primary">
                    <a class="text-light mr-1" x-bind:href="shelfURL(shelf)" x-text="shelf.name"></a>
                    <a x-show="shelveEditRows[index]" x-on:click="unshelveEdition(index, sIndex)">
                        <i class="fas fa-times-circle"></i>
                    </a>
                </span>
            </template>
            <a x-show="!shelveEditRows[index]" class="badge badge-pill badge-primary text-light"
                    x-on:click.prevent="toggleShelveEdit(index)">
                <i class="fas fa-edit"></i>
            </a>
            <a x-show="shelveEditRows[index]" class="badge badge-pill badge-danger text-light"
                    x-on:click.prevent="toggleShelveEdit(index)">
                <i class="fas fa-edit"></i>
            </a>
        </div>
    </div>
</div>
