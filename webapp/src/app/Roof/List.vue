<template>
    <div class="row">
        <a href="#" class="col-xs-11">{{ roof.name }}</a>
        <div class="col-xs-1">
        o   <small>{{ roof.propability }}</small>
        </div>
        <div class="offset-md-1 col-xs-12">
            <i>{{ roof.structure_id }}</i>
        </div>
        <div class="col-xs-12">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Puissance min: </label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" :value="roof.power_min">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Puissance max: </label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" :value="roof.power_min">
                </div>
            </div>

        </div>
    </div>
</template>

<script>

export default {
    props: {
        roof: { type: Object, required:true }
    }
}
</script>

