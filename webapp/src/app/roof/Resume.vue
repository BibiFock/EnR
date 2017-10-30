<template>
    <form class="container">
        <div class="form-group" v-for="key in getKeys()">
            <label for="id">{{ key }}</label>
            <input id="id" class="form-control" type="text" :value="roof[key]">
        </div>

        <button type="button" class="close" aria-label="Close"
            v-on:click="close()">
            <span aria-hidden="true">&times;</span>
        </button>
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
                'http://localhost:8080/api/roofs/' + roofId
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
