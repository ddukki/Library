<div x-data="locationTypes">
    <table class="table" x-show="locationTypes.length > 0">
        <thead>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col"></th>
        </thead>
        <template x-for="(locationType, index) in locationTypes">
            <tr>
                <td x-text="locationType.id"></td>
                <td x-text="locationType.name"></td>
                <td>
                    <button aria-label="Delete location type" class="btn btn--danger btn--sm" x-on:click="removeLocationType(index)">
                        <i class="fas fa-minus"></i>
                    </button>
                </td>
            </tr>
        </template>
    </table>
    <p x-show="locationTypes.length === 0">
        Add <b>Location Types</b> to start creating book editions!
    </p>
    <div class="input-group">
        <input aria-label="New location type" class="form-input__field" type="text" x-model="locationType.name">
        <div class="input-group__append">
            <x-button x-on:click="addLocationType">
                <i class="fas fa-plus"></i> Add
            </x-button>
        </div>
    </div>
</div>
