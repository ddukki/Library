<div x-data="shelfForm(@js($shelf))">
    <div class="form-group">
        <label for="shelf_name">Shelf Name</label>
        <input class="form-input__field"
               id="shelf_name"
               name="shelf_name"
               placeholder="Shelf Name"
               x-model="shelf.name">
    </div>
    <x-button x-on:click="addShelf" x-show="!editShelf">
        + Add
    </x-button>
    <x-button x-on:click="updateShelf" x-show="editShelf">
        + Update
    </x-button>
</div>
