<div x-data="authorForm(@js($author))" class="container">
    <x-card compact>
        <x-slot:header>New Author</x-slot:header>

        <div style="margin-bottom: 0.5rem">Name</div>
        <div class="form-row">
            <div class="form-group">
                <label for="firstname" class="visually-hidden">First Name</label>
                <input id="firstname" name="firstname"
                        type="text"
                        class="form-input__field"
                        placeholder="First Name"
                        x-model="author.first_name">
            </div>
            <div class="form-group">
                <label for="middlename" class="visually-hidden">Middle Name</label>
                <input id="middlename" name="middlename"
                        type="text"
                        class="form-input__field"
                        placeholder="Middle Name"
                        x-model="author.middle_name">
            </div>
            <div class="form-group">
                <label for="lastname" class="visually-hidden">Last Name</label>
                <input id="lastname" name="lastname"
                        type="text"
                        class="form-input__field"
                        placeholder="Last Name"
                        x-model="author.last_name">
            </div>
        </div>
        <div class="form-group">
            <x-button x-show="!editAuthor" x-on:click="addAuthor">
                <i class="fas fa-plus"></i> Create Author
            </x-button>
            <x-button x-show="editAuthor" x-on:click="updateAuthor">
                <i class="fas fa-plus"></i> Update Author
            </x-button>
        </div>
    </x-card>
</div>
