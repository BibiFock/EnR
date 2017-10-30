<template>
    <div class="map-container">
        <v-map class="map-container" :zoom="zoom" :center="[this.lat, this.lng]">
            <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
            <v-marker v-for="marker in markers"  :key="marker.id"
                :lat-lng="[marker.latitude, marker.longitude]"
                v-on:l-click="showDetail(marker)" >
                <!-- <v-popup :content="marker.name"></v-popup> -->
            </v-marker>
        </v-map>

        <button type="button" class="m-2 py-1 px-2 close border rounded border-white bg-white" aria-label="Close"
            v-on:click="close()">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
import Vue2Leaflet from 'vue2-leaflet';

export default {
    props: {
        lat:{ type:Number, default:48.8566 },
        lng: { type:Number, default: 2.3522 },
        markers: { type: Array, default: [] }
    },
    components: {
        'v-map': Vue2Leaflet.Map,
        'v-tilelayer': Vue2Leaflet.TileLayer,
        'v-marker': Vue2Leaflet.Marker,
        'v-popup': Vue2Leaflet.Popup
    },
    methods: {
        showDetail: function(marker) {
            this.$emit('showDetail', marker);
        },
        close: function() {
            this.$emit('close');
        }
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
</style>
