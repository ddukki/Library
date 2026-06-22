import withPagination from './pagination';

export default function (initialSearchTerm, initialSearchColumn) {
    const pagination = withPagination({
        paginationRoute: 'authors.page',
    });

    return {
        ...pagination,
        searchTerm: initialSearchTerm || '',
        searchColumn: initialSearchColumn.length > 0 ? initialSearchColumn : ['first_name', 'middle_name', 'last_name'],

        init() {
            this.getPage(1);
        },

        search() {
            this.getPage(1);
        },

        get authors() {
            return this.page ? this.page.data : [];
        },
    };
}
