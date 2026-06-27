<div x-data="authorForm(@js($author))" class="container" style="max-width: 600px; margin: 0 auto;">
    <div class="form-card">
        <div class="form-card__header">
            <span class="form-card__header-title" x-text="editAuthor ? 'Edit Author' : 'New Author'">New Author</span>
        </div>
        <div class="form-card__body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="firstname" class="form-group__label form-group__required">First Name</label>
                    <input id="firstname" name="firstname"
                            type="text"
                            class="form-input__field"
                            placeholder="First name"
                            x-model="author.first_name">
                </div>
                <div class="form-group">
                    <label for="middlename" class="form-group__label">Middle Name</label>
                    <input id="middlename" name="middlename"
                            type="text"
                            class="form-input__field"
                            placeholder="Middle name (optional)"
                            x-model="author.middle_name">
                </div>
                <div class="form-group form-grid__full">
                    <label for="lastname" class="form-group__label form-group__required">Last Name</label>
                    <input id="lastname" name="lastname"
                            type="text"
                            class="form-input__field"
                            placeholder="Last name"
                            x-model="author.last_name">
                </div>
            </div>
            <div class="form-actions form-actions--right">
                <span x-show="!editAuthor">
                    <x-button variant="primary" x-on:click="addAuthor">Save Author</x-button>
                </span>
                <span x-show="editAuthor">
                    <x-button variant="primary" x-on:click="updateAuthor">Update Author</x-button>
                </span>
            </div>
        </div>
    </div>
</div>
