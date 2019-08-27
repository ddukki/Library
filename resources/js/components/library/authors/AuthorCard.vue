<template>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        {{ author.first_name }} {{ author.middle_name }} {{ author.last_name }}
                        <br/>
                        <p class="small mb-0 mt-3" v-if="author.books.length > 0">
                            Books:
                        </p>
                        <span class="badge badge-primary mr-1" v-for="book in author.books">
                            {{ book.title }}
                        </span>
                    </div>
                    <div class="col-2">
                        <a :href="route('authors.edit', { id: author.id }).url()">
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
        author: Object,
    }
}
</script>
