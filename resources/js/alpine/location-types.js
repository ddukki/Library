export default function () {
    return {
        locationTypes: [],
        locationType: { name: '' },
        init() {
            axios.get(route('locationtypes.all')).then(response => {
                this.locationTypes = response.data.locationTypes;
            }).catch(error => {});
        },
        addLocationType() {
            axios.post(route('locationtypes.store'), {
                locationType: this.locationType,
            }).then(response => {
                this.locationTypes.push(response.data.added);
                this.locationType.name = '';
            }).catch(error => {});
        },
        removeLocationType(index) {
            axios.delete(route('locationtypes.destroy', { locationtype: this.locationTypes[index].id })).then(response => {
                this.locationTypes.splice(index, 1);
            }).catch(error => {});
        },
    };
}
