<template>
    <div class="container">
        <Map :lat="lat" :lng="lng" :markers="markers" v-on:showDetail="showDetail"></Map>
        <div class="search">
            <autocomplete
                url="http://nominatim.openstreetmap.org/search"
                anchor="display_name"
                name="autocomplete"
                label=""
                placeholder="search"
                debounce="500"
                :customParams="{ format: 'json' }"
                :classes="{ input: 'form-control', wrapper: 'input-wrapper'}"
                :onSelect="handleSelect" > </autocomplete>
        </div>
        <Detail class="details"
            v-if="selectedMarker"
            :marker="selectedMarker"
            v-on:close="closeDetail"></Detail>
    </div>
</template>

<script>
import Map from './Map';
import Detail from './Detail'
import Autocomplete from 'vue2-autocomplete-js'
require("../node_modules/vue2-autocomplete-js/dist/style/vue2-autocomplete.css")

export default {
    name: 'app',
    components: {
        Map, Autocomplete, Detail
    },
    methods: {
        handleSelect: function(obj) {
            this.lat = obj.lat;
            this.lng = obj.lon;
        },
        showDetail: function(marker) {
            this.selectedMarker = marker
            this.$router.push({ name: 'markers', params: { markerId: marker.id } });
        },
        closeDetail: function() {
            this.$router.push({ name: 'home' });
            this.selectedMarker = null;
        },
        loadMarkers: function () {
            this.$http.get(
                'https://raw.githubusercontent.com/BibiFock/EnR/preview/data.json'
            ).then(
                response => {
                    this.markers = response.body.map((el, index) => {
                        el.id = index;
                        return el;
                    });
                    if (this.$route.params.markerId) {
                        this.showDetail(this.markers[this.$route.params.markerId]);
                    }

                },
                response => console.log(response)
            );
        }
    },
    mounted() {
        this.loadMarkers();
    },

    data () {
        return {
            lat:undefined,
            lng:undefined,
            selectedMarker: undefined,
            markers:[]
        }
    }
}
</script>

<style>
.search {
    position:absolute; top:0; right:0; z-index:800;
}

.details {
    position:absolute; top:0; bottom:0; left:0; right:0; z-index:800;
    padding:3em;
    max-width:none;
    background-color:white;
    overflow-y:auto;
}
</style>
