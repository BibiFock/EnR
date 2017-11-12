<template>
    <div class="border border-secondary rounded row roof-list"
        v-on:click="showDetail()">
        <div class="col-11" v-if="roof.structure">
            <h3>{{ roof.structure.name }}</h3>
        </div>
        <div class="col-1">
            <small>{{ roof.probability }}</small>
        </div>
        <div class="col-12">
            <div class="form-group row">
                <label class="col-3 col-form-label">Puissance min: </label>
                <div class="col-9">
                    <input type="text" readonly class="form-control-plaintext" :value="roof.power_min">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-3 col-form-label">Puissance max: </label>
                <div class="col-9">
                    <input type="text" readonly class="form-control-plaintext" :value="roof.power_max">
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        roof: { type: Object, required:true }
    },
    methods: {
        showDetail: function() {
            this.$router.push({
                name: 'roof',
                params: { roofId: this.roof.id }
            });
        }
    }
}
</script>

<style>
.roof-list {
    cursor:pointer;
}
</style>
