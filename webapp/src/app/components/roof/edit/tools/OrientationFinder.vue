<template>
    <div>
        <b-btn v-b-modal.modOrFinder>
            <i class="fa fa-location-arrow"></i>
            <span class="d-none d-lg-inline-block">trouver l'orientation</span>
        </b-btn>

        <!-- modals -->
        <b-modal
            id="modOrFinder"
            :title="'orientation par rapport au sud: ' + effOrientation + ' °'"
            @ok="onOk"
            @shown="onShow">
            <div class="map-edit">
                <gmap-map
                    ref="map"
                    :center="center"
                    :zoom="zoom"
                    :map-type-id="mapType"
                    class="col-md-12 map-preview" >

                    <gmap-marker
                        ref="marker"
                        :position="marker"
                        :clickable="false"
                        :draggable="false"
                        @drag="updateOrientation"></gmap-marker>

                    <gmap-marker
                        ref="orientation"
                        :position="markerOrientation"
                        :clickable="false"
                        :draggable="true"
                        label="S"
                        @drag="updateOrientation"></gmap-marker>
                </gmap-map>
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: {
        orientation: { type: Number, default: 0 }
    },
    methods: {
        onOk: function() {
            this.$emit('updateOrientation', this.effOrientation);
        },
        onShow: function() {
            this.effOrientation = this.orientation;
            let marker = this.$refs.marker.$markerObject;
            let mOrientation = this.$refs.orientation.$markerObject;
            let map = this.$refs.map.$mapObject;

            // on convertit en orientation par rapport au nord
            let northOrientation = this.orientationSouthToNorth(this.effOrientation);

            // ensuite on place le point sur un cercle à partir du centre
            let pointOnCircle = google.maps.geometry.spherical.computeOffset(
                marker.position,
                70,
                northOrientation
            );

            mOrientation.setPosition( new google.maps.LatLng(
                pointOnCircle.lat(), pointOnCircle.lng()
            ));

            if (!this.line) {
                this.line = new google.maps.Polyline({
                    path: [ marker.position, mOrientation.position],
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                    map: this.$refs.map.$mapObject,
                    icons: [{
                        icon: {
                            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                        },
                        offset: '100%'
                    }]
                });
            }

            let MVCArrayBinder = function(mvcArray) {
                //credit to https://stackoverflow.com/a/26663570/2381899
                this.array_ = mvcArray;
            }
            MVCArrayBinder.prototype = new google.maps.MVCObject();
            MVCArrayBinder.prototype.get = function (key) {
                if (!isNaN(parseInt(key))) {
                    return this.array_.getAt(parseInt(key));
                } else {
                    this.array_.get(key);
                }
            };

            MVCArrayBinder.prototype.set = function (key, val) {
                if (!isNaN(parseInt(key))) {
                    this.array_.setAt(parseInt(key), val);
                } else {
                    this.array_.set(key, val);
                }
            }

            this.line.binder = new MVCArrayBinder(this.line.getPath());
            marker.bindTo('position', this.line.binder, '0'); //this makes the line bind to point 1
            mOrientation.bindTo('position', this.line.binder, '1'); //and point 2

            this.updateOrientation();
        },
        updateOrientation: function() {
            let marker = this.$refs.marker.$markerObject;

            let cHead = google.maps.geometry.spherical.computeHeading(
                this.line.binder.get(0),
                this.line.binder.get(1),
            );
            // console.log(
            //     "Bearing is " + cHead.toFixed(1) + " or "
            //     + (cHead + 90).toFixed(1) + " / " + this.orientationNorthToSouth(cHead)
            //     + " if E-W is 0. Better try daniel street..."
            // );

            this.effOrientation = this.orientationNorthToSouth(cHead);
        },
        orientationNorthToSouth: function (cHead) {
            let orientation = (cHead < 0 ? cHead + 180 : cHead - 180);

            return Math.round(orientation * 100) / 100;
        },
        orientationSouthToNorth: function (cHead) {
            let orientation = (cHead < 0 ? cHead + 180 : cHead - 180);

            return Math.round(orientation * 100) / 100;
        }
    },
    data() {
        let coord = [ process.env.COORD.LATITUDE, process.env.COORD.LONGITUDE ];
        if (this.$cookie.get('map-center')) {
            let cookieCenter = this.$cookie.get('map-center').split(',');
            coord = cookieCenter;
        }
        coord = coord.map(coord => parseFloat(coord));

        let mapType = process.env.MAP.TYPE;
        if (this.$cookie.get('map-type')) {
            mapType = this.$cookie.get('map-type');
        }
        return {
            zoom: process.env.MAP.ZOOM_EDIT,
            mapType: mapType,
            effOrientation: null,
            center: {
                lat: parseFloat(coord[0]),
                lng: parseFloat(coord[1])
            },
            marker: {
                lat: parseFloat(coord[0]),
                lng: parseFloat(coord[1])
            },
            markerOrientation: {
                lat: parseFloat(coord[0]),
                lng: parseFloat(coord[1])
            }
        }
    }
}
</script>
