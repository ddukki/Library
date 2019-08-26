<template>
    <nav v-if="page && page.last_page > 1">
        <ul class="pagination justify-content-center" role="navigation">
            <li v-for="n in page.last_page" :class="pageClass(n)">
                <a class="page-link" @click="getPage(n)">
                    {{n}}
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: {
            paginationRoute: String,
            searchColumn: Array,
            searchTerm: String,
        },
        data() {
            return {
                page: null,
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.getPage(1);
            });
        },
        methods: {
            getPage: function(page) {
                axios.get(route(this.paginationRoute, {
                    page: page,
                    searchColumn: this.searchColumn,
                    searchTerm: this.searchTerm,
                    perPage: 10,
                })).then(response => {
                    if (response.data) {
                        this.page = response.data.page;
                        this.$emit('paginated', this.page);
                    }
                }).catch(error => {

                });
            },
            pageClass: function(n) {
                if (this.page.current_page == n) {
                    return "page-item active"
                }
                else {
                    return "page-item"
                }
            },
        }
    }
</script>
