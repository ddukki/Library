<div x-data="shelfManager">
    <div class="index-header">
        <div>
            <h1 class="index-header__title">Your Shelves</h1>
            <p class="index-header__subtitle">Organize your reading collection</p>
        </div>
    </div>
    <div class="shelf-grid">
        <a href="{{ route('shelves.create') }}" class="card card--clickable">
            <div style="text-align: center; padding: 2rem">
                <i class="fas fa-plus" style="font-size: 2rem; color: var(--color-gold); margin-bottom: 0.5rem"></i>
                <div>Add New Shelf</div>
            </div>
        </a>
        <template x-for="(shelf, index) in shelves">
            <div style="position: relative">
                <a aria-label="Delete shelf" class="badge badge--danger"
                        style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 1"
                        x-on:click="deleteShelf(shelf.id, index)">
                    <i class="fas fa-times"></i>
                </a>
                <a x-bind:href="route('shelves.show', { shelf: shelf.id })" class="card card--clickable">
                    <div style="text-align: center; padding: 2rem">
                        <i class="fas fa-archive" style="font-size: 2rem; color: var(--color-gold); margin-bottom: 0.5rem"></i>
                        <div x-text="shelf.name"></div>
                    </div>
                </a>
            </div>
        </template>
    </div>
</div>
