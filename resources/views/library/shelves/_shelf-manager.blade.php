<div x-data="shelfManager" class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <h2>Your Shelves</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-3 text-center">
            <a href="{{ route('shelves.create') }}">
                <div class="card">
                    <div class="card-body">
                        Add New Shelf<br/>
                        <h1>+</h1>
                    </div>
                </div>
            </a>
        </div>
        <template x-for="(shelf, index) in shelves" :key="shelf.id">
            <div class="col-3">
                <div class="card">
                    <div class="p-1 text-right">
                        <a class="badge-danger badge-pill text-small text-light"
                                x-on:click="deleteShelf(shelf.id, index)">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <a class="card-link" :href="route('shelves.show', { shelf: shelf.id })">
                        <div class="card-body text-center" x-text="shelf.name">
                        </div>
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>
