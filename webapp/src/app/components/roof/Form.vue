<template>
    <form class="container">
        <div class="form-group row mt-3">
            <label class="col-2 text-right">nom</label>
            <input class="col-10 pt-0 form-control" type="text"
                v-model="roof.name" >
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">probabilité</label>
            <select class="col-10 pt-0 form-control"
                v-model="roof.probability">
                <option v-for="probability in infos.probabilities" :key="index" >
                    {{ probability }}
                </option>
            </select>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">structure initiatrice</label>
            <select class="col-10 pt-0 form-control"
                v-model="roof.structure_id">
                <option v-for="structure in infos.structures" :key="structure.id"
                    :value="structure.id" >
                    {{ structure.name }}
                </option>
            </select>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">surface</label>
            <input class="col-10 pt-0 form-control" type="text" v-model="roof.square_area">
        </div>

        <div class="form-row">
            <label class="col-2 text-right">puissance</label>
            <div class="col-10 pt-0 row mb-2">
                <label class="col-2 text-right">min</label>
                <input type="text" class="col-1 pt-0 form-control" v-model="roof.power_min">

                <label class="col-2 text-right">max</label>
                <input type="text" class="col-1 pt-0 form-control" v-model="roof.power_max">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">Catégorie de tarif</label>
            <select class="col-10 pt-0 form-control"
                v-model="roof.purchase_category_id">
                <option v-for="pc in infos.purchase_categories" :key="pc.id"
                    :value="pc.id" >
                    {{ pc.name }}
                </option>
            </select>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">type</label>
            <select class="col-10 pt-0 form-control"
                v-model="roof.type_id">
                <option v-for="type in infos.types" :key="type.id"
                    :value="type.id" >
                    {{ type.name }}
                </option>
            </select>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">toiture</label>
            <select class="col-10 pt-0 form-control"
                v-model="roof.tilt_id">
                <option v-for="tilt in infos.tilts" :key="tilt.id"
                    :value="tilt.id" >
                    {{ tilt.name }}
                </option>
            </select>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">orientation sud</label>
            <select class="col-10 pt-0 form-control"
                v-model="roof.south_orientation_id">
                <option v-for="so in infos.south_orientations" :key="so.id"
                    :value="so.id" >
                    {{ so.name }}
                </option>
            </select>
        </div>


        <div class="form-check offset-2 pm-2">
            <label class="form-check-label col-2">
                <input class="form-check-input" type="checkbox"
                    value="1" v-model="roof.erp">
                ERP
            </label>
            <label class="form-check-label col-2">
                <input class="form-check-input" type="checkbox"
                    value="1" v-model="roof.perimeter_abf">
                périmètre ABF
            </label>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">taille du bâtiment</label>
            <input class="col-10 pt-0 form-control" type="text" v-model="roof.building_size " />
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">position onduleur</label>
            <input class="col-10 pt-0 form-control" type="text" v-model="roof.inverter_location " />
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">distance onduleur</label>
            <input class="col-10 pt-0 form-control" type="text" v-model="roof.inverter_distance " />
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">remarques</label>
            <textarea class="col-10 pt-0 form-control" type="text" v-model="roof.remarks"></textarea>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right" >adresse</label>
            <input type="text" class="col-10 form-control" v-model="roof.street" >
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">ville</label>
            <div class="col-10 pt-0 row">
                <input type="text" class="pt-0 form-control col-3" v-model="roof.city" >
                <!-- <label class="col-2">département</label> -->
                <!-- <select class="form-control col-2" -->
                    <!-- v-model="roof.department_id"> -->
                    <!-- <option v-for="dep in infos.departments" :key="dep.id" -->
                        <!-- :value="dep.id" > -->
                        <!-- {{ dep.zip }}. {{ dep.name }} -->
                    <!-- </option> -->
                <!-- </select> -->

                <label class="col-2">code postal</label>
                <input type="text" class="pt-0 col-1 form-control" v-model="roof.zip">
            </div>
        </div>

        <div class="row clearfix">
            <v-map class="offset-2 col-5 map-preview" :zoom="zoom"
                v-on:l-moveend="updateGeo"
                :center="center">
                <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
                <v-marker :lat-lng="[roof.latitude, roof.longitude]" >
                </v-marker>
            </v-map>
        </div>

        <div class="row">
            <div class="col-6">
                <button type="button" class="btn btn-link"
                    v-on:click="backToMap()">retour</button>
            </div>
            <div class="col-6 text-right">
                <button type="button" class="btn btn-primary"
                    v-on:click="save()">sauvegarder</button>
            </div>
        </div>
    </form>
