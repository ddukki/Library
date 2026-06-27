export default function () {
    return {
        extentTypes: [],
        extentType: { name: '' },
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
        removeExtentType(index) {
            axios.delete(route('extenttypes.destroy', { extenttype: this.extentTypes[index].id })).then(response => {
                this.extentTypes.splice(index, 1);
            });
        },
    };
}
