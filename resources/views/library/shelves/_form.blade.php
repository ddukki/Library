<div x-data="shelfForm(@js($shelf))">
    <div class="form-group">
        <label class="form-group__label" for="shelf_name">Shelf Name</label>
        <input class="form-input__field"
               id="shelf_name"
               name="shelf_name"
               placeholder="Shelf Name"
               x-model="shelf.name">
    </div>
    <div class="form-actions">
        <x-button x-on:click="addShelf" x-show="!editShelf">
            Add Shelf
        </x-button>
        <x-button x-on:click="updateShelf" x-show="editShelf">
            Update Shelf
        </x-button>
    </div>
</div>
