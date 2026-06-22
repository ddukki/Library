<div x-data="shelfForm(@js($shelf))" class="col-12">
    <div class="form-group">
        <label for="shelf_name">Shelf Name</label>
        <input class="form-control"
               id="shelf_name"
               name="shelf_name"
               placeholder="Shelf Name"
               x-model="shelf.name">
    </div>
    <button class="btn btn-primary" x-on:click="addShelf" x-show="!editShelf">
        + Add
    </button>
    <button class="btn btn-primary" x-on:click="updateShelf" x-show="editShelf">
        + Update
    </button>
</div>
