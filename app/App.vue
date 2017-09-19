<template>
    <div class="container">
        <component :is="currentView"></component>
        <div class="search">
            <input type="text" placeholder="search" v-on:change="search"/>
        </div>
    </div>
</template>

<script>

import Simple from './Simple';

export default {
    name: 'app',
    components: {
        Simple,
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
    position:absolute; top:0; left:0; z-index:800;
}

</style>
