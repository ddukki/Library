export default {
    userShelves: [],
    shelveEditRows: [],
    selectedShelf: null,

    toggleShelveEdit(index) {
        this.shelveEditRows[index] = !this.shelveEditRows[index];
        if (this.shelveEditRows[index] && this.userShelves.length > 0) {
            this.selectedShelf = this.userShelves[0].id;
        }
    },

    shelfURL(shelf) {
        return route('shelves.show', { shelf: shelf.id });
    },

    isShelved(shelf, editionShelves) {
        return editionShelves.some(s => s.id === shelf.id);
    },

    shelveEdition(editionIndex, shelfId) {
        const shelf = this.userShelves.find(s => s.id == shelfId);
        if (!shelf) return;
        const edition = this.editions[editionIndex];
        if (this.isShelved(shelf, edition.shelves || [])) return;

        axios.post(route('editions.shelve', {
            edition: edition.id,
            shelf: shelf.id,
        })).then(response => {
            if (!this.editions[editionIndex].shelves) {
                this.editions[editionIndex].shelves = [];
            }
            this.editions[editionIndex].shelves.push(shelf);
        }).catch(error => {});
    },

    unshelveEdition(editionIndex, shelfIndex) {
        const edition = this.editions[editionIndex];
        axios.post(route('editions.unshelve', {
            edition: edition.id,
            shelf: edition.shelves[shelfIndex].id,
        })).then(response => {
            this.editions[editionIndex].shelves.splice(shelfIndex, 1);
        }).catch(error => {});
    },
};
