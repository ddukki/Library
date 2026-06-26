<div x-data="shelfManager">
    <div style="text-align: center; margin-bottom: 1rem">
        <h2>Your Shelves</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem">
        <div style="text-align: center">
            <a href="{{ route('shelves.create') }}">
                <x-card>
                    <div style="text-align: center">
                        Add New Shelf<br/>
                        <h1>+</h1>
                    </div>
                </x-card>
            </a>
        </div>
        <template x-for="(shelf, index) in shelves">
            <div>
                <x-card>
                    <div style="text-align: right; padding: 0.25rem">
                        <a aria-label="Delete shelf" class="badge badge--danger"
                                x-on:click="deleteShelf(shelf.id, index)">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <a x-bind:href="route('shelves.show', { shelf: shelf.id })">
                        <div style="text-align: center; padding: 1rem">
                            <span class="d-block" x-text="shelf.name"></span>
                            <h1><i class="fas fa-archive"></i></h1>
                        </div>
                    </a>
                </x-card>
            </div>
        </template>
    </div>
</div>
