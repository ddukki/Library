<div x-data="bookEditions(@js($book))">
    <div class="edition-row__header">
        <div>Name</div>
        <div>Format</div>
        <div>Size</div>
        <div>Shelves</div>
        <div>Actions</div>
    </div>

    <template x-for="(edition, index) in editions">
        @include('library.editions._row')
    </template>

    <div class="form-actions">
        <x-button x-on:click="toggleAddForm">
            <i class="fas fa-plus"></i> Add Edition
        </x-button>
    </div>

    <div x-show="showAddForm" style="margin-top: 1rem">
        <div class="form-group">
            <label class="form-group__label" for="name">Edition Name</label>
            <input type="text" id="name"
                    class="form-input__field"
                    placeholder="Edition Name"
                    x-model="newEdition.name">
        </div>
        <div class="form-group">
            <label class="form-group__label" for="extentTypes">Extent Type</label>
            <select class="form-input__field"
                    id="extentTypes"
                    x-model="newEdition.type_id">
                <template x-for="type in extentTypes">
                    <option :value="type.id" x-text="type.name"></option>
                </template>
            </select>
        </div>
        <div class="form-group">
            <label class="form-group__label" for="size">Edition Size</label>
            <input type="text" id="size"
                    class="form-input__field"
                    placeholder="Size"
                    x-model="newEdition.size">
        </div>
        <x-button x-on:click="addEdition">
            Submit
        </x-button>
    </div>
</div>
