<template>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        {{ book.title }}<br/>
                    </div>
                    <div class="col-2">
                        <a @click.prevent="edit">
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
