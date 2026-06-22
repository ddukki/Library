export default function (editShelfData) {
    return {
        editShelf: editShelfData && editShelfData.id ? editShelfData : false,
        shelf: {
            name: editShelfData && editShelfData.id ? editShelfData.name : '',
            id: editShelfData && editShelfData.id ? editShelfData.id : null,
        },
        addShelf() {
            axios.post(route('shelves.store'), {
                shelf: this.shelf,
            }).then(response => {
                window.location.replace(route('shelves.show', { shelf: this.shelf.id }));
            }).catch(error => {
                console.error('Failed to create shelf:', error.response?.data || error);
            });
        },
        updateShelf() {
            axios.put(route('shelves.update', { shelf: this.shelf.id }), {
                shelf: this.shelf,
            }).then(response => {
                window.location.replace(route('shelves.show', { shelf: this.shelf.id }));
            }).catch(error => {
                console.error('Failed to update shelf:', error.response?.data || error);
            });
        },
    };
}
