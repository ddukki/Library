<template>
<div class="col-12">
    <table class="table table-sm" v-if="locationTypes.length > 0">
        <thead>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col"></th>
        </thead>
        <tr v-for="(locationType, index) in locationTypes">
            <td>{{ locationType.id }}</td>
            <td>{{ locationType.name }}</td>
            <td>
                <button class="btn btn-danger btn-sm" @click="removeLocationType(index)">
                    <i class="fas fa-minus"></i>
                </button>
            </td>
        </tr>
    </table>
    <p v-else>
        Add <b>Location Types</b> to start creating book editions!
    </p>
    <div class="input-group">
        <input class="form-control" type="text" v-model="locationType.name">
        <div class="input-group-append">
            <button class="btn btn-primary" @click="addLocationType">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {

    },
    data() {
        return {
            locationTypes: [],
            locationType: {
                name: '',
            }
        }
    },
    mounted: function() {
        axios.get(route('locationtypes.all')).then(response =>{
            this.locationTypes = response.data.locationTypes;
        }).catch(error => {

        });
    },
    methods: {
        addLocationType() {
            axios.post(route('locationtypes.store'), {
                locationType: this.locationType
            }).then(response => {
                this.locationTypes.push(response.data.added);
                this.locationType.name = '';
            }).catch(error => {

            });
        },
        removeLocationType(index) {
            axios.delete(route('locationtypes.destroy', {
                id: this.locationTypes[index].id
            })).then(response => {
                this.locationTypes.splice(index,1);
            }).catch(error => {

            });
        }
    }
}
</script>
