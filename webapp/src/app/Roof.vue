<template>
    <div class="roof">
        <v-list :roof="roof" v-if="type == 'list'"></v-list>
    </div>
</template>

<script>
import RoofList from './roof/List';

export default {
    components: {
        'v-list': RoofList
    },
    props: {
        type: { type: String, required: true},
        roof: { type: Object, required: true}
    }
}
</script>

<style>
</style>
