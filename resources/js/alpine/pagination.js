export default function (config) {
    const paginationRoute = config.paginationRoute;

    return {
        page: null,
        loaded: false,

        getPage(n) {
            axios.get(route(paginationRoute, {
                page: n,
                searchColumn: this.searchColumn || [],
                searchTerm: this.searchTerm || '',
                perPage: 10,
            })).then(response => {
                if (response.data) {
                    this.page = response.data.page;
                    this.loaded = true;
                }
            }).catch(error => {
                console.error('Failed to load page:', error.response?.data || error);
            });
        },

        isCurrentPage(n) {
            return this.page && this.page.current_page === n;
        },
    };
}
