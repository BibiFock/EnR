<template>
    <div class="container">
        <component :is="currentView"></component>
        <div class="search">
            <autocomplete
                url="http://nominatim.openstreetmap.org/search"
                anchor="formatted_address"
                label="geometry.location.lat"
                name="autocomplete"
                :customParams="{ format: 'json' }"
                :classes="{ input: 'form-control', wrapper: 'input-wrapper'}"
                :process="processJSON"
                :onSelect="handleSelect" > </autocomplete>
        </div>
    </div>
</template>

<script>

import Simple from './Simple';
import Autocomplete from 'vue2-autocomplete-js'

export default {
    name: 'app',
    components: {
        Simple, Autocomplete
    },
    methods: {
        search: function() {
            console.log('this test');
        }
    },
    data () {
        return {
            currentView: 'simple'
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
