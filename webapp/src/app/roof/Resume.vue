<template>
    <form class="container">
        <div class="form-group row mt-3">
            <label class="col-2">Structure</label>
            <input class="col-10 form-control" type="text" :value="roof.structure.name">
        </div>

        <div class="form-group row">
            <label class="col-2">Probabilité</label>
            <input class="col-10 form-control" type="text" :value="roof.probability">
        </div>
        <div class="form-group row">
            <label class="col-2">Catégorie tarif</label>
            <input class="col-10 form-control" type="text" :value="roof.purchase_category.name">
        </div>

        <div class="form-group row">
            <label class="col-2">Remarques</label>
            <textarea class="col-10 form-control" type="text">{{ roof.remarks }}</textarea>
        </div>

        <button type="button" class="btn btn-link"
            onclick="history.back()">Retour</button>


        <div class="form-group" v-for="key in getKeys()">
            <label for="id">{{ key }}</label>
            <input id="id" class="form-control" type="text" :value="roof[key]">
        </div>

    </form>
</template>

<script>
import Vue2Leaflet from 'vue2-leaflet';

export default {
    props: {
        roof:{ type:Object, default:{} },
    },
    methods: {
        getKeys: function() {
            return Object.keys(this.roof);
        },
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
                    console.log(this.roofs);
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
    }
}
</script>

<style>
.close {
    top:1em; right:1em;
    position:absolute;
    cursor:pointer;
}
</style>
