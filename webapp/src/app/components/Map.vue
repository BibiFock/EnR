<template>
    <div class="map-container">
        <!--v-map class="map-container" :zoom="zoom" :center="[this.lat, this.lng]"
            v-on:l-moveend="updateGeo">
            <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
            <v-marker v-for="roof in roofs" :key="roof.id"
                :lat-lng="[roof.latitude, roof.longitude]"
                v-on:l-click="showDetail(roof)" >
                <v-tooltip :content="getToolTip(roof)"></v-tooltip>
            </v-marker>
        </v-map-->
        <gmap-map
            :center="center"
            :zoom="zoom"
            map-type-id="hybrid"
            @center_changed="updateCenter"
            class="map-container" >
            <gmap-info-window
                :options="infoOptions"
                :position="infoWindowPos"
                :opened="infoWinOpen"
                @closeclick="infoWinOpen=false">
                {{infoContent}}
            </gmap-info-window>
            <gmap-marker
                v-for="(roof, index) in roofs"
                :key="index"
                :position="roof.position"
                :clickable="true"
                :draggable="false"
                @mouseover="overOfRoof(roof)"
                @mouseout="outOfRoof(roof)"
                @click="showDetail(roof)" ></gmap-marker>
        </gmap-map>
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
            <!--GmapAutocomplete @place_changed="setPlace">
            </GmapAutocomplete-->

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

            <b-dropdown variant="link" size="lg" right no-caret>
                <template slot="button-content">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </template>
                <b-dropdown-item v-on:click="exportData()">exporter</b-dropdown-item>
            </b-dropdown>

        </div>

    </div>
</template>

<script>
import Autocomplete from 'vue2-autocomplete-js';

export default {
    props: {
        roofs: { type: Array, default: [] }
    },
    components: {
        Autocomplete
    },
    methods: {
        exportData: function () {
            this.$cookie.set('token', localStorage.getItem('id_token'), 30);
            window.open(
                process.env.API_URL + 'export?token=' + localStorage.getItem('id_token'),
                '_blank'
            );
        },
        updateGeo: function(e) {
            let center = e.target.getCenter();
            if (center == null) {
                return true;
            }

            this.$cookie.set('map-center', [center.lat, center.lng], 30);
        },
        handleSelect: function(obj) {
            this.center.lat = parseFloat(obj.lat);
            this.center.lng = parseFloat(obj.lon);
        },
        overOfRoof: function(roof) {
            this.infoWinOpen = true;
            this.infoWindowPos = roof.position;
            this.infoContent = this.getToolTip(roof);
        },
        outOfRoof: function(roof) {
            if (!this.infoWinOpen || this.infoWindowPos != roof.position) {
                return;
            }
            this.infoWinOpen = false;
        },
        getToolTip: function(roof) {
            return roof.name + ', '
                + (roof.structure ? roof.structure.name : '-') + ', '
                + roof.power_max + ' kWc';

        },
        addRoof: function() {
            this.$router.push({
                name:'roof-edit',
                params: { roofId: 0 }
            });
        },
        showDetail: function(roof) {
            this.$router.push({
                name: 'roof-edit',
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
                    this.roofs = response.body.map(roof => {
                        roof.position = {
                            lat: parseFloat(roof.latitude),
                            lng: parseFloat(roof.longitude)
                        };
                        return roof;
                    });
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
            center: {
                lat: parseFloat(center[0]),
                lng: parseFloat(center[1])
            },
            urlAutocomplete: process.env.API_URL + 'map/search',
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
            infoWinOpen: false,
            infoWindowPos: null,
            infoContent: ''
        }
    }
}
</script>

<style>
.map-container {
    position:absolute !important;
    top:0;
    left:0;
    right:0;
    bottom:0;
}

.extra-content {
    z-index:500;
    position:absolute;
    right:30px;
}

.close {
    opacity:1;
}

.autocomplete-list {
    background-color:white;
    position: absolute;
}
.autocomplete-list ul {
    list-style-type: none;
    padding: .5em 1em;
}
</style>
