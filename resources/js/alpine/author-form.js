export default function (editAuthorData) {
    return {
        editAuthor: editAuthorData && editAuthorData.id ? editAuthorData : false,
        author: {
            first_name: editAuthorData && editAuthorData.id ? editAuthorData.first_name : '',
            middle_name: editAuthorData && editAuthorData.id ? editAuthorData.middle_name : '',
            last_name: editAuthorData && editAuthorData.id ? editAuthorData.last_name : '',
            id: editAuthorData && editAuthorData.id ? editAuthorData.id : null,
        },
        addAuthor() {
            axios.post(route('authors.store'), {
                author: this.author,
            }).then(response => {
                window.location.replace(route('authors.index'));
            }).catch(error => {
                console.error('Failed to create author:', error.response?.data || error);
            });
        },
        updateAuthor() {
            axios.put(route('authors.update', { author: this.author.id }), {
                author: this.author,
            }).then(response => {
                window.location.replace(route('authors.index'));
            }).catch(error => {
                console.error('Failed to update author:', error.response?.data || error);
            });
        },
    };
}
