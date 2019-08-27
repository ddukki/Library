<template>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        <a :href="route('books.show', { id: book.id })">
                            {{ book.title }}
                        </a>
                        <br/>
                        <p class="small" v-if="book.authors.length > 0">
                            By:
                            <span v-for="n in book.authors.length">
                                <template v-if="n < book.authors.length">
                                    {{ book.authors[n - 1].first_name }} {{ book.authors[n - 1].middle_name }} {{ book.authors[n - 1].last_name }},
                                </template>
                                <template v-else>
                                    {{ book.authors[n - 1].first_name }} {{ book.authors[n - 1].middle_name }} {{ book.authors[n - 1].last_name }}
                                </template>
                            </span>
                        </p>
                    </div>
                    <div class="col-2">
                        <a :href="route('books.edit', { id: book.id }).url()">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        book: Object,
    }
}
</script>
