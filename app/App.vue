<template>
    <div class="container">
        <simple :lat="lat" :lng="lng" ></simple>
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
    </div>
</template>

<script>

import Simple from './Simple';
import Autocomplete from 'vue2-autocomplete-js'
require("../node_modules/vue2-autocomplete-js/dist/style/vue2-autocomplete.css")

export default {
    name: 'app',
    components: {
        Simple, Autocomplete
    },
    methods: {
        handleSelect: function(obj) {
            this.lat = obj.lat;
            this.lng = obj.lon;
        }
    },
    data () {
        return {
            lat:undefined,
            lng:undefined
        }
    }
}
</script>

<style>
/*.leaflet-fake-icon-image-2x {
  background-image: url(../../node_modules/leaflet/dist/images/marker-icon-2x.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../../node_modules/leaflet/dist/images/marker-shadow.png);
}
@import "../../node_modules/leaflet/dist/leaflet.css";
*/

.leaflet-fake-icon-image-2x {
  background-image: url(../node_modules/leaflet/dist/images/marker-icon-2x.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../node_modules/leaflet/dist/images/marker-shadow.png);
}

@import "../node_modules/leaflet/dist/leaflet.css";

.search {
    position:absolute; top:0; right:0; z-index:800;
}

</style>
