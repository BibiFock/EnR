<template>
    <form class="container">
        <div class="form-group row mt-3" v-if="roof.structure">
            <label class="col-2 text-right">structure</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.structure.name">
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">probabilité</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.probability">
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">surface</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.square_area">
        </div>

        <div class="form-row">
            <label class="col-2 text-right">puissance</label>
            <div class="col-10 pt-0 row">
                <label class="col-2 text-right">min</label>
                <input type="text" class="col-1 pt-0 form-control-plaintext" :value="roof.power_min">

                <label class="col-2 text-right">max</label>
                <input type="text" class="col-1 pt-0 form-control-plaintext" :value="roof.power_max">
            </div>
        </div>

         <div class="form-group row" v-if="roof.purchase_category">
            <label class="col-2 text-right">catégorie tarif</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.purchase_category.name">
        </div>

         <div class="form-group row" v-if="roof.type">
            <label class="col-2 text-right">type</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.type.name">
        </div>

         <div class="form-group row" v-if="roof.tilt">
            <label class="col-2 text-right">tilt</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.tilt.name">
        </div>

         <div class="form-group row" v-if="roof.south_orientation">
            <label class="col-2 text-right">south_orientation</label>
            <input class="col-10 pt-0 form-control-plaintext" type="text" :value="roof.south_orientation.name">
        </div>

        <div class="form-check offset-2">
            <label class="form-check-label col-2">
                <input class="form-check-input" disabled type="checkbox"
                    value="1" v-model="roof.erp">
                ERP
            </label>
            <label class="form-check-label col-2">
                <input class="form-check-input" disabled type="checkbox"
                    value="1" v-model="roof.perimeter_abf">
                périmètre ABF
            </label>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">taille du bâtiment</label>
            <textarea class="col-10 pt-0 form-control-plaintext" type="text">{{ roof.building_size }}</textarea>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">position onduleur</label>
            <textarea class="col-10 pt-0 form-control-plaintext" type="text">{{ roof.inverter_location }}</textarea>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">distance onduleur</label>
            <textarea class="col-10 pt-0 form-control-plaintext" type="text">{{ roof.inverter_distance }}</textarea>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">distance onduleur</label>
            <textarea class="col-10 pt-0 form-control-plaintext" type="text">{{ roof.inverter_distance }}</textarea>
        </div>

        <div class="form-group row">
            <label class="col-2 text-right">remarques</label>
            <textarea class="col-10 pt-0 form-control-plaintext" type="text">{{ roof.remarks }}</textarea>
        </div>

        <div class="form-group">
            <label class="col-2 text-right" >adresse</label>
            <input type="text" class="form-control-plaintext" :value="roof.street" >
        </div>

        <div class="form-row">
            <label class="col-2 text-right">ville</label>
            <div class="col-10 pt-0">
                <input type="text" class="pt-0 form-control-plaintext col-3" :value="roof.city" >
                <label class="col-1">zip</label>
                <input type="text" class="pt-0 col-1 form-control-plaintext" :value="roof.zip">
            </div>
            <div class="form-group col-2" v-if="roof.department">
                <label >département</label>
                <input type="text" class="pt-0 form-control-plaintext" :value="roof.department.code">
            </div>
        </div>

        <div class="row clearfix" v-if="roof.latitude">
            <v-map class="offset-2 col-5 map-preview" :zoom="zoom" :center="[roof.latitude, roof.longitude]">
                <v-tilelayer :url="url" :attribution="attribution"></v-tilelayer>
                <v-marker :lat-lng="[roof.latitude, roof.longitude]" >
                </v-marker>
            </v-map>
        </div>

        <button type="button" class="btn btn-link"
            onclick="history.back()">retour</button>
    </form>
</template>

<script>
import Vue2Leaflet from 'vue2-leaflet';

export default {
    props: {
        roof:{ type:Object, default:{} },
    },
    components: {
        'v-map': Vue2Leaflet.Map,
        'v-tilelayer': Vue2Leaflet.TileLayer,
        'v-marker': Vue2Leaflet.Marker,
    },
    methods: {
        close: function() {
            this.$emit('close');
        },
        loadRoof: function(roofId) {
            this.$http.get(
                // 'http://raw.githubusercontent.com/BibiFock/EnR/master/data.json'
                process.env.API_URL + 'roofs/' + roofId
            ).then(
                response => {
                    this.roof = response.body;
                },
                response => {
                    console.log(response)
                    alert('todo make nofication error');
                }
            );
        }
    },
    mounted() {
        if (this.$route.params.roofId) {
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
