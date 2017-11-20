<template>
    <div class="container">
        <router-view></router-view>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import Map from './Map';
import Roof from './Roof';

export default {
    name: 'app',
    components: {
        Map, Roof
    },
    methods: {
        loadRoofs: function () {
            this.$http.get(
                process.env.API_URL + 'roofs'
            ).then(
                response => {
                    this.roofs = response.body;
                },
                response => {
                    console.log(response)
                    alert('todo make nofication error');
                }
            );
        },
        toggleMap: function() {
            this.showMap = !this.showMap;
        },
        closeMap: function() {
            this.showMap = false;
        }
    },
    data () {
        return {
            lat:undefined,
            lng:undefined,
            selectedRoof: undefined,
            roofs:[],
            showMap: true
        }
    }
}
</script>
