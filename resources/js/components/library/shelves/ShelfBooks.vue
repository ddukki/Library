<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2>{{ shelf.name }}</h2>
                <div class="card">
                    <div class="card-body">
                        <a :href="route('shelves.edit', { id: shelf.id })">
                            <i class="fas fa-edit"></i> Edit Shelf
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3 text-center mb-3">
                <a class="card" :href="route('books.index')">
                    <div class="card">
                        <div class="card-body">
                            <p>Add New Book</p>
                            <h1 class="mt-3"><i class="fas fa-plus"></i></h1>
                        </div>
                    </div>
                </a>
            </div>
            <div v-for="shelf in shelf.editions" class="col-3 mb-3">
                <a class="card" :href="route('books.show', { id: shelf.book.id })">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">{{ shelf.book.title }}</div>
                                <div class="col-12 small">{{ joinNames(shelf.book.authors) }}</div>
                                <div class="col-12">
                                    <h1 class="mt-3"><i class="fas fa-book"></i></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        shelf: Object,
    },
    methods: {
        fullName(author) {
            var names = [];
            names.push(author.first_name);
            names.push(author.middle_name);
            names.push(author.last_name);
            return names.filter(Boolean).join(" ");
        },
        joinNames(authors) {
            var names = [];

            authors.forEach((author) => {
                names.push(this.fullName(author));
            });

            return names.join(", ");
        }
    }
}
</script>
