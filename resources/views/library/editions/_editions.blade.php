<div x-data="bookEditions(@js($book))">
    <div class="border-bottom" style="display: grid; grid-template-columns: 3fr 2fr 2fr 3fr 2fr; gap: 0; padding: 0.5rem 0">
        <div class="small" style="font-weight: 600">Name</div>
        <div class="small" style="font-weight: 600">Format</div>
        <div class="small" style="font-weight: 600">Size</div>
        <div class="small" style="font-weight: 600">Shelves</div>
        <div class="small" style="font-weight: 600">Actions</div>
    </div>

    <template x-for="(edition, index) in editions">
        @include('library.editions._row')
    </template>

    <x-button x-on:click="toggleAddForm" style="margin-top: 0.5rem">
        <i class="fas fa-plus"></i> Add Edition
    </x-button>

    <div x-show="showAddForm" style="margin-top: 1rem">
        <div class="form-group">
            <label for="name">Edition Name</label>
            <input type="text" id="name"
                    class="form-input__field"
                    placeholder="Edition Name"
                    x-model="newEdition.name">
        </div>
        <div class="form-group">
            <label for="locationTypes">Edition Type</label>
            <select class="form-input__field"
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
                    class="form-input__field"
                    placeholder="Size"
                    x-model="newEdition.size">
        </div>
        <x-button x-on:click="addEdition">
            Submit
        </x-button>
    </div>
</div>
