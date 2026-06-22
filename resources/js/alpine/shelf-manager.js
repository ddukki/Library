export default function () {
    return {
        shelves: [],
        init() {
            axios.get(route('shelves.index')).then(response => {
                this.shelves = response.data.shelves;
            }).catch(error => {});
        },
        deleteShelf(shelfID, index) {
            axios.delete(route('shelves.destroy', { shelf: shelfID })).then(response => {
                this.shelves.splice(index, 1);
            }).catch(error => {
                console.log(error);
            });
        },
    };
}
