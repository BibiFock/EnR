<template>
    <div class="map-container">
        <gmap-map
            :center="center"
            :zoom="zoom"
            @maptypeid_changed="typeChanged"
            :map-type-id="type"
            @center_changed="centerChanged"
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
                v-if="roof.position"
                :key="index"
                :position="roof.position"
                :clickable="true"
                :draggable="false"
                @mouseover="overOfRoof(roof)"
                @mouseout="outOfRoof(roof)"
                @click="showDetail(roof)" ></gmap-marker>
        </gmap-map>
        <div class="extra-content">
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

import { mapState, mapActions, mapGetters, mapMutations } from 'vuex';

export default {
    components: {
        Autocomplete
    },
    computed: {
        ...mapState('roofs', [ 'roofs' ]),
        ...mapGetters('auth', [ 'token' ]),
        ...mapState('map', ['center', 'type', 'zoom'])
    },
    methods: {
        ...mapActions('roofs', [ 'loadRoofs' ]),
        ...mapMutations('map', [ 'typeChanged', 'centerChanged' ]),
        exportData: function () {
            this.$cookie.set('token', this.token, 30);
            window.open(
                process.env.API_URL + 'export?token=' + this.token,
                '_blank'
            );
        },
        handleSelect: function(obj) {
            this.centerChanged({
                lat: () => parseFloat(obj.lat),
                lng: () => parseFloat(obj.lng)
            });
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
    },
    mounted() {
        this.$router.push({
            name: 'map'
        });
        setTimeout(() => this.loadRoofs(), 10);
    },
    data () {
        return {
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
