<template>
    <div class="map-container">
        <!-- <h2>Simple map</h2> -->
        <!-- Marker is placed at {{ marker.lat }}, {{ marker.lng }} -->
        <!-- </br> -->
        <v-map class="map-container" :zoom="zoom" :center="[this.lat, this.lng]">
            <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
            <v-marker v-for="marker in markers"  :key="marker.id"
                :lat-lng="marker.position"
                v-on:l-click="showDetail(marker)" >
                <v-popup :content="marker.tooltip"></v-popup>
            </v-marker>
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
        'v-marker': Vue2Leaflet.Marker,
        'v-popup': Vue2Leaflet.Popup
    },
    methods: {
        showDetail: function(marker) {
            console.log('this is ok');
            console.log(marker);
            // this.$emit('test', 'test');
            this.$emit('showDetail', marker);
        }
    },
    data () {
        console.log('getData');
        return {
            zoom:13,
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            markers:[
                { position: { lat:46.423221, lng:-1.333482}, tooltip: 'marker 1'},
                { position: { lat:46.413220, lng:-1.219482}, tooltip: 'marker 2'},
                { position: { lat:46.457809, lng:-1.571045}, tooltip: 'marker 3'},
                { position: { lat: 47.413220, lng:-1.219482}, tooltip: 'this is a fucking test'}
            ],
            // marker: L.latLng(47.413220, -1.219482),
            // tooltip: 'testation bla bla<br/> bla'
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
