<template>
    <div class="container">
        <div class="row">
            <input type="search" class="form-control" placeholder="recherche" >
        </div>
        <div class="d-flex align-items-end justify-content-end">
            <button type="button" class="btn btn-link btn-sm" v-on:click="toggleMap()">
                Voir la carte
            </button>
        </div>
        <div>
            <Roof v-for="roof in roofs" class="mb-3"
                :key="roof.id" :roof="roof" type="list"></Roof>
        </div>
        <Map :lat="lat" :lng="lng" :roofs="roofs"
            v-if="showMap" v-on:close="closeMap"></Map>
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
    mounted() {
        this.loadRoofs();
        if (this.$route.name == "map") {
            this.showMap = true;
        }
    },
    data () {
        return {
            lat:undefined,
            lng:undefined,
            selectedRoof: undefined,
            roofs:[],
            showMap: false
        }
    }
}
</script>
