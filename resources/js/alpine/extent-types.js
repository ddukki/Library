export default function () {
    return {
        extentTypes: [],
        extentType: { name: '' },
        editingIndex: null,
        editName: '',

        init() {
            axios.get(route('extenttypes.all')).then(response => {
                this.extentTypes = response.data.extentTypes;
            });
        },

        addExtentType() {
            axios.post(route('extenttypes.store'), {
                extentType: this.extentType,
            }).then(response => {
                this.extentTypes.push(response.data.added);
                this.extentType.name = '';
            });
        },

        toggleEdit(index) {
            if (this.editingIndex === index) {
                this.cancelEdit();
            } else {
                this.editingIndex = index;
                this.editName = this.extentTypes[index].name;
            }
        },

        saveEdit(extentType, index) {
            if (this.editName.trim() === '' || this.editName === extentType.name) {
                this.cancelEdit();
                return;
            }

            const doUpdate = () => {
                axios.put(route('extenttypes.update', { extenttype: extentType.id }), {
                    extentType: { name: this.editName },
                }).then(response => {
                    this.extentTypes[index] = response.data.updated;
                    this.editingIndex = null;
                    this.editName = '';
                });
            };

            axios.get(route('extenttypes.usage', { extenttype: extentType.id })).then(response => {
                const count = response.data.editions_count;

                if (count > 0) {
                    const msg = count === 1
                        ? `Warning: this extent type is used by 1 edition.`
                        : `Warning: this extent type is used by ${count} editions.`;

                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: msg, variant: 'warning', duration: 5000 },
                    }));
                }

                doUpdate();
            });
        },

        cancelEdit() {
            this.editingIndex = null;
            this.editName = '';
        },

        removeExtentType(index) {
            axios.delete(route('extenttypes.destroy', { extenttype: this.extentTypes[index].id })).then(response => {
                this.extentTypes.splice(index, 1);
            });
        },
    };
}
