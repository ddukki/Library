import withPagination from './pagination';

export default function (initialSearchTerm, initialSearchColumn) {
    const pagination = withPagination({
        paginationRoute: 'books.page',
    });

    return {
        ...pagination,
        searchTerm: initialSearchTerm || '',
        searchColumn: initialSearchColumn.length > 0 ? initialSearchColumn : ['title'],

        init() {
            this.getPage(1);
        },

        search() {
            this.getPage(1);
        },

        get books() {
            return this.page ? this.page.data : [];
        },
    };
}
