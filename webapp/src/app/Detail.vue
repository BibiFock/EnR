<template>
    <form class="container">
        <div class="form-group" v-for="key in getKeys()">
            <label for="id">{{ key }}</label>
            <input id="id" class="form-control" type="text" :value="marker[key]">
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
        marker:{ type:Object, default:{} },
    },
    methods: {
        getKeys: function() {
            return Object.keys(this.marker);
        },
        close: function() {
            this.$emit('close');
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
