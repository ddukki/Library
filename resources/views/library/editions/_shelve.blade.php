<div>
    <div x-show="shelveEditRows[index]" class="flex" style="gap: 0.5rem; margin-bottom: 0.5rem">
        <div style="flex: 1">
            <select class="custom-select custom-select--sm"
                    name="selectedShelf"
                    x-model="selectedShelf">
                <template x-for="userShelf in userShelves">
                    <option :value="userShelf.id" x-text="userShelf.name"></option>
                </template>
            </select>
        </div>
        <div>
            <button aria-label="Shelve edition" class="btn btn--primary btn--sm" x-on:click="shelveEdition(index, selectedShelf)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div>
        <template x-for="(shelf, sIndex) in (edition.shelves || [])">
            <span class="badge badge--primary">
                <a class="text-light" style="margin-right: 0.25rem" x-bind:href="shelfURL(shelf)" x-text="shelf.name"></a>
                <a x-show="shelveEditRows[index]" x-on:click="unshelveEdition(index, sIndex)">
                    <i class="fas fa-times-circle"></i>
                </a>
            </span>
        </template>
        <a x-show="!shelveEditRows[index]" class="badge badge--primary text-light"
                x-on:click.prevent="toggleShelveEdit(index)">
            <i class="fas fa-edit"></i>
        </a>
        <a x-show="shelveEditRows[index]" class="badge badge--danger text-light"
                x-on:click.prevent="toggleShelveEdit(index)">
            <i class="fas fa-edit"></i>
        </a>
    </div>
</div>
