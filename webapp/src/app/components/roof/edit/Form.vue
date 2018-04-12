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
                    <option v-for="probability in types.probabilities" :key="index" >
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
                    <option v-for="structure in types.structures" :key="structure.id"
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
                <div class="input-group">
                    <input class="form-control" type="text"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('square_area')}"
                        v-model="roof.square_area">
                    <div class="input-group-prepend">
                        <div class="input-group-text">m²</div>
                    </div>
                </div>

                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('square_area')"> {{ errors['square_area'].join(',') }} </div>
            </div>
        </div>

        <div class="form-row col-12">
            <label class="col-md-2 col-12 text-md-right">puissance</label>
            <div class="col-md-10 col-12 pt-0 row mb-2">
                <div class="col-12 col-sm-6 row">
                    <label class="col-4 text-md-right">min</label>
                    <div class="col-8 pt-0">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                v-bind:class="{'is-invalid': errors.hasOwnProperty('power_min')}"
                                v-model="roof.power_min">
                            <div class="input-group-prepend">
                                <div class="input-group-text">kWc</div>
                            </div>
                        </div>
                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('power_min')">
                            {{ errors['power_min'].join(',') }}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 row">
                    <label class="col-4 text-md-right">max</label>
                    <div class="col-8 pt-0">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                v-bind:class="{'is-invalid': errors.hasOwnProperty('power_max')}"
                                v-model="roof.power_max">
                            <div class="input-group-prepend">
                                <div class="input-group-text">kWc</div>
                            </div>
                        </div>

                        <div class="invalid-feedback"
                            v-if="errors.hasOwnProperty('power_max')">
                            {{ errors['power_max'].join(',') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row col-12">
            <label class="col-md-2 text-md-right">Catégorie de tarif</label>
            <div class="col-md-10 pt-0">
                <select class="form-control"
                    v-bind:class="{'is-invalid': errors.hasOwnProperty('purchase_category_id')}"
                    v-model="roof.purchase_category_id">
                    <option v-for="pc in types.purchase_categories" :key="pc.id"
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
            <label class="col-md-2 text-md-right">hauteur du bâtiment</label>
            <div class="col-md-10 pt-0">
                <div class="input-group">
                    <input class="form-control" type="text"
                        v-bind:class="{'is-invalid': errors.hasOwnProperty('building_size')}"
                        v-model="roof.building_size" />
                    <div class="input-group-prepend">
                        <div class="input-group-text">m</div>
                    </div>
                </div>
                <div class="invalid-feedback"
                    v-if="errors.hasOwnProperty('building_size')"> {{ errors['building_size'].join(',') }} </div>
            </div>
        </div>

        <div class="row mb-4">
            <fieldset class="row col-12">
                <div class="form-group row col-12">
                    <label class="col-md-2 text-md-right">toitures</label>
                    <div class="col-md-10 pt-0">
                        <select class="form-control col-md-7 d-inline"
                            v-bind:class="{'is-invalid': errors.tilts.length > 0}"
                            v-model="tiltIndex" >
                            <option v-for="(tilt, index) in roof.tilts"
                                :key="tilt.id"
                                :value="index" >{{ tilt.name }}</option>
                        </select>
                        <button type="button"
                            class="btn btn-secondary col-md-2 d-inline"
                            v-on:click="addTilt()"> ajouter </button>
                        <button type="button"
                            v-if="roof.tilts.length > 1"
                            class="btn btn-secondary col-md-2 d-inline"
                            v-on:click="deleteTilt()"> supprimer </button>
                    </div>
                </div>

                <FormTilt
                    :tilt="roof.tilts[tiltIndex]"
                    :types="types.types"
                    :errors="errors.tilts[tiltIndex]"></FormTilt>
            </fieldset>
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
                            <option v-for="so in types.structure_types" :key="so.id"
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
                <div class="col-md-10">
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
import cloneDeep from 'lodash/cloneDeep';
import { mapState, mapActions, mapMutations } from 'vuex';
import FormTilt from './Form-tilt.vue';

export default {
    computed: {
        ...mapState('roofs', [ 'types' ])
    },
    components: { FormTilt },
    methods: {
        ...mapActions('roofs', [ 'loadTypesInfos', 'loadRoof' ]),
        ...mapMutations('roofs', [ 'roofSaved' ]),
        addTilt: function() {
            this.roof.tilts.push({
                name: 'toiture ' + (this.roof.tilts.length+1),
                latitude: null,
                longitude: null,
                slope: 0,
                ground_square_area: 0,
                occupancy_rate: 0,
                south_orientation: 0,
                // relations
                type_id: 0,
            });
            this.tiltIndex = (this.roof.tilts.length-1);
        },
        deleteTilt: function() {
            let result  = confirm(
               'Voulez vous vraiment supprimer la toiture: '
               + (this.tiltIndex+1) + '. ' + this.roof.tilts[this.tiltIndex].name + '?!',
            );
            if (!result) {
                return false;
            }
            let tilts = JSON.parse(JSON.stringify(this.roof.tilts));
            tilts.splice(this.tiltIndex, 1);
            if (this.tiltIndex >= tilts.length) {
                this.tiltIndex = tilts.length-1;
            }

            this.roof.tilts = tilts;
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

            this.errors = {
                tilts: []
            };

            this.$http[method](url, params).then(
                response => {
                    if (this.roof.id == false) {
                        this.$router.replace({
                            name:'roof-edit',
                            params: { roofId: response.body.id }
                        });
                    }
                    this.roof = response.body;
                    this.editingOwner = (this.roof.owner_id === null);

                    this.$notify({
                        type:'success',
                        title: 'sauvegarde réussie',
                        text: 'well done jack'
                    });

                    this.roofSaved(response.body);
                },
                response => {
                    if (response.status !== 400) {
                        return false;
                    }

                    let errors = { tilts: [] };
                    for (let key in response.body) {
                        if (!/^tilts./.test(key)) {
                            errors[key] = response.body[key];
                            continue;
                        }
                        let details = key.split('.');
                        if (!errors.tilts[details[1]]) {
                            errors.tilts[details[1]] = {};
                        }
                        errors.tilts[details[1]][details[2]] = response.body[key]
                            .map(el => el.replace( 'tilts.' + details[1] + '.', ''));
                    }
                    this.errors = errors;
                }
            );
         }
    },
    mounted() {
        setTimeout(() => this.loadTypesInfos(), 10);

        if (this.$route.params.roofId != false) {
            let roofId = this.$route.params.roofId;
            setTimeout(() => {
                this.loadRoof(roofId)
                    .then(roof => {
                        this.roof = cloneDeep(roof);
                        if (this.roof.owner) {
                            this.editingOwner = false;
                        } else {
                            this.roof.owner = {};
                            this.editingOwner = true;
                        }

                    });
            }, 100);
        } else {
            this.addTilt();
            this.editingOwner = true;
        }
    },
    data() {
        return {
            editingOwner: false,
            errors: { tilts: [] },
            owner: {
                id: 0,
                name: '',
                contact_id: 0,
                contact: { }
            },
            tiltIndex: 0,
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
                street: '',
                zip: '',
                city: '',
                // relations
                tilts: [],
                owner_id: 0,
                owner: { name:'' },
                structure_id: null,
                purchase_category_id: 0,
            }
        }
    }

}
</script>

<style>
</style>
