<template>
    <form>
        <div class="form-group row col-12 mt-3">
            <label class="col-md-2 text-md-right">nom</label>
            <div class="col-md-10">
                <input class="pt-0 form-control" type="text"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('name')}" v-model="roof.name" >
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('name')"> {{ errors['name'].join(',') }} </div>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-md-2 text-md-right">probabilité</label>
            <div class="col-md-10">
                <select class="form-control"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('probability')}"
                    v-model="roof.probability">
                    <option v-for="probability in infos.probabilities" :key="index" >
                        {{ probability }}
                    </option>
                </select>
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('probability')"> {{ errors['probability'].join(',') }} </div>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-md-2 text-md-right">structure initiatrice</label>
            <div class="col-md-10 pt-0">
                <select class="form-control"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('structure_id')}"
                    v-model="roof.structure_id">
                    <option v-for="structure in infos.structures" :key="structure.id"
                        :value="structure.id" >
                        {{ structure.name }}
                    </option>
                </select>
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('structure_id')"> {{ errors['structure_id'].join(',') }} </div>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-md-2 text-md-right">surface</label>
            <div class="col-md-10 pt-0">
                <input class="form-control" type="text"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('square_area')}"
                    v-model="roof.square_area">
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('square_area')"> {{ errors['square_area'].join(',') }} </div>
            </div>
        </div>

        <div class="form-row col-12">
            <label class="col-md-2 col-12 text-md-right">puissance</label>
            <div class="col-md-10 col-12 pt-0 row mb-2">
                <div class="col-6">
                    <label class="col-2 text-md-right">min</label>
                    <input type="text" class="col-4 pt-0 form-control"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('power_min')}"
                        v-model="roof.power_min">
                    <div class="invalid-feedback"
                        v-if="errors.hasOwnProperty('power_min')"> {{ errors['power_min'].join(',') }} </div>
                </div>
                <div class="col-6">
                    <label class="col-2 text-md-right">max</label>
                    <input type="text" class="col-4 pt-0 form-control"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('power_max')}"
                        v-model="roof.power_max">
                    <div class="invalid-feedback"
                        v-if="errors.hasOwnProperty('power_max')"> {{ errors['power_max'].join(',') }} </div>
                </div>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-md-2 text-md-right">Catégorie de tarif</label>
            <div class="col-md-10 pt-0">
                <select class="form-control"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('purchase_category_id')}"
                    v-model="roof.purchase_category_id">
                    <option v-for="pc in infos.purchase_categories" :key="pc.id"
                        :value="pc.id" >
                        {{ pc.name }}
                    </option>
                </select>
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('purchase_category_id')"> {{ errors['purchase_category_id'].join(',') }} </div>
            </div>
        </div>

        <div class="form-check offset-2">
            <label class="form-check-label col-5">
                <input class="form-check-input" type="checkbox"
                    value="1" v-bind:class="{'is-invalid': errors.hasOwnProperty('erp')}"
                    v-model="roof.erp">
                ERP
            </label>
            <label class="form-check-label col-5">
                <input class="form-check-input" type="checkbox"
                    value="1" v-bind:class="{'is-invalid': errors.hasOwnProperty('perimeter_abf')}"
                    v-model="roof.perimeter_abf">
                périmètre ABF
            </label>
        </div>

        <div class="form-group row col-12">
            <label class="col-md-2 text-md-right">taille du bâtiment</label>
            <div class="col-md-10 pt-0">
                <input class="form-control" type="text"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('building_size')}"
                    v-model="roof.building_size " />
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('building_size')"> {{ errors['building_size'].join(',') }} </div>
            </div>
        </div>

        <div class="row mb-4">
            <fieldset class="form-group col-md-6 col-12 mb-md-0">
                <legend class="offset-4 col-md-7">
                    <small>toiture</small>
                </legend>

                <div class="form-group row col-12">
                    <label class="col-md-4 ml-2 text-md-right">type</label>
                    <div class="col-md-7 pt-0">
                        <select class="form-control"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('type_id')}"
                            v-model="roof.type_id">
                            <option v-for="type in infos.types" :key="type.id"
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
                            v-model="roof.slope" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('slope')"> {{ errors['slope'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-4 ml-2 text-md-right">surface au sol du toit</label>
                    <div class="col-md-7 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('ground_square_area')}"
                            v-model="roof.ground_square_area" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('ground_square_area')"> {{ errors['ground_square_area'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-4 ml-2 text-md-right">taux d'occupation</label>
                    <div class="col-md-7 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('occupancy_rate')}"
                            v-model="roof.occupancy_rate" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('occupancy_rate')"> {{ errors['occupancy_rate'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-4 ml-2 text-md-right">orientation sud</label>
                    <div class="col-md-7 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('south_orientation')}"
                            v-model="roof.south_orientation" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('south_orientation')"> {{ errors['south_orientation'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-4 ml-2 text-md-right">position onduleur</label>
                    <div class="col-md-7 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('inverter_location')}"
                            v-model="roof.inverter_location" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('inverter_location')"> {{ errors['inverter_location'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-4 ml-2 text-md-right">distance onduleur</label>
                    <div class="col-md-7 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('inverter_distance')}"
                            v-model="roof.inverter_distance" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('inverter_distance')"> {{ errors['inverter_distance'].join(',') }} </div>
                    </div>
                </div>
            </fieldset>
            <div class="map-edit col-md-6 mt-md-5 col-12">
                <gmap-map
                    :center="center"
                    :zoom="zoom"
                    :map-type-id="mapType"
                    @maptypeid_changed="updateMapType"
                    @center_changed="updateGeo"
                    class="col-md-12 map-preview" >
                    <gmap-marker :position.sync="roof.position" ></gmap-marker>

                    <div class="extra-content">
                        <button type="button" class="btn btn-primary d-inline-block btn-sm"
                            v-on:click="updateCenter()">centrer le marqueur</button>
                    </div>
                </gmap-map>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label class="col-md-2 text-md-right">remarques</label>
            <div class="col-md-10 pt-0">
                <textarea class="form-control" type="text"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('remarks')}"
                    v-model="roof.remarks"></textarea>
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('remarks')"> {{ errors['remarks'].join(',') }} </div>
            </div>
        </div>

        <fieldset class="form-group">
            <legend class="offset-2 col-md-10">
                <small>propriétaire</small>
            </legend>

            <div v-if="editingOwner">
                <div class="form-group row col-md-12">
                    <label class="col-md-2 text-md-right">prénom</label>
                    <div class="col-md-10 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('owner.name')}"
                            v-model="owner.contact.first_name" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('owner.name')"> {{ errors['owner.name'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-2 text-md-right">nom</label>
                    <div class="col-md-10 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('owner.name')}"
                            v-model="owner.contact.last_name" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('owner.name')"> {{ errors['owner.name'].join(',') }} </div>
                    </div>
                </div>
                <div class="form-group row col-md-12">
                    <label class="col-md-2 text-md-right">type</label>
                    <div class="col-md-10 pt-0">
                        <select class="form-control"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('owner.type_id')}"
                            v-model="owner.type_id">
                            <option v-for="so in infos.structure_types" :key="so.id"
                                    :value="so.id" >
                                    {{ so.name }}
                            </option>
                        </select>
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('owner.type_id')"> {{ errors['owner.type_id'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-2 text-md-right">téléphone</label>
                    <div class="col-md-10 pt-0">
                        <input class="form-control" type="text"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('owner.contact.phone')}"
                            v-model="owner.contact.phone" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('owner.contact.phone')"> {{ errors['owner.contact.phone'].join(',') }} </div>
                    </div>
                </div>

                <div class="form-group row col-md-12">
                    <label class="col-md-2 text-md-right">email</label>
                    <div class="col-md-10 pt-0">
                        <input class="form-control" type="email"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('owner.contact.email')}"
                            v-model="owner.contact.email" />
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('owner.contact.email')"> {{ errors['owner.contact.email'].join(',') }} </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="form-group row col-md-12">
                    <!-- TODO make a card of contact infos -->
                    <label class="col-md-2 text-md-right">nom</label>
                    <div class="col-md-7 pt-0">
                        <input class="form-control-plaintext" type="text"
                            v-model="roof.owner.name" />
                    </div>
                    <button type="button" class="btn btn-secondary"
                        v-on:click="loadOwner()">edit</button>
                </div>
            </div>

        </fieldset>

        <fieldset class="form-group">
            <legend class="offset-2 col-md-10">
                <small>localisation</small>
            </legend>

            <div class="form-group row col-md-12">
                <label class="col-md-2 text-md-right" >adresse</label>
                <div class="col-md-10 ">
                    <input type="text" class="form-control"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('street')}"
                        v-model="roof.street" >
                    <div class="invalid-feedback"
                        v-if="errors.hasOwnProperty('street')"> {{ errors['street'].join(',') }} </div>
                </div>
            </div>

            <div class="form-group row col-md-12">
                <label class="col-md-2 text-md-right">ville</label>
                <div class="col-md-10 pt-0">
                    <input type="text" class="form-control"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('city')}"
                        v-model="roof.city" >
                    <div class="invalid-feedback"
                        v-if="errors.hasOwnProperty('city')"> {{ errors['city'].join(',') }} </div>
                </div>
             </div>
             <div class="form-group row col-md-12">
                    <label class="col-md-2 text-md-right">code postal</label>
                    <div class="pt-0 col-md-10">
                        <input type="text" class="form-control"
                            v-bind:class="{'is-invalid': errors.hasOwnProperty('zip')}"
                            v-model="roof.zip">
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('zip')"> {{ errors['zip'].join(',') }} </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-link"
                    v-on:click="backToMap()">retour</button>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary"
                    v-on:click="save()">sauvegarder</button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        infos: {
            type:Object,
            default: {
                types: null,
                probabilities: null,
                structures: null,
                purchase_categories: null,
                structure_types: null,
            },
        },
    },
    components: {
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
            this.roof.latitude = center[0];
            this.roof.longitude = center[1];
            this.roof.position.lat = center[0];
            this.roof.position.lng = center[1];
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
            this.errors = {};
            this.$http[method](url, params).then(
                response => {
                    this.editingOwner = false;
                    if (this.roof.id == false) {
                        this.$router.replace({
                            name:'roof-edit',
                            params: { roofId: response.body.id }
                        });
                    }
                    this.roof = response.body;

                    this.$notify({
                        type:'success',
                        title: 'sauvegarde réussie',
                        text: 'well done jack'
                    });

                },
                response => {
                    if (response.status !== 400) {
                        return false;
                    }
                    this.errors = response.body;
                }
            );
        },
        loadRoof: function(roofId) {
            this.$http.get(
                process.env.API_URL + 'roofs/' + roofId
            ).then(
                response => {
                    this.roof = response.body;
                    let center = {
                        lat: parseFloat(this.roof.latitude),
                        lng: parseFloat(this.roof.longitude)
                    };
                    this.roof.position = center;
                    this.center = center;
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
            url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            center: center,
            editingOwner: false,
            errors: {},
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
                latitude: center.lat,
                longitude: center.lng,
                position: center,
                slope: 0,
                ground_square_area: 0,
                occupancy_rate: 0,
                south_orientation: 0,
                // relations
                owner_id: 0,
                owner: { name:'' },
                structure_id: null,
                purchase_category_id: 0,
                type_id: 0,
                department_id: 0
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
