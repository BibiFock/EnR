<template>
    <div class="map-container">
      <!-- <h2>Simple map</h2> -->
        <!-- Marker is placed at {{ marker.lat }}, {{ marker.lng }} -->
      <!-- </br> -->
      <v-map class="map-container" :zoom="zoom" :center="[this.lat, this.lng]">
        <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
        <v-marker :lat-lng="marker"></v-marker>
      </v-map>
    </div>
</template>

<script>
import Vue2Leaflet from 'vue2-leaflet';

export default {
    props: {
        lat:{ type:Number, default:47.413220 },
        lng: { type:Number, default: -1.219482 }
    },
    components: {
        'v-map': Vue2Leaflet.Map,
        'v-tilelayer': Vue2Leaflet.TileLayer,
        'v-marker': Vue2Leaflet.Marker
    },
    data () {
        console.log('getData');
        return {
            zoom:13,
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            marker: L.latLng(47.413220, -1.219482),
        }
    }
}
</script>

<style>
.map-container {
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
}
</style>
