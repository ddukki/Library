<div x-data="locationTypes" class="col-12">
    <table class="table table-sm" x-show="locationTypes.length > 0">
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
                    <button class="btn btn-danger btn-sm" x-on:click="removeLocationType(index)">
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
        <input class="form-control" type="text" x-model="locationType.name">
        <div class="input-group-append">
            <button class="btn btn-primary" x-on:click="addLocationType">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    </div>
</div>
