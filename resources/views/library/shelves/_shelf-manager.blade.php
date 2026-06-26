<div x-data="shelfManager">
    <div style="text-align: center; margin-bottom: 1rem">
        <h2>Your Shelves</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem">
        <a href="{{ route('shelves.create') }}" style="display: block">
            <x-card>
                <div style="text-align: center">
                    Add New Shelf<br/>
                    <h1>+</h1>
                </div>
            </x-card>
        </a>
        <template x-for="(shelf, index) in shelves">
            <div style="position: relative">
                <a aria-label="Delete shelf" class="badge badge--danger"
                        style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 1"
                        x-on:click="deleteShelf(shelf.id, index)">
                    <i class="fas fa-times"></i>
                </a>
                <a x-bind:href="route('shelves.show', { shelf: shelf.id })" style="display: block">
                    <x-card>
                        <div style="text-align: center">
                            <span class="d-block" x-text="shelf.name"></span>
                            <h1><i class="fas fa-archive"></i></h1>
                        </div>
                    </x-card>
                </a>
            </div>
        </template>
    </div>
</div>