</template>

<script>
import Vue2Leaflet from 'vue2-leaflet';

export default {
    props: {
        infos: {
            type:Object,
            default: {
                types: null,
                probabilities: null,
                structures: null,
                purchase_categories: null,
                tilts: null,
                south_orientations: null,
                // departments: null
            },
        },
        roof: {
            type: Object,
            default:{
                id: 0,
                name: '',
                probability: '',
                square_area: 0,
                power_max: 0,
                power_min: 0,
                erp: false,
                building_size: 0,
                perimeter_abf: false,
                remarks: '',
                inverter_location: '',
                inverter_distance: 0,
                street: '',
                zip: '',
                city: '',
                latitude: 0,
                longitude: 0,
                // relations
                owner_id: 0,
                structure_id: 0,
                south_orientation_id: 0,
                purchase_category_id: 0,
                type_id: 0,
                tilt_id: 0,
                department_id: 0

            }
        }
    },
    components: {
        'v-map': Vue2Leaflet.Map,
        'v-tilelayer': Vue2Leaflet.TileLayer,
        'v-marker': Vue2Leaflet.Marker,
    },
    methods: {
        updateGeo: function(e) {
            let center = e.target.getCenter();
            this.roof.latitude = center.lat;
            this.roof.longitude = center.lng;

        },
        backToMap:  function() {
            this.$router.push({ name: 'map' });
        },
        save: function() {
            let method = 'post',
                url = process.env.API_URL + 'roofs/';

            if (this.roof.id) {
                method = 'put';
                url += this.roof.id;
            }
            this.$http[method](url, this.roof).then(
                response => {
                    if (this.roof.id == false) {
                        this.$router.replace({
                            name:'roof',
                            params: { roofId: response.body.id }
                        });
                    }
                    this.roof = response.body;

                    this.$notify({
                        type:'success',
                        title: 'sauvegarde réussie',
                        text: 'well done jack'
                    });

                }
            );
        },
        close: function() {
            this.$emit('close');
        },
        loadRoof: function(roofId) {
            this.$http.get(
                process.env.API_URL + 'roofs/' + roofId
            ).then(
                response => {
                    this.roof = response.body;
                    this.center = [this.roof.latitude, this.roof.longitude];
                }
            );
        },
        loadExtrasInfos: function(name) {
            let url = process.env.API_URL;
            if (['structures', 'departments'].indexOf(name) == -1) {
                url += 'roof/';
            }
            this.$http.get(
                url + name
            ).then(response => {
                this.infos[name] = response.body
            });
        }
    },
    mounted() {
        for (let key in this.infos) {
            this.loadExtrasInfos(key);
        }

        if (this.$route.params.roofId != false) {
            this.loadRoof(this.$route.params.roofId);
        }
    },
    data() {
        return {
            zoom:13,
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        }
    }

}
</script>

<style>
@import "../../../../node_modules/leaflet/dist/leaflet.css";

.leaflet-fake-icon-image-2x {
  background-image: url(../../../../node_modules/leaflet/dist/images/marker-icon.png);
}
.leaflet-fake-icon-shadow {
  background-image: url(../../../../node_modules/leaflet/dist/images/marker-shadow.png);
}

.map-preview {
    width:200px;
    height:200px;
}
</style>
