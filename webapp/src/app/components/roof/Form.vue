<template>
    <form>
        <div class="form-group row col-12 mt-3">
            <label class="col-2 text-right">nom</label>
            <div class="col-10">
                <input class="pt-0 form-control" type="text"
                    v-model="roof.name" >
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">probabilité</label>
            <div class="col-10">
                <select class="form-control" v-model="roof.probability">
                    <option v-for="probability in infos.probabilities" :key="index" >
                        {{ probability }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">structure initiatrice</label>
            <div class="col-10 pt-0">
                <select class="form-control"
                    v-model="roof.structure_id">
                    <option v-for="structure in infos.structures" :key="structure.id"
                        :value="structure.id" >
                        {{ structure.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">surface</label>
            <div class="col-10 pt-0">
                <input class="form-control" type="text" v-model="roof.square_area">
            </div>
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

        <div class="form-group row col-12">
            <label class="col-2 text-right">Catégorie de tarif</label>
            <div class="col-10 pt-0">
                <select class="form-control" v-model="roof.purchase_category_id">
                    <option v-for="pc in infos.purchase_categories" :key="pc.id"
                        :value="pc.id" >
                        {{ pc.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">type</label>
            <div class="col-10 pt-0">
                <select class="form-control" v-model="roof.type_id">
                    <option v-for="type in infos.types" :key="type.id"
                        :value="type.id" >
                        {{ type.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">toiture</label>
            <div class="col-10 pt-0">
                <select class="form-control" v-model="roof.tilt_id">
                    <option v-for="tilt in infos.tilts" :key="tilt.id"
                        :value="tilt.id" >
                        {{ tilt.name }}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">orientation sud</label>
            <div class="col-10 pt-0">
                <select class="form-control"
                    v-model="roof.south_orientation_id">
                    <option v-for="so in infos.south_orientations" :key="so.id"
                        :value="so.id" >
                        {{ so.name }}
                    </option>
                </select>
            </div>
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

        <div class="form-group row col-12">
            <label class="col-2 text-right">taille du bâtiment</label>
            <div class="col-10 pt-0">
                <input class="form-control" type="text" v-model="roof.building_size " />
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">position onduleur</label>
            <div class="col-10 pt-0">
                <input class="col-10 pt-0 form-control" type="text" v-model="roof.inverter_location " />
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">distance onduleur</label>
            <div class="col-10 pt-0">
                <input class="form-control" type="text" v-model="roof.inverter_distance " />
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-2 text-right">remarques</label>
            <div class="col-10 pt-0">
                <textarea class="form-control" type="text" v-model="roof.remarks"></textarea>
            </div>
        </div>

        <fieldset>
            <legend class="offset-2 col-10">
                <small>propriétaire</small>
            </legend>

            <div v-if="editingOwner">
                <div class="form-group row col-12">
                    <label class="col-2 text-right">prénom</label>
                    <div class="col-10 pt-0">
                        <input class="form-control" type="text"
                            v-model="owner.contact.first_name" />
                    </div>
                </div>

                <div class="form-group row col-12">
                    <label class="col-2 text-right">nom</label>
                    <div class="col-10 pt-0">
                        <input class="form-control" type="text"
                            v-model="owner.contact.last_name" />
                    </div>
                </div>
                <div class="form-group row col-12">
                    <label class="col-2 text-right">type</label>
                    <div class="col-10 pt-0">
                        <select class="form-control"
                                v-model="owner.type_id">
                            <option v-for="so in infos.structure_types" :key="so.id"
                                    :value="so.id" >
                                    {{ so.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row col-12">
                    <label class="col-2 text-right">téléphone</label>
                    <div class="col-10 pt-0">
                        <input class="form-control" type="text"
                            v-model="owner.contact.phone" />
                    </div>
                </div>

                <div class="form-group row col-12">
                    <label class="col-2 text-right">email</label>
                    <div class="col-10 pt-0">
                        <input class="form-control" type="email"
                            v-model="owner.contact.email" />
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="form-group row col-12">
                    <!-- TODO make a card of contact infos -->
                    <label class="col-2 text-right">nom</label>
                    <div class="col-7 pt-0">
                        <input class="form-control-plaintext" type="text"
                            v-model="roof.owner.name" />
                    </div>
                    <button type="button" class="btn btn-secondary"
                        v-on:click="loadOwner()">edit</button>
                </div>
            </div>

        </fieldset>

        <fieldset>
            <legend class="offset-2 col-10">
                <small>localisation</small>
            </legend>

            <div class="form-group row col-12">
                <label class="col-2 text-right" >adresse</label>
                <div class="col-10 ">
                    <input type="text" class="form-control" v-model="roof.street" >
                </div>
            </div>

            <div class="form-group row col-12">
                <label class="col-2 text-right">ville</label>
                <div class="col-10 pt-0 row">
                    <input type="text" class="form-control" v-model="roof.city" >
                </div>
                    <!-- <label class="col-2">département</label> -->
                    <!-- <select class="form-control col-2" -->
                        <!-- v-model="roof.department_id"> -->
                        <!-- <option v-for="dep in infos.departments" :key="dep.id" -->
                            <!-- :value="dep.id" > -->
                            <!-- {{ dep.zip }}. {{ dep.name }} -->
                        <!-- </option> -->
                    <!-- </select> -->
             </div>
             <div class="form-group row col-12">
                    <label class="col-2 text-right">code postal</label>
                    <div class="pt-0 col-10">
                        <input type="text" class="form-control" v-model="roof.zip">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <v-map class="offset-2 col-5 map-preview" :zoom="zoom"
                    v-on:l-moveend="updateGeo" :center="center">
                    <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
                    <v-marker :lat-lng="[roof.latitude, roof.longitude]" >
                    </v-marker>
                    <div class="extra-content">
                        <button type="button" class="btn btn-primary d-inline-block btn-sm"
                            v-on:click="updateCenter()">centrer le marqueur</button>
                    </div>
                </v-map>
            </div>

        </fieldset>

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
                structure_types: null,
                // departments: null
            },
        },
    },
    components: {
        'v-map': Vue2Leaflet.Map,
        'v-tilelayer': Vue2Leaflet.TileLayer,
        'v-marker': Vue2Leaflet.Marker,
    },
    methods: {
        updateGeo: function(e) {
            let center = e.target.getCenter();
            if (center == null) {
                return true;
            }
            // this.roof.latitude = center.lat;
            // this.roof.longitude = center.lng;

            this.$cookie.set('map-center', [center.lat, center.lng], 30);
        },
        updateCenter: function() {
            let center = this.$cookie.get('map-center').split(',');
            this.roof.latitude = center[0];
            this.roof.longitude = center[1];
        },
        backToMap:  function() {
            this.$router.push({ name: 'map' });
        },
        loadOwner: function() {
            this.$http.get(process.env.API_URL + 'structures/' + this.roof.owner_id).then(
                response => {
                    this.owner = response.body;
                    this.editingOwner = true;
                }
            );
        },
        save: function() {
            let method = 'post',
                url = process.env.API_URL + 'roofs/';

            if (this.roof.id) {
                method = 'put';
                url += this.roof.id;
            }
            let params = this.roof;
            if (this.editingOwner) {
                params.owner = this.owner;
            }
            this.$http[method]( url, params).then(
                response => {
                    this.editingOwner = false;
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
        loadRoof: function(roofId) {
            this.$http.get(
                process.env.API_URL + 'roofs/' + roofId
            ).then(
                response => {
                    this.roof = response.body;
                    this.center = [this.roof.latitude, this.roof.longitude];
                    this.editingOwner = (this.roof.owner_id === 0);
                }
            );
        },
        loadExtrasInfos: function(name) {
            let url = process.env.API_URL;
            if (['structures', 'structure_types', 'departments'].indexOf(name) == -1) {
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
        } else {
            this.editingOwner = true;
        }
    },
    data() {
        let center = [ process.env.COORD.LATITUDE, process.env.COORD.LONGITUDE ];
        if (this.$cookie.get('map-center')) {
            let cookieCenter = this.$cookie.get('map-center').split(',');
            center = cookieCenter;
        }
        return {
            zoom:18,
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            center: center,
            editingOwner: false,
            owner: {
                id: 0,
                name: '',
                contact_id: 0,
                contact: {
                    // id: 0,
                    // first_name: '',
                    // last_name: '',
                    // phone: '',
                    // email: ''
                }
            },
            roof: {
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
                latitude: center[0],
                longitude: center[1],
                // relations
                owner_id: 0,
                owner: { name:'' },
                structure_id: 0,
                south_orientation_id: 0,
                purchase_category_id: 0,
                type_id: 0,
                tilt_id: 0,
                department_id: 0
            }
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

.extra-content {
    z-index:500;
    position:absolute;
    right:0;
}
</style>
