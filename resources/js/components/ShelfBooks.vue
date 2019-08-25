<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center">
                <h2>{{ shelf.name }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-3 text-center">
                <a class="card" :href="route('books.all')">
                    <div class="card">
                        <div class="card-body">
                            Add New Book<br/>
                            <h1>+</h1>
                        </div>
                    </div>
                </a>
            </div>
            <div v-for="book in shelf.editions" class="col-3">
                <a class="card" :href="route('books.show', { id: book.id })">
                    <div class="card text-center">
                        <div class="card-body">
                            {{ book.title }}
                            <h1><i class="fas fa-book"></i></h1>
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
}
</script>
