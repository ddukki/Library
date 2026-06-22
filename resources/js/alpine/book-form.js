import withPagination from './pagination';

export default function (bookData, initialAuthorsPage) {
    const pagination = withPagination({
        paginationRoute: 'authors.page',
    });

    return {
        ...pagination,

        editing: !!bookData,
        book: {
            id: bookData?.id || null,
            title: bookData?.title || '',
            authors: bookData?.authors || [],
        },

        searchTerm: '',
        searchColumn: ['first_name', 'last_name'],

        init() {
            if (initialAuthorsPage) {
                this.page = initialAuthorsPage;
            }
        },

        get authors() {
            return this.page ? this.page.data : [];
        },

        searchAuthors() {
            this.getPage(1);
        },

        isSelected(author) {
            return this.book.authors.some(a => a.id == author.id);
        },

        selectAuthor(author) {
            if (!this.isSelected(author)) {
                this.book.authors.push(author);
            }
        },

        unselectAuthor(author) {
            const index = this.book.authors.findIndex(a => a.id == author.id);
            if (index > -1) {
                this.book.authors.splice(index, 1);
            }
        },

        createBook() {
            axios.post(route('books.store'), {
                book: {
                    title: this.book.title,
                    authors: this.book.authors,
                },
            }).then(response => {
                window.location.replace(route('books.index'));
            }).catch(error => {
                console.error('Failed to create book:', error.response?.data || error);
            });
        },

        updateBook() {
            axios.put(route('books.update', { book: this.book.id }), {
                book: {
                    title: this.book.title,
                    authors: this.book.authors,
                },
            }).then(response => {
                window.location.replace(route('books.index'));
            }).catch(error => {
                console.error('Failed to update book:', error.response?.data || error);
            });
        },
    };
}
