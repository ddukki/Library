import shelving from './book-editions-shelve';

export default function (bookData) {
    const editions = (bookData.editions || []).map(e => ({ ...e }));

    return {
        editions: editions,
        locationTypes: [],
        newEdition: { name: '', type_id: null, size: 0 },
        showAddForm: false,
        editingRows: editions.map(() => false),
        editData: editions.map(e => ({ ...e })),

        ...shelving,

        init() {
            axios.get(route('locationtypes.all')).then(response => {
                this.locationTypes = response.data.locationTypes;
                if (this.locationTypes.length > 0) {
                    this.newEdition.type_id = this.locationTypes[0].id;
                }
            }).catch(error => {});
            axios.get(route('shelves.user')).then(response => {
                this.userShelves = response.data.shelves;
                if (this.userShelves.length > 0) {
                    this.selectedShelf = this.userShelves[0].id;
                }
            }).catch(error => {});
            this.shelveEditRows = this.editions.map(() => false);
        },

        toggleAddForm() {
            this.showAddForm = !this.showAddForm;
            if (this.showAddForm) {
                this.newEdition = { name: '', type_id: null, size: 0 };
                if (this.locationTypes.length > 0) {
                    this.newEdition.type_id = this.locationTypes[0].id;
                }
            }
        },

        addEdition() {
            axios.post(route('editions.store'), {
                book: bookData,
                edition: {
                    name: this.newEdition.name,
                    location_type_id: this.newEdition.type_id,
                    location_size: this.newEdition.size,
                },
            }).then(response => {
                this.editions.push(response.data.added);
                this.editingRows.push(false);
                this.editData.push({ ...response.data.added });
                this.newEdition = { name: '', type_id: null, size: 0 };
                this.showAddForm = false;
            }).catch(error => {});
        },

        deleteEdition(index) {
            axios.delete(route('editions.destroy', { edition: this.editions[index].id })).then(response => {
                this.editions.splice(index, 1);
                this.editingRows.splice(index, 1);
                this.editData.splice(index, 1);
            }).catch(error => {});
        },

        toggleEdit(index) {
            this.editingRows[index] = !this.editingRows[index];
            this.editData[index] = {
                ...this.editions[index],
                location_type_id: this.editions[index].location_type?.id ?? null,
            };
        },

        updateEdition(index) {
            axios.put(route('editions.update', { edition: this.editions[index].id }), {
                edition: {
                    name: this.editData[index].name,
                    location_type_id: this.editData[index].location_type_id,
                    location_size: this.editData[index].location_size,
                },
            }).then(response => {
                const type = this.locationTypes.find(t => t.id == this.editData[index].location_type_id);
                this.editions[index] = { ...this.editData[index], location_type: type };
                this.editingRows[index] = false;
            }).catch(error => {});
        },

        editionURL(edition) {
            return route('editions.show', { edition: edition.id });
        },

        locationTypeName(id) {
            const type = this.locationTypes.find(t => t.id === id);
            return type ? type.name : '';
        },
    };
}
