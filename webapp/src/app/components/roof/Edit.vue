<template>
    <div>
        <b-nav tabs v-if="!isNew">
            <b-nav-item v-on:click="goTo('roof-edit')" >détails</b-nav-item>
            <b-nav-item v-on:click="goTo('roof-historical')">historique</b-nav-item>
        </b-nav>
        <router-view></router-view>
    </div>
</template>

<script>

export default {
    methods: {
        goTo: function(name) {
            this.$router.push({
                name: name,
                params: { roofId: this.roofId }
            });
        }
    },
    mounted() {
        if (this.$route.params.roofId != false) {
            this.roofId = this.$route.params.roofId;
        }
    },
    data() {
        return {
            roofId: false
        }
    },
}
</script>

<style>
</style>
