<template>
    <div class="container">
        <div class="row form-group">
            <input type="search" class="form-control" placeholder="recherche" >
            <a href="#" v-on:click="toggleMap()">
                <small class="form-text text-muted">Voir la carte</small>
            </a>
        </div>
        <div>
            <Roof v-for="roof in roofs"
                  class="mb-3"
                :key="roof.id" :roof="roof" type="list"></Roof>
        </div>
        <Map :lat="lat" :lng="lng" :markers="roofs" v-show="showMap"></Map>
    </div>
    <!-- <div class="container"> -->
        <!-- <Map :lat="lat" :lng="lng" :markers="markers" v-on:showDetail="showDetail"></Map> -->
        <!-- <div class="search"> -->
            <!-- <autocomplete -->
                <!-- url="http://nominatim.openstreetmap.org/search" -->
                <!-- anchor="display_name" -->
                <!-- name="autocomplete" -->
                <!-- label="" -->
                <!-- placeholder="search" -->
                <!-- debounce="500" -->
                <!-- :customParams="{ format: 'json' }" -->
                <!-- :classes="{ input: 'form-control', wrapper: 'input-wrapper'}" -->
                <!-- :onSelect="handleSelect" > </autocomplete> -->
        <!-- </div> -->
        <!-- <Detail class="details" -->
            <!-- v-if="selectedMarker" -->
            <!-- :marker="selectedMarker" -->
            <!-- v-on:close="closeDetail"></Detail> -->
    <!-- </div> -->
</template>

<script>
import Map from './Map';
import Roof from './Roof'
//import Autocomplete from 'vue2-autocomplete-js'
//require("../../node_modules/vue2-autocomplete-js/dist/style/vue2-autocomplete.css")

export default {
    name: 'app',
    components: {
        Map, Roof
    },
    methods: {
        loadRoofs: function () {
            this.$http.get(
                // 'http://raw.githubusercontent.com/BibiFock/EnR/master/data.json'
                'http://localhost:8080/api/roofs'
            ).then(
                response => {
                    this.roofs = response.body;
                    console.log(this.roofs);
                },
                response => {
                    console.log(response)
                    alert('todo make nofication error');
                }
            );
        }
    },
    mounted() {
        this.loadRoofs();
    },
    data () {
        return {
            lat:undefined,
            lng:undefined,
            selectedRoof: undefined,
            roofs:[]
        }
    }
}
</script>
