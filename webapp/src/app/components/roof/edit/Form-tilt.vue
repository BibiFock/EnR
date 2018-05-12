<template>
    <div class="row col-12">
        <div class="form-group col-md-6 col-12 mb-md-0">
            <div class="form-group row col-12">
                <label class="col-md-4 ml-2 text-md-right">name</label>
                <div class="col-md-7 pt-0">
                    <input class="form-control" type="text"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('name')}"
                        v-model="tilt.name" />
                    <div class="invalid-feedback"
                        v-if="errors.hasOwnProperty('name')"> {{ errors['name'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-12">
                <label class="col-md-4 ml-2 text-md-right">type</label>
                <div class="col-md-7 pt-0">
                    <select class="form-control"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('type_id')}"
                            v-model="tilt.type_id">
                        <option v-for="type in types" :key="type.id"
                                :value="type.id" >
                                {{ type.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('type_id')"> {{ errors['type_id'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">pente de toit</label>
                <div class="col-md-7 pt-0">
                    <div class="input-group">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('slope')}"
                            v-model="tilt.slope" />
                        <div class="input-group-prepend">
                            <div class="input-group-text">°</div>
                        </div>
                    </div>

                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('slope')"> {{ errors['slope'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">surface au sol du toit</label>
                <div class="col-md-7 pt-0">
                    <div class="input-group">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('ground_square_area')}"
                            v-model="tilt.ground_square_area" />
                        <div class="input-group-prepend">
                            <div class="input-group-text">m²</div>
                        </div>
                    </div>
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('ground_square_area')"> {{ errors['ground_square_area'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">taux d'occupation</label>
                <div class="col-md-7 pt-0">
                    <div class="input-group">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('occupancy_rate')}"
                            v-model="tilt.occupancy_rate" />
                        <div class="input-group-prepend">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('occupancy_rate')"> {{ errors['occupancy_rate'].join(',') }} </div>
                </div>
            </div>

            <div class="form-row col-md-12">
                <div class="form-group col-md-4 ml-2">
                    <label class="text-md-right">orientation sud</label>
                </div>
                <div class="form-group col-11 col-md-6 col-lg-3 pt-0">
                    <div class="input-group">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('south_orientation')}"
                            v-model="tilt.south_orientation" />
                        <div class="input-group-prepend">
                            <div class="input-group-text">°</div>
                        </div>
                    </div>

                </div>
                <div class="form-group col-1 col-md-1 col-lg-3">
                    <ToolOrientationFinder
                        v-on:updateOrientation="updateOrientation"
                        :center="position"
                        :orientation="tilt.south_orientation"></ToolOrientationFinder>
                </div>

                <div class="invalid-feedback"
                        v-if="errors.hasOwnProperty('south_orientation')"> {{ errors['south_orientation'].join(',') }} </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">emplacement onduleur</label>
                <div class="col-md-7 pt-0">
                    <input class="form-control" type="text"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('inverter_location')}"
                        v-model="tilt.inverter_location" />
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('inverter_location')"> {{ errors['inverter_location'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">distance onduleur</label>
                <div class="col-md-7 pt-0">
                    <div class="input-group">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('inverter_distance')}"
                            v-model="tilt.inverter_distance" />
                        <div class="input-group-prepend">
                            <div class="input-group-text">m</div>
                        </div>
                    </div>

                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('inverter_distance')"> {{ errors['inverter_distance'].join(',') }} </div>
                </div>
            </div>
        </div>
        <div class="map-edit col-md-6 col-12">
            <gmap-map
                :center="center"
                :zoom="zoom"
                :map-type-id="mapType"
                :options="options"
                @maptypeid_changed="updateMapType"
                @center_changed="updateGeo"
                class="col-md-12 map-preview" >
                <gmap-marker :position.sync="position" ></gmap-marker>

                <div class="extra-content">
                    <button type="button" class="btn btn-primary d-inline-block btn-sm"
                        v-on:click="updateCenter()">
                        <i class="fa fa-bullseye"></i>
                        <span>centrer le marqueur</span>
                    </button>
                </div>
            </gmap-map>
        </div>

    </div>
</template>

<script>
import ToolOrientationFinder from './tools/OrientationFinder.vue';

export default {
    components: { ToolOrientationFinder },
    props: {
        errors: { type:Object, default: {} },
        tilt: {
            type: Object,
            default: {
                id: 0,
                name: '',
                latitude: null,
                longitude: null,
                slope: 0,
                ground_square_area: 0,
                occupancy_rate: 0,
                south_orientation: 0,
                // relations
                type_id: 0,
            }
        },
        types: { default:[] },
    },
    watch: {
        tilt : function (val) {
            if (this.tilt.latitude === null) {
                this.tilt.latitude = this.center.lat;
                this.tilt.longitude = this.center.lng;
            } else {
                this.tilt.latitude = parseFloat(this.tilt.latitude);
                this.tilt.longitude = parseFloat(this.tilt.longitude);
            }

            ['position', 'center'].map( (field) => {
                this[field].lat = this.tilt.latitude;
                this[field].lng = this.tilt.longitude;
            });
        }
    },
    methods: {
        updateOrientation: function(southOrientation) {
            this.tilt.south_orientation = southOrientation;
        },
        // <-- cookies
        updateMapType: function(mapType) {
            this.$cookie.set('map-type', mapType, 30);
        },
        updateGeo: function(center) {
            this.$cookie.set('map-center', [center.lat(), center.lng()], 30);
        },
        // cookies -->
        updateCenter: function() {
            let center = this.$cookie.get('map-center').split(',')
                .map(el => parseFloat(el));
            this.tilt.latitude = center[0];
            this.tilt.longitude = center[1];
            this.position.lat = center[0];
            this.position.lng = center[1];
        },
        toggleOrientationFinder: function(tilt) {
            // open modal gmaps
            // code orientation finder
            // god help me please
        }
    },
    data() {
        let coord = [ process.env.COORD.LATITUDE, process.env.COORD.LONGITUDE ];
        if (this.$cookie.get('map-center')) {
            let cookieCenter = this.$cookie.get('map-center').split(',');
            coord = cookieCenter;
        }
        coord = coord.map(coord => parseFloat(coord));
        let center = { lat: coord[0], lng: coord[1] };

        let mapType = process.env.MAP.TYPE;
        if (this.$cookie.get('map-type')) {
            mapType = this.$cookie.get('map-type');
        }
        return {
            zoom:process.env.MAP.ZOOM_EDIT,
            mapType:mapType,
            center: center,
            position: center,
            options: {
                tilt:0
            }
        }
    }
}
</script>

<style>
.map-edit .map-preview {
    min-height:300px;
    height:100%;
}


.map-edit .map-preview .vue-map-hidden {
    display:block;
}

.map-edit .extra-content {
    z-index:500;
    position:absolute;
    left:0;
    bottom:0;
}
</style>
