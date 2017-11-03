<template>
    <div class="map-container">
        <v-map class="map-container" :zoom="zoom" :center="[this.lat, this.lng]">
            <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
            <v-marker v-for="roof in roofs"  :key="roof.id"
                :lat-lng="[roof.latitude, roof.longitude]"
                v-on:l-click="showDetail(roof)" >
                <v-tooltip :content="getToolTip(roof)"></v-tooltip>
                <!-- <v-popup :content="roof.name"></v-popup> -->
            </v-marker>
        </v-map>
        <div class="extra-content">
            <button type="button" v-on:click="close()" aria-label="Close"
                class="btn-close m-3 py-1 px-2 close border rounded border-white bg-white">
                <span aria-hidden="true">&times;</span>
            </button>

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
import Vue2Leaflet from 'vue2-leaflet';
// import RoofMini from 'roof/Mini';

import Autocomplete from 'vue2-autocomplete-js'
require('../../node_modules/vue2-autocomplete-js/dist/style/vue2-autocomplete.css')

export default {
    props: {
        lat:{ type:Number, default:48.8566 },
        lng: { type:Number, default: 2.3522 },
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
        handleSelect: function(obj) {
            this.lat = obj.lat;
            this.lng = obj.lon;
        },
        getToolTip: function(roof) {
            return '<div>' + roof.structure.name + '</div>'
                + '<div class="text-right">' + roof.power_max + ' kWc</div>';

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
        }
    },
    mounted() {
        this.$router.push({
            name: 'map'
        });
    },
    data () {
        return {
            zoom:13,
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        }
    }
}
</script>

<style>
@import "../../node_modules/leaflet/dist/leaflet.css";

.leaflet-fake-icon-image-2x {
  background-image: url(../../node_modules/leaflet/dist/images/marker-icon.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../../node_modules/leaflet/dist/images/marker-shadow.png);
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
