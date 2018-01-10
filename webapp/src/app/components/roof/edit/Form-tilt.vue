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
                    <input class="form-control" type="text"
                                                v-bind:class="{'is-invalid': errors.hasOwnProperty('slope')}"
                                                v-model="tilt.slope" />
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('slope')"> {{ errors['slope'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">surface au sol du toit</label>
                <div class="col-md-7 pt-0">
                    <input class="form-control" type="text"
                                                v-bind:class="{'is-invalid': errors.hasOwnProperty('ground_square_area')}"
                                                v-model="tilt.ground_square_area" />
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('ground_square_area')"> {{ errors['ground_square_area'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">taux d'occupation</label>
                <div class="col-md-7 pt-0">
                    <input class="form-control" type="text"
                                                v-bind:class="{'is-invalid': errors.hasOwnProperty('occupancy_rate')}"
                                                v-model="tilt.occupancy_rate" />
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('occupancy_rate')"> {{ errors['occupancy_rate'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">orientation sud</label>
                <div class="col-md-7 pt-0">
                    <input class="form-control" type="text"
                                                v-bind:class="{'is-invalid': errors.hasOwnProperty('south_orientation')}"
                                                v-model="tilt.south_orientation" />
                    <div class="invalid-feedback"
                         v-if="errors.hasOwnProperty('south_orientation')"> {{ errors['south_orientation'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-4 ml-2 text-md-right">position onduleur</label>
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
                    <input class="form-control" type="text"
                                                v-bind:class="{'is-invalid': errors.hasOwnProperty('inverter_distance')}"
                                                v-model="tilt.inverter_distance" />
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
                @maptypeid_changed="updateMapType"
                @center_changed="updateGeo"
                class="col-md-12 map-preview" >
                <gmap-marker :position.sync="position" ></gmap-marker>

                <div class="extra-content">
                    <button type="button" class="btn btn-primary d-inline-block btn-sm"
                        v-on:click="updateCenter()">centrer le marqueur</button>
                </div>
            </gmap-map>
        </div>
    </div>
</template>

<script>
export default {
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
    },
    data() {
        let coord = [ process.env.COORD.LATITUDE, process.env.COORD.LONGITUDE ];
        if (this.$cookie.get('map-center')) {
            let cookieCenter = this.$cookie.get('map-center').split(',');
            coord = cookieCenter;
        }
        coord = coord.map(coord => parseFloat(coord));
        let center = { lat: coord[0], lng: coord[1] };

        let mapType = 'hybrid';
        if (this.$cookie.get('map-type')) {
            mapType = this.$cookie.get('map-type');
        }
        return {
            zoom:18,
            mapType:mapType,
            center: center,
            position: center,
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
