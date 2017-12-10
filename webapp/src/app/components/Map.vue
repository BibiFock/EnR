<template>
    <div class="map-container">
        <v-map class="map-container" :zoom="zoom" :center="[this.lat, this.lng]"
            v-on:l-moveend="updateGeo">
            <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
            <v-marker v-for="roof in roofs"  :key="roof.id"
                :lat-lng="[roof.latitude, roof.longitude]"
                v-on:l-click="showDetail(roof)" >
                <v-tooltip :content="getToolTip(roof)"></v-tooltip>
                <!-- <v-popup :content="roof.name"></v-popup> -->
            </v-marker>
        </v-map>
        <div class="extra-content">
            <!-- temporary hide this ->
            <button type="button" v-on:click="close()" aria-label="Close"
                class="btn-close m-3 py-1 px-2 close border rounded border-white bg-white">
                <span aria-hidden="true">&times;</span>
            </button>
            <!- /temporary hide this -->
            <button type="button" class="btn btn-primary d-inline-block"
                v-on:click="addRoof()">
                add
            </button>

            <autocomplete
                class="d-inline-block"
                :url="urlAutocomplete"
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
import Vue2Leaflet from 'vue2-leaflet';
// import RoofMini from 'roof/Mini';

import Autocomplete from 'vue2-autocomplete-js'
require('../../../node_modules/vue2-autocomplete-js/dist/style/vue2-autocomplete.css')

export default {
    props: {
        roofs: { type: Array, default: [] }
    },
    components: {
        Autocomplete,
        'v-map': Vue2Leaflet.Map,
        'v-tilelayer': Vue2Leaflet.TileLayer,
        'v-marker': Vue2Leaflet.Marker,
        // 'v-popup': Vue2Leaflet.Popup,
        'v-tooltip': Vue2Leaflet.Tooltip
    },
    methods: {
        updateGeo: function(e) {
            let center = e.target.getCenter();

            this.$cookie.set('map-center', [center.lat, center.lng], 30);
        },
        handleSelect: function(obj) {
            this.lat = obj.lat;
            this.lng = obj.lon;
        },
        getToolTip: function(roof) {
            return '<div><strong>' + roof.name + '</strong></div>'
                + '<div>' + (roof.structure ? roof.structure.name : '-') + '</div>'
                + '<div class="text-right">' + roof.power_max + ' kWc</div>';

        },
        addRoof: function() {
            this.$router.push({
                name:'roof',
                params: { roofId: 0 }
            });
        },
        showDetail: function(roof) {
            this.$router.push({
                name: 'roof',
                params: { roofId: roof.id }
            });
        },
        close: function() {
            this.$router.push({
                name: 'home'
            });
            this.$emit('close');
        },
        loadRoofs: function () {
            this.$http.get(
                process.env.API_URL + 'roofs'
            ).then(
                response => {
                    this.roofs = response.body;
                }
            );
        },
    },
    mounted() {
        this.$router.push({
            name: 'map'
        });
        this.loadRoofs();
    },
    data () {
        let center = [ process.env.COORD.LATITUDE, process.env.COORD.LONGITUDE ];
        if (this.$cookie.get('map-center')) {
            let cookieCenter = this.$cookie.get('map-center').split(',');
            center = cookieCenter;
        }

        return {
            zoom:13,
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            lat: center[0],
            lng: center[1],
            urlAutocomplete: process.env.API_URL + 'map/search'
        }
    }
}
</script>

<style>
@import "../../../node_modules/leaflet/dist/leaflet.css";

.leaflet-fake-icon-image-2x {
  background-image: url(../../../node_modules/leaflet/dist/images/marker-icon.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../../../node_modules/leaflet/dist/images/marker-shadow.png);
}

.map-container {
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
}
.extra-content {
    z-index:500;
    position:absolute;
    right:0;
}
.close {
    opacity:1;
}

</style>
