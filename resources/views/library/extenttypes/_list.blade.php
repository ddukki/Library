<div x-data="extentTypes">
    <p class="caption" style="margin-bottom: 1.5rem">
        An <strong>extent</strong> is the total count of a unit type that an edition spans &mdash; e.g. "350 pages," "5423 Kindle locations," "12 chapters."
        The <strong>extent type</strong> defines what those units are (page, location, chapter, position, etc.).
        Your reading progress is measured against this extent.
    </p>

    <table class="table" x-show="extentTypes.length > 0">
        <thead>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col"></th>
        </thead>
        <template x-for="(extentType, index) in extentTypes">
            <tr>
                <td x-text="extentType.id"></td>
                <td>
                    <span x-show="editingIndex !== index" x-text="extentType.name"></span>
                    <input x-show="editingIndex === index" class="form-input__field" type="text"
                           x-model="editName" x-on:keydown.enter="saveEdit(extentType, index)"
                           x-on:keydown.escape="cancelEdit">
                </td>
                <td>
                    <template x-if="editingIndex !== index">
                        <button aria-label="Edit extent type" class="btn btn--sm" x-on:click="toggleEdit(index)">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </template>
                    <template x-if="editingIndex === index">
                        <button aria-label="Save extent type" class="btn btn--success btn--sm" x-on:click="saveEdit(extentType, index)">
                            <i class="fas fa-check"></i>
                        </button>
                    </template>
                    <template x-if="editingIndex !== index">
                        <button aria-label="Delete extent type" class="btn btn--danger btn--sm" x-on:click="removeExtentType(index)">
                            <i class="fas fa-minus"></i>
                        </button>
                    </template>
                    <template x-if="editingIndex === index">
                        <button aria-label="Cancel edit" class="btn btn--sm" x-on:click="cancelEdit">
                            <i class="fas fa-times"></i>
                        </button>
                    </template>
                </td>
            </tr>
        </template>
    </table>
    <p x-show="extentTypes.length === 0" style="color: var(--color-text-muted)">
        Add <strong>Extent Types</strong> to start creating book editions!
    </p>
    <div class="input-group" style="margin-top: 1rem">
        <input aria-label="New extent type" class="form-input__field" type="text" x-model="extentType.name" placeholder="New extent type...">
        <div class="input-group__append">
            <x-button x-on:click="addExtentType">
                <i class="fas fa-plus"></i> Add
            </x-button>
        </div>
    </div>
</div>
